<?php

namespace App\Data;

use App\Entity\Provider;
use App\Entity\Service;
use App\Entity\Expertise;

class SearchExpertsData
{
    /**
     * @var string
     */
    public $q = '';

    /**
     * @var Provider[]
     */
    public $provider = [];

    /**
     * @var Expertise[]
     */
    public $expertise = [];
}
