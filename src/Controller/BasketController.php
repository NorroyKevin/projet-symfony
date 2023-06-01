<?php

namespace App\Controller;

use App\Entity\Pizza;
use App\Entity\Article;
use App\Repository\BasketRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BasketController extends AbstractController
{
    #[Route('/basket', name: 'app_basket')]
    public function index(): Response
    {
        return $this->render('basket/index.html.twig', [
            'controller_name' => 'BasketController',
        ]);
    }

    #[Route('/mon-panier', name: 'app_basket_display')]
    public function display(): Response
    {
        return $this->render('basket/display.html.twig');
    }

    #[Route('/mon-panier/{id}/ajouter', name: 'app_basket_add')]
    public function add(Pizza $pizza, BasketRepository $repository): Response
    {
        $user = $this->getUser();

        $basket = $user->getBasket();

        $article = new Article();
        
        $article->setQuantity(1);

        $article->setBasket($basket);

        $article->setPizza($pizza);
        

        $basket->addArticle($article);

        $repository->save($basket, true);

        return $this->redirectToRoute('app_pizza_home');
    }

    #[Route('/mon-panier/{id}/plus', name: 'app_basket_plus')]
    public function plus(Article $article, ArticleRepository $repository): Response
    {
        $quantite = $article->getQuantity();
        $article->setQuantity($quantite+1);

        $repository->save($article, true);

        return $this->redirectToRoute('app_basket_display');
    }

    #[Route('/mon-panier/{id}/moins', name: 'app_basket_minus')]
    public function minus(Article $article , ArticleRepository $repository): Response
    {
        //mettre la quantité à -1
        $qt = $article->getQuantity();
        $article->setQuantity($qt-1);

        if($article->getQuantity() <= 0)
        {
           //recuperer l user
           $user = $this->getUser();
           $basket = $user->getBasket();
           
           //supprimer l article du basket
           $basket->removeArticle($article);

           //maj du panier
           $basketRepository->save($basket, true);

           //redirection
           return $this->redirectToRoute('app_basket_display');
        }
        //save en BDD
        $repository->save($article, true);

        //redirection
        return $this->redirectToRoute('app_basket_display');

    }

    #[Route('/mon-panier/{id}/supprimer', name: 'app_basket_remove')]
    public function remove(Article $article, BasketRepository $repository): Response
    {
       //recup l'utilisateur
       $user = $this->getUser();
       
       //recup le panier de l'utilisateur
       $basket = $user->getBasket();
       //supprimer le livre du panier
       $basket->removeArticle($article);

       //enregistrer les modification dans la bd
       $repository->save($basket, true);

       //redirection vers la page d'affichage du panier
       return $this->redirectToRoute('app_basket_display'); 
    }
}
