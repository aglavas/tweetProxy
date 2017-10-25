<?php

namespace AppBundle\Validator;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

const TWEET_CONST = 'tweet';
const TEXT_CONST = 'text';
const USER_ID_CONST = 'userId';


/**
 * Validator extension logic
 *
 * Class BothFieldsNullValidator
 * @package AppBundle\Validator
 */
class BothFieldsNullValidator extends ConstraintValidator
{
    private $em;
    private $request;

    public function __construct(EntityManager $em, RequestStack $request)
    {
        $this->em = $em;
        $this->request = $request;
    }

    /**
     * Checks if the passed value is valid.
     * It is forbidden for both values to be null.
     *
     * @param mixed      $value      The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     *
     * @return bool
     */
    public function validate($value, Constraint $constraint)
    {
        $parameters = $this->request->getCurrentRequest()->query->all();

        if ((empty($parameters[TWEET_CONST][TEXT_CONST]) || is_null($parameters[TWEET_CONST][TEXT_CONST])) && (empty($parameters[TWEET_CONST][USER_ID_CONST]) || is_null($parameters[TWEET_CONST][USER_ID_CONST]))) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
            return false;
        }

        return true;
    }
}
