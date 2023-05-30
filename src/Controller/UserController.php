<?php

namespace App\Controller;

use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    #[Route('/user/registration', name: 'app_user_registration')]
    public function registration(Request $request, UserRepository $repository): Response
    {
        //creation du formulaire
        $form = $this->createForm(RegistrationType::class);

        //remplir le formulaire avec les données utilisateurs
        $form->handleRequest($request);

        //tester le formulaire
        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

            $repository->save($user, true);

            //redirection
            return $this->redirectToRoute('app_pizza_home');

        }

        //affichage de la page du formulaire
        return $this->render('user/userRegistration.html.twig', [
            'form' =>$form
        ]);
    }
}
