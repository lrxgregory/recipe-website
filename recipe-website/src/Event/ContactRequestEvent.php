<?php

namespace App\Event;

use App\DTO\ContactDTO;

class ContactRequestEvent
{
    public function __construct(readonly public ContactDTO $data){

    }
}