<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SerieController extends AbstractController
{
    /**
     * @Route("/series/{page}", name="serie_list", requirements={"page"="\d+"})
     */
    public function list(int $page = 1, SerieRepository $serieRepository): Response
    {
        //Recuperer le repository
        //2eme solution : $serieRepository = $this->getDoctrine()->getRepository(Serie::class);
        //3eme solution : $serieRepository = $entityManager->getRepository(Serie::class);
        //                mettre entityManagerInterface $entityManager en parametre

        //recuperer toute la table
        //$series = $serieRepository->findAll();

        //recupere les 50 premieres series trié dans l'ordre decroissant selon le vote
        //$series = $serieRepository->findBy([], ["vote" =>"DESC"], 50);



        $nbSeries = $serieRepository->count([]);
        $maxPage = ceil($nbSeries / 50);

        //si la page exist pas
        if ($page>=1 && $page <=$maxPage){
            $series = $serieRepository->finsBestSeries($page);
        }else{
            throw $this->createNotFoundException("Oops 404 !");
        }

        return $this->render('serie/list.html.twig', [
                "series"=>$series,
                "currentPage"=>$page,
                "maxPage" => $maxPage,
        ]);
    }

    /**
     * @Route("/series/detail/{id}", name="serie_detail")
     */
    public function detail($id, SerieRepository $serieRepository): Response
    {
        $serie = $serieRepository->find($id);

        //leve exception si serie existe pas : page 404
        if (!$serie){
            throw $this->createNotFoundException("Oops ! This serie does not exist !");

            //on peut aussi le rediriger
            //return $this -> redirectToRoute('main_home');
        }


        return $this->render('serie/detail.html.twig', [
            "serie" => $serie
        ]);
    }

    /**
     * @Route("/series/create", name="serie_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        //genere le formulaire à partie de SerieType pour l'envoi au twig
        $serie = new Serie();
        $serieForm = $this->createForm(SerieType::class, $serie);
        $serie->setDateCreated(new \DateTime());

        //associe les données à l'instance de serie
        $serieForm->handleRequest($request);

        //test si le formulaire est soumit et si données sont valides
        if ($serieForm->isSubmitted() && $serieForm->isValid()){

            //enregistre les données en BDD
            $entityManager->persist($serie);
            $entityManager->flush();

            //message flash
            $this->addFlash('success', 'Serie added !');

            return $this->redirectToRoute('serie_detail', ['id' => $serie->getId()]);

        }

        return $this->render('serie/create.html.twig', [
            'serieForm' => $serieForm->createView()
        ]);
    }

    /**
     * @Route("/series/delete/{id}", name="serie_delete")
     */
    public function delete($id, EntityManagerInterface $entityManager, SerieRepository $serieRepository): Response
    {
        //$serie = $serieRepository->find($id);
        //même methode pour atteindre la methode find()
        $serie = $entityManager->find(Serie::class, $id);
        $entityManager->remove($serie);
        $entityManager->flush();

        $this->addFlash('success', 'Serie deleted !');

        return $this->redirectToRoute('main_home');
    }

    /**
     * @Route("/series/edit/{id}", name="serie_edit")
     */
    public function edit($id, SerieRepository $serieRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $serie = $serieRepository->find($id);

        //leve exception si serie existe pas : page 404
        if (!$serie){
            throw $this->createNotFoundException("Oops ! This serie does not exist !");
        }

        $serieForm = $this->createForm(SerieType::class, $serie);
        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()){
            $serie->setDateModified(new \DateTime());

            //enregistre les données en BDD
            $entityManager->persist($serie);
            $entityManager->flush();

            //message flash
            $this->addFlash('success', 'Serie edited !');

            return $this->redirectToRoute('serie_detail', ['id' => $serie->getId()]);

        }
        return $this->render('serie/edit.html.twig', [
            "serie" => $serie,
            "serieForm"=>$serieForm->createView()
        ]);
    }
}
