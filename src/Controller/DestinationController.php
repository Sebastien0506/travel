<?php

namespace App\Controller;

use App\Entity\VilleImage;
use App\Entity\Destination;
use App\Form\DestinationType;
use App\Repository\VilleImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DestinationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/destination')]
class DestinationController extends AbstractController
{
    #[Route('destination_view_user', name:'destination_vue_utilisateur')]
    public function allDestinationUser(DestinationRepository $destinationRepository)
    {
        $allDestination = $destinationRepository->findAllDestinationAvionWithRelation();
// dd($allDestination);
        return $this->render('destination/destination_user.html.twig', [
            'allDestination' => $allDestination,
        ]);
    }
    #[Route('all_destination', name:'toute_destination')]
    public function allDestination(DestinationRepository $destinationRepository)
    {
        $allDestination = $destinationRepository->findAllDestionWithDestination();
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
        
        $image = $destination->getVilleImage();
        // dd($image);
        $destinationImageData = [];

        foreach($image as $villeImage){
            $destinationImage = $villeImage->getName();
            // dd($destinationImage);
            $idImageVille = $villeImage->getId();
            // dd($idImageVille);
            $destinationImageData = [
                'image' => $destinationImage,
                'id' => $idImageVille,
            ];
            // dd($destinationImageData);
        }
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
            'destination' => $destination,
            'avions' => $avions,
            'image' => $destinationImageData,
            
        ]);
    }

    #[Route('delete_destination/{id}', name:'supprimer_destination', methods:['POST'])]
    public function destinationDelete(Request $request, DestinationRepository $destinationRepository, int $id, EntityManagerInterface $em): Response
    {
        $submittedToken = $request->request->get('token');

        if($this->isCsrfTokenValid('delete-item', $submittedToken))
        {
            $destination = $destinationRepository->find($id);
            // dd($destinationDele)
            if($destination)
            {
                foreach($destination->getVilleImage() as $images)
                {
                    $imagePath = $this->getParameter('image_directory') . '/' . $images->getName();

                    if(file_exists($imagePath)){
                        unlink($imagePath);
                    }
                    $em->remove($images);
                }
                $em->remove($destination);
                $em->flush();

                $this->addFlash('success', 'Destination supprimer avec succès');

                return $this->redirectToRoute('toute_destination', [], Response::HTTP_SEE_OTHER);
            } else {
                $this->addFlash('error', 'Une erreur est survenue lors de la suppression de la destination');
                
                return $this->redirectToRoute('toute_destination', [], Response::HTTP_SEE_OTHER);
            }
        } else {
            return new Response('Token invalide', 403);
        }
    }
    #[Route('supprimer_image/{id}', name:"delete_image_destination", methods:['POST'])]
    public function deleteImageDestination(Request $request, VilleImageRepository $villeImageRepository, int $id, EntityManagerInterface $em): Response
    {   //On récupère le toke 
         $submittedToken = $request->request->get('_token');
        //  dd($submittedToken);
         //Si il est valide 
         if($this->isCsrfTokenValid('delete-item'. $id, $submittedToken))
         {  //On récupère l'image par sont id 
            $image = $villeImageRepository->find($id);
            // dd($image);
            if($image){//Si l'image existe on récupère le chemin vers le dossier ou est stocker l'image 
                $imagePath = $this->getParameter('image_directory') . '/' . $image->getName();

                if(file_exists($imagePath)){//Si le fichier existe on supprime l'image 
                    unlink($imagePath);
                }
                $em->remove($image);
                $em->flush();
                //On affiche un message de succès et on redirige 
                $this->addFlash('success', 'image supprimée avec succès.');
                return $this->redirectToRoute('toute_destination', [], Response::HTTP_SEE_OTHER);
            } else {//Si le fichier n'existe pas on affiche un message d'erreur
                $this->addFlash('error', 'Image introuvable');
            }
         } else {//Si le jeton CSRF est invalide on affiche un message d'erreur 
            $this->addFlash('error', 'Jeton CSRF invalide.');
         }
         return $this->redirectToRoute('toute_destination', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('afficher_destination/{id}', name:'view_destination')]
    public function afficherDestion(DestinationRepository $destinationRepository, int $id)
    {
        //On récupère la destination par sont id
        $destinationView = $destinationRepository->find($id);
        
        //On récupère l'image lier à la destination
        $image = $destinationView->getVilleImage();
    
        //On initialise la variable à un tableau vide 
        $destinationViewImage = [];
        
        //On boucle sur chaque image de la variable $villeImage
        foreach($image as $villeImage){
            $imageDestination = $villeImage->getName();//On récupère le nom de l'image 
            $imageId = $villeImage->getId();//On récupère sont id 
            // dd($imageDestination);

            $destinationViewImage = [//On stock toute les informations dans la variable $destinationViewImage
                'image' => $imageDestination,
                'id' => $imageId,
            ];
        }
        
        //On affiche le rendu 
        return $this->render('destination/afficher_destination.html.twig', [
            'image' => $destinationViewImage,
            'destinationView' => $destinationView,
        ]);
    }
}
