<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\FiltersSorties;
use App\Repository\CampusRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltersSortiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus', EntityType::class, [
                'label' => 'Campus',
                'class' => Campus::class, 'choice_label' => 'nom',
                'required' => false,
                'placeholder' => 'Selectioner un campus',
                'query_builder' => function (CampusRepository  $campusRepository) {
                    return $campusRepository->createQueryBuilder('c')->orderBy('c.nom', 'ASC');
                },
            ])
            ->add('nomSortie', TextType::class, [
                'label' => 'Le nom de la sortie contient',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Search'
                )
            ])
            ->add('dateDebut', DateType::class, [
                'label' => 'Entre le',
                'required' => false,
            ])
            ->add('dateFin', DateType::class, [
                'label' => 'Et le',
                'required' => false,
            ])
            ->add('organisateur', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false,
            ])
            ->add('inscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit/e',
                'required' => false,
            ])
            ->add('pasInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false,
            ])
            ->add('passees', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FiltersSorties::class,
        ]);
    }
}
