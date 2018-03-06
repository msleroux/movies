<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Critique;
use AppBundle\Entity\Movie;
use AppBundle\Form\CritiqueType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MovieController extends Controller
{
    public function detailAction($id, Request $request)
    {
        // partie movie
        $repoMovie = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $repoMovie->find($id);


        // partie commentaires

            // on crée un nouvel objet critique
            $critique = new Critique();
            // on crée le formulaire
            $critiqueForm = $this->createForm(CritiqueType::class,$critique);

        //si le user a le droit, on gère sa
        if ($this->isGranted("ROLE_USER")) {
            // on hydrate l'objet = on met automatiquement dans la var critique qu'on lui a passé au dessus
            $critiqueForm->handleRequest($request);


            //si formulaire soumis, on set movie et user et on persist les données
            if($critiqueForm->isSubmitted()&&$critiqueForm->isValid()){

                $critique->setMovie($movie);
                $critique->setUser($this->getUser());

                //$movie->addCritique($critique);
                //$user->addCritique($critique); PAS BESOIN

                $em = $this->getDoctrine()->getManager();
                $em->persist($critique);

                $em->flush();
                $this->addFlash("success", "Your review has been added !");

                return $this->redirectToRoute("movie_detail",[
                    "id"=>$id
                ]);

            }
        } else {
            $this->addFlash("error", "You must be logged in !");
        }


        return $this->render('movie/detail.html.twig', [
            "movie"=>$movie,
            "critiqueForm"=>$critiqueForm->createView()]);
    }


}
