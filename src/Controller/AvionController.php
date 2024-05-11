<?php

namespace App\Controller;

use App\Entity\Avion;
use App\Form\AvionType;
use App\Repository\AvionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/avion')]
class AvionController extends AbstractController
{
    #[Route('add_avion', name:'ajoutez_avion')]
    public function addAvion(Request $request, EntityManagerInterface $em):Response
    {
        //On créer une nouvel instance de l'entité Avion
        $avion = new Avion();

        $form = $this->createForm(AvionType::class);//On crée le formulaire

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())//Si le formulaire est soumis et qu'il est valide
        {
            $nameAvion = $form->get('nom')->getData();//On récypère le nom de l'avion
            $numberOfPlaces = $form->get('places')->getData();//On récupère le nombre de places disponible dans l'avion
            // dd($nameAvion, $numberOfPlaces);

            //Permet de vérifier si $numberOfPlaces est numérique et si il est un entier
            if(!is_numeric($numberOfPlaces) || (int)$numberOfPlaces != $numberOfPlaces) {
                $this->addFlash('error', 'Le nombre de places doit être un nombre entier.');
                return $this->render('avion/ajoutez_avion.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
            $avion->setNom($nameAvion);//On set le nom de l'avion 
            $avion->setPlaces($numberOfPlaces);//On set le nombre de places 

            //On enregistre les données dans la base de donnée
            $em->persist($avion);
            $em->flush();
            
            $this->addFlash('success', 'Avion ajouté avec succès.');
            //On gère le redirection
            return $this->redirectToRoute('ajoutez_avion', [], Response::HTTP_SEE_OTHER);
        }
        //On gère l'affichage du formulaire
        return $this->render('avion/ajoutez_avion.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('all_avion', name:'tous_les_avions')]
    public function allAvion(AvionRepository $avionRepository)
    {
        $avions = $avionRepository->findAll();//On récupère tous les avions dans la base de données
        // dd($avion);
      //On passe tous les avions récupérer à la vue 
      return $this->render('avion/all_avion.html.twig', [
            'avions' => $avions,
        ]);
    }

    #[Route('/modify_avion/{id}', name:"modifier_avion", methods:['POST', 'GET'])]
    public function avionModify(Request $request, EntityManagerInterface $em, int $id, AvionRepository $avionRepository):Response
    {
        $avion = $avionRepository->find($id);
        // dd($avionModifier);

        $form = $this->createForm(AvionType::class, $avion);

        $form->handleRequest($request);

        if($form-> isSubmitted() && $form->isValid())
        {
            $nomAvion = $form->get('nom')->getData();
            $placesAvion = $form->get('places')->getData();

            $avion->setNom($nomAvion);
            $avion->setPlaces($placesAvion);

            // dd($avionModifier);
            $em->persist($avion);
            $em->flush();
            
            return $this->redirectToRoute('tous_les_avions', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('avion/modifier_avion.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('delete_avion/{id}', name:"supprimer_avion", methods:['POST'])]
    public function deletedAvion(Request $request, AvionRepository $avionRepository, int $id): Response
    {
        //Récupère le token génerer dans la requête
        $submittedToken = $request->request->get('token');
    
        //Vérifie si le Csrf est valide et que le token a été soumis
        if ($this->isCsrfTokenValid('delete-item', $submittedToken)){
            $avionDelete = $avionRepository->find($id);//On récupère l'avion par sont id
            
            //Si l'avion est trouvé, on procède à sa suppression
            if($avionDelete){
                //Utilise la fonction delete du repository pour supprimer l'avion
                $avionRepository->delete($avionDelete);
                
                //On affiche le message de succès 
                $this->addFlash('success', 'Avion supprimer avec succès.');
                
                //On éxecute la redirection
                return $this->redirectToRoute('tous_les_avions', [], Response::HTTP_SEE_OTHER);
            } else {
                //Si aucun avion n'est trouvé, affiche un message d'erreur 
                $this->addFlash('error', "Aucun avion trouvé avec l'id fourni.");

                return $this->redirectToRoute('tous_les_avions', [], Response::HTTP_SEE_OTHER);
            }
        } else {//Si le token est invalid, on renvoi un message d'erreur 
            return new Response('Token invalide.', 403);
        }
    }
}
