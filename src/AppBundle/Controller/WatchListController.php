<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Genre;
use AppBundle\Entity\Movie;
use AppBundle\Entity\WatchListItem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;


class WatchListController extends Controller
{

    public function deleteAction($imdbId, $pageOrigine)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $repoMovie = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $repoMovie->findOneBy(['imdbId' => $imdbId]);

        $repoWatchItems = $this->getDoctrine()->getRepository(WatchListItem::class);

        $itemInList = $repoWatchItems->findOneBy([
            "movie"=>$movie,
            "user" => $this->getUser(),
        ]);


        $em = $this->getDoctrine()->getManager();
        $em->remove($itemInList);
        $em->flush();

        $this->addFlash("success", "the movie has been removed to your watchList");



        return $this->redirectToRoute($pageOrigine, ["id" => $movie->getId(),
        ]);

    }


    public function addAction($imdbId)
    {
        // le user veut l'ajouter à sa watchList
        // il doit etre connecté
        $this->denyAccessUnlessGranted('ROLE_USER');

        // on récupère le movie concerné à patir de l'imdbId
        $repoMovie = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $repoMovie->findOneBy(['imdbId' => $imdbId]);

        //on crée le nouveau watchItem
        $item =  new WatchListItem($this->getUser(),$movie);

        //on l'enregistre
        $em = $this->getDoctrine()->getManager();
        $em->persist($item);
        $em->flush();

        $this->addFlash("success", "the movie has been added to our watchList");


        return $this->redirectToRoute("movie_detail", ["id" => $movie->getId()]);

    }



    public function listAction()
    {
        return $this->render('user/watchlist.html.twig');
    }


    // PARTIE POUR AJAX !!!

    public function postAddAction($imdbId)
    {
        if ($this->isGranted('ROLE_USER')) {

            // on va faire la requete pour voir si le movie est dans sa watchlist
            $repoMovie = $this->getDoctrine()->getRepository(Movie::class);

            $repoWatchItems = $this->getDoctrine()->getRepository(WatchListItem::class);

            $itemInList = $repoWatchItems->findOneBy([
                "movie" => $repoMovie->findOneBy(['imdbId' => $imdbId]),
                "user" => $this->getUser(),
            ]);


            //si le film n'est pas déjà dedans :
            if ($itemInList == null) {
                // on le crée
                $item = new WatchListItem($this->getUser(), $repoMovie->findOneBy(['imdbId' => $imdbId]));
                dump($item);
                $em = $this->getDoctrine()->getManager();
                $em->persist($item);
                $em->flush();

                return $this->json([
                    "status" => "ok",
                ]);
            } //sinon on revnoie already in
            else {
                // on le suppri

                return $this->json([
                    "status" => "alreadyInList",
                ]);
            }

        }

        return $this->json([
            "status"=>"error",
            "libelle"=> "Il faut vous connecter avant de voter !",
        ]);

    }

    public function postDeleteAction($imdbId)
    {
        $repoMovie = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $repoMovie->findOneBy(['imdbId' => $imdbId]);

        $repoWatchItems = $this->getDoctrine()->getRepository(WatchListItem::class);

        $itemInList = $repoWatchItems->findOneBy([
            "movie"=>$movie,
            "user" => $this->getUser(),
        ]);



        if($itemInList !== null) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($itemInList);
            $em->flush();

            return $this->json([
            "status"=>"ok",
            ]);
         } else
        {
            return $this->json([
                "status"=>"notInList",
        ]);
        }


    }












}
