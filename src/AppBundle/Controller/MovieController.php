<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Critique;
use AppBundle\Entity\Movie;
use AppBundle\Entity\People;
use AppBundle\Entity\WatchListItem;
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


        $suggested = $repoMovie->findSuggested($movie);


        dump($suggested);

        // partie commentaires

            // on crée un nouvel objet critique
            $critique = new Critique();
            // on crée le formulaire
            $critiqueForm = $this->createForm(CritiqueType::class,$critique);

            // initialisation du boolean pour affichage page detail
        $isInWatchList = false;


        $repoWatchItems = $this->getDoctrine()->getRepository(WatchListItem::class);
        $previousItem = $repoWatchItems->findOneBy([
            "movie"=>$movie,
            "user" => $this->getUser(),
        ]);

        // si dejà associé user et film => le boolean passe à true
        if($previousItem != null){
            $isInWatchList = true;
        }

        //si le user a le droit, on gère la request (formulaire)
        if ($this->isGranted("ROLE_USER")) {
            // on hydrate l'objet = on met automatiquement dans la var critique qu'on lui a passé au dessus
            $critiqueForm->handleRequest($request);




            //si le user est connecté, on va vérifier si le movie est dans sa watchlist


            //si formulaire soumis, on set movie et user et on persist la critique
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
                    "id"=>$id,
                    "isInWatchList"=>$isInWatchList,
                ]);

            }

            }



        return $this->render('movie/detail.html.twig', [
            "movie"=>$movie,
            "critiqueForm"=>$critiqueForm->createView(),
            "suggested"=>$suggested,
            "isInWatchList"=>$isInWatchList,
        ]);
    }



    // ___________________________ RECUPERATION MOVIE PAR PEOPLE _____________________ //

    public function peopleAction($imdbId)
    {

        // récupérer le repo des people
        $repo = $this->getDoctrine()->getRepository(Movie::class);

        // faire requete spéciale findMovieWithPeople(idpeople) dans le movie repository
        $movies = $repo->findMoviesWithPeople($imdbId);


        //on envoie la page de resultat
        return $this->render('movie/results.html.twig', [
            "movies"=>$movies
        ]);

    }


}
