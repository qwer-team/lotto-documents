<?php

namespace Qwer\LottoDocumentsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('documentTypeId')
            ->add('num')
            ->add('date')
            ->add('status')
            ->add('OA1')
            ->add('OA2')
            ->add('text1')
            ->add('text2')
            ->add('summa1')
            ->add('summa2')
            ->add('summa3')
            ->add('ucor')
            ->add('dtcor')
            ->add('externalUserId')
            ->add('documentType')
            ->add('currency')
            ->add('lotoClient')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Qwer\LottoDocumentsBundle\Entity\Bet'
        ));
    }

    public function getName()
    {
        return 'qwer_lottodocumentsbundle_bettype';
    }
}
