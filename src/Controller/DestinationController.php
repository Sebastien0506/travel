<?php

namespace App\Controller;

use App\Entity\VilleImage;
use App\Entity\Destination;
use App\Form\DestinationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DestinationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/destination')]
class DestinationController extends AbstractController
{
    #[Route('all_destination', name:'toute_destination')]
    public function allDestination(DestinationRepository $destinationRepository)
    {
        $allDestination = $destinationRepository->findAllDestionWithDestiantion();
        // dd($allDestination);

        return $this->render('destination/all_destination.html.twig', [
            "destinations" => $allDestination,
        ]);
    }

    #[Route('add_destination', name:'ajoutez_destination')]
    public function addDestination(Request $request, EntityManagerInterface $em):Response
    {
        $destination = new Destination();//On crée une instance de destination

        $form = $this->createForm(DestinationType::class);//On créer le formulaire

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {//Si le formulaire est soumis et qu'il est valide
            $newDestination = $form->get('nomDeLaDestination')->getData();//On récupère les données du formulaire
            
            $avionDestination = $form->get('avion')->getData();
            // dd($avion);
            
            foreach($avionDestination as $avion){
                $avion->getNom();
                // dd($avion);
                $destination->addAvion($avion);
            }

            $images = $form->get('images')->getData();
// dd($images);
            foreach($images as $image){
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                $image->move(
                    $this->getParameter('image_directory'),
                    $fichier
                );

                $img = new VilleImage();
                $img->setName($fichier);
                $destination->addVilleImage($img);
            }
            $destination->setNomDeLaDestination($newDestination);//On donne à destination le nom de la destination que l'on vient d'ajouter
            // dd($destination);
            //On enregistrez les nouvelles données dans la base de donnée
            $em->persist($destination);
            $em->flush();

            //On gère la redirection
            return $this->redirectToRoute('ajoutez_destination', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('destination/ajoutez_destination.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('modify_destion/{id}', name:'modifiez_destionation', methods:['POST', 'GET'])]
    public function destinationModify(Request $request, EntityManagerInterface $em, int $id, DestinationRepository $destinationRepository): Response
    {
        $destination = $destinationRepository->find($id); 
        // dd($destinationModifer);
        $avions = $destination->getAvions();
        // dd($avions);
        $form = $this->createForm(DestinationType::class, $destination);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid())
        {
            $destinationModifier = $form->get('nomDeLaDestination')->getData();
            $avionsModifier = $form->get('avions')->getData();

            $images = $form->get('images')->getData();
            foreach($images as $image){
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                $image->move(
                    $this->getParameter('image_directory'),
                    $fichier
                );

                $img = new VilleImage();
                $img->setName($fichier);
                $destination->addVilleImage($img);
            }
            $destination->setNomDeLaDestination($destinationModifier);
            $destination->getAvions($avionsModifier);
// dd($destination);
            $em->persist($destination);
            $em->flush();
            
            $this->addFlash('succes', 'Destination modifier avec succès');

            return $this->redirectToRoute('toute_destination', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('destination/modifiez_destination.html.twig', [
            'form' => $form->createView(),
            'destination' =>$destination,
            'avions' => $avions,
        ]);
    }
}
