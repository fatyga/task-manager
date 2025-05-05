<?php

namespace App\Form\Type;

use App\Entity\Task;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('title', TextType::class)
            ->add('deadline', DateType::class)
            ->add('save', SubmitType::class);
    }

    public static function loadVAlidatorMetadata(ClassMetadata $metadata): void{
        $metadata->addPropertyConstraint('title', new NotBlank());

        $metadata->addPropertyConstraint('deadline', new NotBlank());
        $metadata->addPropertyConstraint('deadline', new Type(\DateTimeInterface::class));
    }

    public function configureOptions(OptionsResolver $resolver): void{
        $resolver->setDefaults([
            'data_class' => Task::class
        ]);
    }
}