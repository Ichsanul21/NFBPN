<?php

namespace App\Policies;

class NewsPolicy extends ContentPolicy
{
    protected string $managePermission = 'news.manage';
}
