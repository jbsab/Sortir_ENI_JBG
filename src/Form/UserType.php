<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class,[
                "label" => 'Pseudo'
            ])
            ->add('password', TextType::class,[
                "label" => 'Mot de passe'
            ])
            ->add('lastName', TextType::class,[
                "label" => 'Nom'
            ])
            ->add('firstname', TextType::class, [
                "label" => 'Prenom'
            ])
            ->add('phoneNumber', TextType::class,[
                "label" => 'Numero de telephone'
            ])
            ->add('mail')
            ->add('profilePicture', TextType::class, [
                "label" => 'Photo de profil'
            ])
            ->add('isActive')
            ->add('isAdmin')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
