<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomSortie', TextType::class, [
                'label' => 'Nom de la sortie'
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Date et heure de la sortie',
                'date_widget' => 'single_text'
            ])
            ->add('dateLimiteInscription', DateType::class, [
                'label' => 'Date limite d\'inscription',
                'widget' => 'single_text',
            ])
            ->add('duree', NumberType::class, [
                'label' => 'Durée'
            ])

            ->add('nbInscriptionsMax', NumberType::class, [
                'label' => 'Nombre de places'
            ])
            ->add('infosSortie', TextareaType::class, [
                'label' => 'Descriptions et infos'
            ])
            ->add('etat', EntityType::class,[
                'class' => Etat::class,
                'label' => 'Etat (selectionner)',
                'placeholder' => '---Selectionner---',
                'choice_label' => 'libelle'
                ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'label' => 'Choisir le campus organisateur',
                'placeholder' => '---Selectionner---',
                'choice_label' => 'nom'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
