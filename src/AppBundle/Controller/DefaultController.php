<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Genre;
use AppBundle\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
    public function homeAction(Request $request)
    {

        $repo = $this->getDoctrine()->getRepository(Movie::class);
        $movies = $repo->findBy([],["id"=>"ASC"],50,0);

        // FILTRES
        $defaultData = array(); //données préselectionnnées au premier chargement
        // ici on n'en veut pas -> donc array vide

        $formFilter = $this->createFormBuilder($defaultData)
            ->add('genre', EntityType::class,[
                'label'=>'Genre',
                'class'=>Genre::class,
                'choice_label'=>'name',
                'placeholder' => 'Choose an option',
                    ])
            ->add('anneeMin', IntegerType::class)

            ->add('anneeMax', IntegerType::class)
            ->add('recherche', TextType::class)
            ->add('send', SubmitType::class)
            ->getForm();

        $formFilter->handleRequest($request);

        if ($formFilter->isSubmitted() && $formFilter->isValid())
        {
            // filters is an array with "category", "anneMin", "anneeMax", recherche
            dump($formFilter);

            $selectedGenre = $formFilter->get('genre')->getData();
            $selectedAnneeMin = $formFilter->get('anneeMin')->getData();
            $selectedAnneeMax = $formFilter->get('anneeMax')->getData();
            $recherche = $formFilter->get('recherche')->getData();

            $movies = $repo->findWithFilters($selectedGenre,$selectedAnneeMin,$selectedAnneeMax,$recherche);
            dump($movies);
        }

        //dump($movies);
        //die();

        return $this->render('default/home.html.twig', [
            "movies"=>$movies,
            "formFilter"=>$formFilter->createView(),

        ]);
    }


}
