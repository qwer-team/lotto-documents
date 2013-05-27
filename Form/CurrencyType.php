<?php

namespace Qwer\LottoDocumentsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CurrencyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('rateToMain')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Qwer\LottoDocumentsBundle\Entity\Currency'
        ));
    }

    public function getName()
    {
        return 'qwer_lottodocumentsbundle_currencytype';
    }
}
