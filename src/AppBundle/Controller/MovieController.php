<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MovieController extends Controller
{
    public function detailAction($id)
    {
        $repo = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $repo->find($id);



        return $this->render('movie/detail.html.twig', ["movie"=>$movie]);
    }
}
