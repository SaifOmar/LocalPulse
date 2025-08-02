<?php

namespace App\Rules;

use App\Helpers\Helpers;
use App\Models\Account;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueHandle implements ValidationRule
{
    public function __construct(private ?int $ignoreId = null) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $handle = Helpers::createHandle($value);

        $query = Account::where('handle', $handle);

        if ($this->ignoreId) {
            $query->where('id', '!=', $this->ignoreId);
        }

        if ($query->exists()) {
            $fail("The $attribute is already taken.");
        }
    }
}
