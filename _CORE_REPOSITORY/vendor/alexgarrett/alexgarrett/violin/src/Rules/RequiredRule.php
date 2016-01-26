<?php

namespace Violin\Rules;

use Violin\Contracts\RuleContract;

class RequiredRule implements RuleContract
{
    public function run($value, $input, $args)
    {
        if (is_null($value)) {
            return false;
        } elseif (is_string($value) && trim($value) === '') {
            return false;
        } elseif ((is_array($value) || $value instanceof Countable) && count($value) < 1) {
            return false;
        }

        return true;
    }

    public function error()
    {
        return '{field} is required.';
    }

    public function canSkip()
    {
        return false;
    }
}
