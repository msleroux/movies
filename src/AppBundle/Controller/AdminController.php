<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Critique;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
# use namespace du controller = nom complet
#

class AdminController extends Controller
{


    public function usersAction()
    {
        $repo = $this->getDoctrine()->getRepository(User::class);
        $users = $repo->findAll();

        return $this->render('admin/admin_users.html.twig', ["users" => $users]);
    }


    public function reviewsAction()
    {
        $repoCritique = $this->getDoctrine()->getRepository(Critique::class);
        $reviews = $repoCritique->findAll();
        dump($reviews);

        return $this->render('admin/admin_reviews.html.twig', ["reviews" => $reviews]);
    }



    public function seeReviewsAction($id)
    {
        $repo = $this->getDoctrine()->getRepository(User::class);
        $user = $repo->find($id);

       $reviews = $user->getCritiques();

        return $this->render('admin/admin_reviews.html.twig', [
            "reviews" => $reviews,
            "libelle" => $user->getUsername()]);

    }

    public function userDeleteAction($id)
    {
        // on récupère le repo des User
        $repoUser = $this->getDoctrine()->getRepository(User::class);
        // on récupère le user à supprimer via son $id
        $user = $repoUser->find($id);

        // on va "archiver" le user, car on veut garder ses reviews
        // on change son mail si jamais il veut se réinscrire plus tard
        // son nom
        // on lui donne un role type "archive" comme ça il n'a plus accès aux zones user
        $user->setUsername("Old reviewer");
        // en fait on ne peut pas changer le nom en old reviewer car username unique donc dès qu'on va vouloir
        // supprimer un deuxième user ça va bloquer contrainte d'intérité, idem pour
        $user->setEmail("old@mail.com");
        $user->setRoles('ROLE_ARCHIVE');

        //on enregistre la modif dans la base avec Doctrine
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->addFlash("success","Le compte a été supprimé");

        return $this->redirectToRoute('home');


    }
}

