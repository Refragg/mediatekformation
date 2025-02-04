<?php

namespace App\Form;

use App\Entity\Playlist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Classe représentant le formulaire d'ajout / modification d'une playlist
 */
class PlaylistsType extends AbstractType
{
    /**
     * Construction du formulaire
     * @param FormBuilderInterface $builder Injecté par Symfony
     * @param array $options Injecté par Symfony
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'rows' => 4,
                ],
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Playlist::class,
        ]);
    }
}
