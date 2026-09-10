<?php

namespace App\Policies;

class AgendaPolicy extends ContentPolicy
{
    protected string $managePermission = 'agenda.manage';
}
