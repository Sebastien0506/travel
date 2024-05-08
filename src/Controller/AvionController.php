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
        $avion = new Avion();

        $form = $this->createForm(AvionType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $nameAvion = $form->get('nom')->getData();
            $numberOfPlaces = $form->get('places')->getData();
            // dd($nameAvion, $numberOfPlaces);
            $avion->setNom($nameAvion);
            $avion->setPlaces($numberOfPlaces);

            $em->persist($avion);
            $em->flush();

            return $this->redirectToRoute('ajoutez_avion', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('avion/ajoutez_avion.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('all_avion', name:'tous_les_avions')]
    public function allAvion(AvionRepository $avionRepository)
    {
        $avions = $avionRepository->findAll();
        // dd($avion);

      return $this->render('avion/all_avion.html.twig', [
            'avions' => $avions,
        ]);
    }
}
