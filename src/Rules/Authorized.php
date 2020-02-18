<?php

declare(strict_types=1);

namespace Php\Support\Laravel\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

/**
 * Class Authorized
 * @package Php\Support\Laravel\Rules
 */
class Authorized implements Rule
{
    /** @var string */
    protected $ability;

    /** @var array */
    protected $arguments;

    /** @var string */
    protected $className;

    /** @var string */
    protected $attribute;

    public function __construct(string $ability, string $className)
    {
        $this->ability = $ability;

        $this->className = $className;
    }

    public function passes($attribute, $value): bool
    {
        $this->attribute = $attribute;

        if (!$user = Auth::user()) {
            return false;
        }

        if (!$model = $this->className::find($value)) {
            return false;
        }

        return $user->can($this->ability, $model);
    }

    public function message(): string
    {
        $classBasename = class_basename($this->className);

        return __(
            'laravelSupport::messages.authorized',
            [
                'attribute' => $this->attribute,
                'ability'   => $this->ability,
                'className' => $classBasename,
            ]
        );
    }
}
