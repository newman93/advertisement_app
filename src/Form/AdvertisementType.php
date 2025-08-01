<?php

namespace App\Form;

use App\Entity\Advertisement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class AdvertisementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max'=>255])
                ]
            ])
            ->add('description', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max'=>1000])
                ]
            ])
            ->add('price', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Type('numeric'),
                    new Assert\GreaterThanOrEqual(0)
                ]
            ])
          ->add('images', FileType::class, [
            'label' => 'Images',
            'mapped' => false,
            'multiple' => true,
            'required' => false,
            'constraints' => [
                new Assert\Count(['max' => 5]),
                new Assert\All([
                    new Assert\File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                    ])
                ])
            ]
        ]);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Advertisement::class]);
    }
}
