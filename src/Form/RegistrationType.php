<?php

namespace App\Form;

use App\Entity\User;
use App\Form\AddressType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'mot de passe incorrect',
                'required' => true,
                'first_options' =>['label' => 'mot de passe'],
                'second_options' =>['label' => 'Repeter le mot de passe'],
            ])
            ->add('phone', TelType::class, [
                'label' => 'Numero de telephone',
                'required' => true,
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prenom',
                'required' => false,
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom de Famille',
                'required' => false,
            ])
            ->add('address', AddressType::class)
            
            ->add('send', SubmitType::class, [
                'label' => 'Envoyer',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
