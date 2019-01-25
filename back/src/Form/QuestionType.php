<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;
use App\Entity\Category;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', TextType::class, ['label' => 'Libellé'])
            ->add('difficulty', ChoiceType::class, array(
              'choices' => array(
                'Facile' => 1,
                'Intermédiaire' => 2,
                'Difficile' => 3),
              'label' => 'Niveau de difficulté'
            ))
            ->add('category', EntityType::class, array(
              'class' => Category::class,
              'choice_label' => 'name',
              'label' => 'Catégorie',
              'query_builder' => function (EntityRepository $er){
                return $er->createQueryBuilder('c')->orderBy('c.name', 'ASC');
              }
            ))
            ->add('submit', SubmitType::class, array(
              'label' => 'Enregistrer'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
