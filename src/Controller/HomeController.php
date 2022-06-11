<?php

namespace App\Controller;

use App\Entity\Gite;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
  /**
   * @Route("home", name="home_index")
   */
  public function index(ManagerRegistry $doctrine)
  {

    $repository = $doctrine->getRepository(Gite::class);

    $gite = $repository->find(1);
    $gites = $repository->findAll();

    dump($gite);

    // $manager = $doctrine->getManager();

    // $gite = new Gite();

    // $gite
    //   ->setName('Mon premier Gite')
    //   ->setDescription('Une longue description')
    //   ->setSurface(80)
    //   ->setChambre(3)
    //   ->setCouchage(6);

    // $manager->persist($gite);

    // $manager->flush();


    return $this->render("home/index.html.twig",[
      'menu' => 'home',
      'gite' => $gite,
      'gites' => $gites
    ]);
  }

  /**
   * @Route("contact", name="home_contact")
   */
  public function contact()
  {
    return $this->render("home/contact.html.twig", [
      'menu' => 'contact'
    ]);
  }
}