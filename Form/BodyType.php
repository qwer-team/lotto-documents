<?php

namespace Qwer\LottoDocumentsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Qwer\LottoDocumentsBundle\Form\RawBetType;

class BodyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $lottoTimeOptions = array('class' => 'QwerLottoBundle:Time');
        $rawBetsOptions =  array(
            'type'         => new RawBetType(),
            'allow_add'    => true,
            'by_reference' => false,
        );
        
        $builder
            ->add('lottoTime', 'entity', $lottoTimeOptions)
            ->add('withBonus', 'checkbox')
            ->add('drawNum', 'integer')
            ->add('rawBets', 'collection', $rawBetsOptions )
            ->add('tokenStr')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Qwer\LottoDocumentsBundle\Entity\Request\Body'
        ));
    }
    
    public function getName()
    {
        return 'body';
    }
}