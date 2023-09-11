<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nickname', TextType::class, [
                "label"=>'Pseudo'
            ])
            ->add('mail', EmailType::class, [
                "label"=>'Email'
            ])
            ->add('password', PasswordType::class, [
                "label"=>'Mot de passe'
            ])
            ->add('lastName', TextType::class, [
                "label"=>'Nom'
            ])
            ->add('firstName', TextType::class, [
                "label"=>'Prenom'
            ])
            ->add('phoneNumber', TextType::class, [
                "label"=>'Telephone'
            ])
            ->add('isAdmin')
            ->add('isActive')
       ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
