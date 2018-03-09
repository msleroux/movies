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
}

