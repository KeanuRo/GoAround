<?php

namespace GoAroundCustomer\Controllers;

use controller;
use GoAroundCustomer\Models\articles;
use Model;

class home implements controller
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function run()
    {
        $articles = $this->model->all();
        foreach ($articles as $art) {
            echo $art['title'] . PHP_EOL;
        }
    }
}