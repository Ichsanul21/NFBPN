<?php

namespace App\Policies;

class GalleryPolicy extends ContentPolicy
{
    protected string $managePermission = 'gallery.manage';
}
