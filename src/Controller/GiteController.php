<?php

namespace App\Controller;

use App\Entity\Gite;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GiteController extends AbstractController
{

  /**
  * @Route("gite/{id}", name="gite_show")
  */
  public function show(Gite $gite){ 
  # On peut aussi faire comme ça pour se simplifier la vie
  # Vive Symfony, Vive la France, Vive la République XD

  // }
  // public function show(ManagerRegistry $doctrine, int $id)
  // {

  //   $repository = $doctrine->getRepository(Gite::class);

  //   $gite = $repository->find($id);
  //   dump($gite);

    return $this->render('gite/show.html.twig',[
      'gite' => $gite,
    ]);
  }
}