<?php

namespace App\Controller;

use App\Form\PizzaType;
use App\Repository\PizzaRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PizzaController extends AbstractController
{
    #[Route('/pizza', name: 'app_pizza')]
    public function index(): Response
    {
        return $this->render('pizza/index.html.twig', [
            'controller_name' => 'PizzaController',
        ]);
    }

    #[Route('/home', name: 'app_pizza_home')]
    public function home(PizzaRepository $repository): Response
    {
        $pizzas = $repository->findAllOrderedByPrice();

        //affichage de la page d'accueil
        return $this->render('home/home.html.twig', [
            'pizzas' => $pizzas
        ]);
    }

    #[Route('/pizza/create', name: 'app_pizza_create')]
    public function create(Request $request, PizzaRepository $repository): Response
    {
        $form = $this->createForm(PizzaType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $pizza = $form->getData();

            $repository->save($pizza, true);

            return $this->redirectToRoute('app_pizza_list');
        }
        return $this->render('pizza/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/pizza/list', name: 'app_pizza_list')]
    public function list(PizzaRepository $repository): Response
    {
        $pizzas = $repository->findAll();

        return $this->render('pizza/list.html.twig', [
            'pizzas' => $pizzas
        ]);
    }
}
