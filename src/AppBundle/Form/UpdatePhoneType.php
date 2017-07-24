<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdatePhoneType extends PhoneType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        // override this!
        $resolver->setDefaults(['is_edit' => true]);
    }

    public function getName()
    {
        return 'app_bundle_update_phone_type';
    }
}
