<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function homeAction()
    {
        // sur la page d'accueil on va trouver la liste de 50 films
        $repo = $this->getDoctrine()->getRepository(Movie::class);
        $movies = $repo->findBy([],["id"=>"ASC"],50,0);
        //dump($movies);
        //die();

        return $this->render('default/home.html.twig', ["movies"=>$movies]);
    }
}
