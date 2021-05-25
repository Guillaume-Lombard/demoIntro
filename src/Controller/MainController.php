<?php


namespace App\Controller;


use App\Entity\Serie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends  AbstractController
{

    /**
     * @Route("/", name="main_home")
     */
    public function home(){

        return $this->render("main/home.html.twig");
    }

    /**
     * @Route("/test", name="main_test")
     */
    //EntityManagerInterface $entityManager <- injextion de dependance
    public function test(EntityManagerInterface $entityManager){

        //2eme methode pour recuperer entityManager
        //$entityManager = $this->getDoctrine()->getManagers()

        $serie = new Serie();

        $serie->setBackdrop("lkgtkjg")
            ->setDateCreated(new \DateTime())
            ->setFirstAirDate(new \DateTime('-1 year'))
            ->setGenres("Western")
            ->setLastAirDate(new \DateTime("-6 month"))
            ->setName("Lucky Luck")
            ->setPopularity(100.8)
            ->setPoster("hlqjgmh")
            ->setStatus("returning")
            ->setTmdbId(123456)
            ->setVote(9.8);

        dump($serie);

        //creation
        $entityManager->persist($serie);
        $entityManager->flush();

        //modification
        $serie->setName("Calamity Jane");
        $entityManager->persist($serie);
        $entityManager->flush();

        dump($serie);

        //suppression
        $entityManager->remove($serie);
        $entityManager->flush();

        return $this->render('main/test.html.twig');
    }
}