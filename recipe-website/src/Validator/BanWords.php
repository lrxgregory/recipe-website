<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class BanWords extends Constraint
{
    public function __construct(public string $message =  'The value "{{ banWord }}" is a ban word.', public array $banWords = [
        'connard',
        'putain',
        'merde',
        'salope',
        'enfoiré',
        'bâtard',
        'enculé',
        'bordel',
        'connasse',
        'pute',
        'salaud',
        'bite',
        'couille',
        'cul',
        'fuck',
        'shit',
        'asshole',
        'bitch',
        'bastard',
        'damn',
        'crap',
        'dick',
        'cunt',
        'pussy',
        'cock',
        'slut',
        'whore',
        'douche',
        'prick',
        'wanker',
        'twat',
        'piss',
        'bloody',
    ], ?array $groups = null, mixed $payload = null)
    {
        parent::__construct(null, $groups, $payload);
    }
}
