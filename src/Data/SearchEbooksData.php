<?php

namespace App\Data;

use App\Entity\Expertise;
use App\Entity\Ebook;

class SearchEbooksData
{
    /**
     * @var string
     */
    public $q = '';

    /**
     * @var Expertise[]
     */
    public $expertise = [];

    /**
     * @var Ebook[]
     */
    public $ebook = [];
}
