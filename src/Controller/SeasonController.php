<?php

namespace App\Controller;

use App\Entity\Season;
use App\Form\SeasonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SeasonController extends AbstractController
{
    /**
     * @Route("/season/create", name="season_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $season = new Season();
        $seasonForm = $this->createForm(SeasonType::class, $season);
        $seasonForm->handleRequest($request);

        if ($seasonForm->isSubmitted() && $seasonForm->isValid()){

            $season->setDateCreated(new \DateTime());
            $entityManager->persist($season);
            $entityManager->flush();

            $this->addFlash('success', 'Season added !');
        }

        return $this->render('season/create.html.twig', [
            'seasonForm'=>$seasonForm->createView()
        ]);
    }
}
