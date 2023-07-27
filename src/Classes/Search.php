<?php

namespace App\Classes;

use App\Entity\Categorie;

class Search
{
    /**
     * @var string
     */
    public $string = '';

    /**
     * @var Categorie[]
     */
    public $categories = [];

    public function __toString(): string
    {
        return $this->string;
    }
}
