<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\User;
use AppBundle\Validator\BothFieldsNull;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

const GET_METHOD = 'GET';

const FORM_LABEL = 'label';
const FORM_REQUIRED = 'required';
const FORM_CONSTRAINTS = 'constraints';
const FORM_CLASS = 'class';
const FORM_CHOICE_LABEL = 'choice_label';
const FORM_ATTRIBUTES = 'attr';

const FORM_TEXT_FIELD = 'text';
const FORM_USER_ID_FIELD = 'userId';
const FORM_SUBMIT_FIELD = 'submit';

const FORM_TWEET_LABEL = 'Tweet text';
const FORM_USER_LABEL = 'User screen name';
const FORM_USER_CHOICE_LABEL = 'screen_name';

const FORM_SUBMIT_LABEL = 'Search';
const FORM_SUBMIT_CLASS = 'btn btn-default';

const FORM_NAME = 'tweet';


/**
 * Defines form type
 *
 * Class TweetType
 * @package AppBundle\Form\Type
 */
class TweetType extends AbstractType
{
    /**
     * Adding form parameters to builder
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod(GET_METHOD)
            ->add(
                FORM_TEXT_FIELD,
                TextType::class,
                [
                    FORM_LABEL => FORM_TWEET_LABEL,
                    FORM_REQUIRED => false,
                    FORM_CONSTRAINTS => [new BothFieldsNull()]
                ]
            )
            ->add(
                FORM_USER_ID_FIELD,
                EntityType::class,
                [
                    FORM_CLASS => User::class,
                    FORM_LABEL => FORM_USER_LABEL,
                    FORM_CHOICE_LABEL => FORM_USER_CHOICE_LABEL,
                    FORM_REQUIRED => false,
                    FORM_CONSTRAINTS => [new BothFieldsNull()]
                ]
            )
            ->add(
                FORM_SUBMIT_FIELD,
                SubmitType::class,
                [
                    FORM_LABEL => FORM_SUBMIT_LABEL,
                    FORM_ATTRIBUTES => [
                        FORM_CLASS => FORM_SUBMIT_CLASS
                    ]
                ]
            );
    }

    /**
     * Defines form name
     *
     * @return string
     */
    public function getName()
    {
        return FORM_NAME;
    }
}
