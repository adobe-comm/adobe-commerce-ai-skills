<?php
declare(strict_types=1);

namespace Acme\CustomerValidate\Model;

use Magento\Framework\Validator\EmailAddress as EmailAddressValidator;

class Validator
{
    public function __construct(
        private readonly EmailAddressValidator $emailAddressValidator,
    ) {
    }

    public function isValidEmail(string $email): bool
    {
        $email = trim($email);
        if ($email === '') {
            return false;
        }

        return $this->emailAddressValidator->isValid($email);
    }
}
