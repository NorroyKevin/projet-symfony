<?php

namespace App\Controller;

use App\DTO\Payment;
use App\Entity\Order;
use App\Entity\Address;
use App\Form\AddressType;
use App\Form\PaymentType;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('/order', name: 'app_order')]
    public function index(): Response
    {
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }

    #[Route('/ma-commande', name: 'app_order_display')]
    public function display(Request $request, OrderRepository $repository): Response
    {   

        //recuperer utilisateur 
        $user= $this->getUser();

        $payment = new Payment();

         //recuperer l'adresse
        $payment->address = $user->getAddress();
        
        //creer le formulaire payement
        $form = $this->createForm(PaymentType::class, $payment);
        

        $form->handleRequest($request);
       
        if($form->isSubmitted() && $form->isValid())
        {
            //créer la commande
            $order = new Order();
            //attacher la commande à l'utilisateur
            $order->setUser($user);
            $order->setAddress($payment->address);
            
            foreach($user->getBasket()->getArticle() as $article)
            {
                $order->addArticle($article);
                $user->getBasket()->removeArticle($article);
            }

             //save les donnees
            $repository->save($order, true);
            //redirection
            return $this->redirectToRoute('app_order_display');
        }

        
        return $this->render('order/display.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/ma-commande/validation', name: 'app_order_validate')]
    public function validate(Request $request): Response
    {
      
        
    }
}
