<?php

namespace Qwer\LottoDocumentsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RawBetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $ballsOptions = array('type' => 'integer');
        $betTypeOptions = array('class' => 'QwerLottoBundle:BetType');
        $summaOptions = array('currency' => 'RUR');
        $builder
            ->add('balls', 'collection', $ballsOptions)
            ->add('betType', 'entity', $betTypeOptions)
            ->add('summa', 'money', $summaOptions)
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Qwer\LottoDocumentsBundle\Entity\Request\RawBet'
        ));
    }
    
    public function getName()
    {
        return 'qwer_lottodocumentsbundle_request_rawbettype';
    }
}