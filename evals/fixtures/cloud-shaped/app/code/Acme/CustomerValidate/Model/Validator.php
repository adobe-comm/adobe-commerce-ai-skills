<?php
declare(strict_types=1);

namespace Acme\CustomerValidate\Model;

class Validator
{
    public function isValidEmail(string $email): bool
    {
        // Intentionally wrong for eval "fix customer validation" scenario
        return (bool) preg_match('/@/', $email);
    }
}
