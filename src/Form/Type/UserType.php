<?php

namespace Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', array('label' => 'Pseudo'))
            ->add('password', 'repeated', array(
                'type'            => 'password',
                'invalid_message' => 'The password fields must match.',
                'options'         => array('required' => true),
                'first_options'   => array('label' => 'Password'),
                'second_options'  => array('label' => 'Repeat password'),
            ))
            ->add('role', 'choice', array(
                'choices' => array('ROLE_ADMIN' => 'Admin', 'ROLE_USER' => 'User')
            ))
             ->add('userprenom', 'text', array('label' => 'Prénom'))
             ->add('usernom', 'text', array('label' => 'Nom'))
             ->add('usermail', 'email', array('label' => 'Mail'));
    }

    public function getName()
    {
        return 'user';
    }
}
