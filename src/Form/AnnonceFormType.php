<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\Categorie;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class AnnonceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre',TextType::class, [
                'constraints' =>[
                    new NotBlank(['message' => 'Veuillez indiquer un titre à votre annonce.']),
                    new Length([
                        'max' => 200,
                        'maxMessage' => 'Le nom ne peut contenir plus de {{ limit }} caractères.'
                    ])
                ],
                'required' =>false
            ])
            ->add('description',TextareaType::class,[
                'required' => false
            ])
            ->add('prix',MoneyType::class, [
                'constraints' =>[
                    new Positive(['message' => 'Le prix doit être positif']),
                    new NotBlank(['message' => 'Le prix est manquant'])
                ],
                'divisor' => 100,
                'required' =>false
            ])
            ->add('ville', TextType::class, [
                'constraints' =>[
                    new NotBlank(['message' => 'Veuillez indiquer une ville']),
                    new Length([
                        'max' => 200,
                        'maxMessage' => 'La ville ne peut contenir plus de {{ limit }} caractères.'
                    ])
                ],
                'required' =>false
            ])
            ->add('codePostal', TextType::class, [
                'constraints' =>[
                    new NotBlank(['message' => 'Veuillez indiquer un code postal.']),
                    new Length([
                        'max' => 10,
                        'maxMessage' => 'Le code postal ne peut contenir plus de {{ limit }} caractères.'
                    ])
                ],
                'required' =>false
            ])
            ->add('adresse', TextType::class, [
                'constraints' =>[
                    new NotBlank(['message' => 'Veuillez indiquer une adresse']),
                    new Length([
                        'max' => 200,
                        'maxMessage' => 'La adresse ne peut contenir plus de {{ limit }} caractères.'
                    ])
                ],
                'required' =>false
            ])
            ->add('categorie',EntityType::class, [
                // Classe de l'entité à afficher
                'class' => Categorie::class,
                // propriété à afficher dans la liste
                'choice_label' => 'nom'
            ])
            ->add('auteur',EntityType::class, [
                // Classe de l'entité à afficher
                'class' => User::class,
                // propriété à afficher dans la liste
                'choice_label' => function($auteur) {
                return $auteur->getPrenom() . ' ' . $auteur->getNom();
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
