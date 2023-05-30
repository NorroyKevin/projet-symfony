<?php

namespace App\Controller;

use App\Repository\PizzaRepository;
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

    #[Route('/pizza/home', name: 'app_pizza_home')]
    public function home(PizzaRepository $repository): Response
    {
        $pizzas = $repository->findAllOrderedByPrice();

        //affichage de la page d'accueil
        return $this->render('home/home.html.twig', [
            'pizzas' => $pizzas
        ]);
    }
}
