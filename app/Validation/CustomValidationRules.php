<?php

namespace App\Validation;

use CodeIgniter\Shield\Validation\ValidationRules;

class CustomValidationRules extends ValidationRules
{
    public function getLoginRules(): array
    {
        return setting('Validation.login') ?? [
            'username' => $this->config->usernameValidationRules,
            // 'email'    => $this->config->emailValidationRules,
            'password' => $this->getPasswordRules(),
        ];
    }
}
