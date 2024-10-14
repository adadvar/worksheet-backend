<?php

namespace App\Rules;

use App\Models\Advert;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CanChangeAdvertState implements ValidationRule
{
    private $advert;
 /**
     * Create a new rule instance.
     *
     * @param Advert $advert
     */
    public function __construct(Advert $advert = null)
    {
        $this->advert = $advert;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!(!empty($this->advert) &&
            (
                ($this->advert->state == Advert::STATE_PENDING && $value === Advert::STATE_BLOCKED) ||
                ($this->advert->state == Advert::STATE_PENDING && $value === Advert::STATE_ACCEPTED) ||
                ($this->advert->state == Advert::STATE_ACCEPTED && $value === Advert::STATE_BLOCKED) ||
                ($this->advert->state == Advert::STATE_BLOCKED && $value === Advert::STATE_ACCEPTED)
            ))){
                $fail('وضعیت اشتباه است.');
            }
    }
}
