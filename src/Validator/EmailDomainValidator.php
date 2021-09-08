<?php

namespace App\Validator;

use App\Repository\ConfigRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EmailDomainValidator extends ConstraintValidator
{   
    private $configRepository;
    private $globalBlocked = [];

    public function __construct(ConfigRepository $configRepository, $globalBlockedDomains = '') 
    {
        $this->globalBlocked = explode(',', $globalBlockedDomains); 
        $this->configRepository = $configRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\EmailDomain */

        if (null === $value || '' === $value) {
            return;
        }

        $domain = substr($value, strpos($value, '@') + 1);
        $blockedDomain = array_merge($constraint->blocked, $this->configRepository->getAsArray('blocked_domains'), $this->globalBlocked);
        if (in_array($domain, $blockedDomain)) {
            // TODO: implement the validation here
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
