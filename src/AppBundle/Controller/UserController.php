<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Movie;
use AppBundle\Entity\User;
use AppBundle\Entity\WatchListItem;
use AppBundle\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends Controller
{

    public function registerAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        //on récupère des données de formulaire => donc on a request en paramètre
        // on crée un nouveau User
        $user =  new User();

        //on crée le formulaire en lassociant au user vide
        $registerForm = $this->createForm(RegisterType::class,$user);

        //on récupère les données entrée dans le formulaire et les injecte dans le user vide
        $registerForm->handleRequest($request);


        if($registerForm->isSubmitted()&&$registerForm->isValid())
        {
            //hash le mot de passe du $user
            $hash = $encoder->encodePassword($user,$user->getPassword());
            //remplace le mot de passe en clair par le hash
            //avant la sauvegarde en bdd
            $user->setPassword($hash);

            $user->setRoles("ROLE_USER");

            $user->setDateRegistered(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash("success","Welcome !");

            return $this->redirectToRoute("home");
        }

        //affiche la page register.html.twig
        return $this->render("user/register.html.twig",[
            //passe la view formulaire à la page register
            "registerForm"=>$registerForm->createView()
        ]);
    }


    public function watchitemAction($imdbId)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $repoMovie = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $repoMovie->findOneBy(['imdbId' => $imdbId]);

        $user = $this->getUser();

        $item =  new WatchListItem($user,$movie);

        $em = $this->getDoctrine()->getManager();
        $em->persist($item);
        $em->flush();

        $this->addFlash("success", "the movie has been added to our watchList");

        return $this->redirectToRoute("movie_detail", ["id" => $movie->getId()]);

    }

    public function watchlistAction()
    {
        return $this->render('user/watchlist.html.twig');
    }

    //page d'inscription (création du user)

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('user/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }
}
