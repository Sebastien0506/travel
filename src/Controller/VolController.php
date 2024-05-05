<?php

namespace App\Controller;

use App\Entity\Vol;
use App\Form\VolType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VolController extends AbstractController
{
    #[Route('/vol', name: 'app_vol')]
    public function index(): Response
    {
        return $this->render('vol/index.html.twig', [
            'controller_name' => 'VolController',
        ]);
    }

    #[Route('add_vol', name:'ajoutez_vol')]
    public function addVol(Request $request, EntityManagerInterface $em)
    {
        $vol = new Vol();

        $form = $this->createForm(VolType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $destiantion = $form->get('nomDeLaDestination')->getData();

            $vol->setNomDeLaDestination($destiantion);
// dd($vol);
            $em->persist($vol);
            $em->flush();

            return $this->redirectToRoute('ajoutez_vol', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vol/ajouter_vol.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
