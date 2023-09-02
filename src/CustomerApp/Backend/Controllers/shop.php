<?php

namespace GoAroundCustomer\Controllers;

use Controller;
use Model;
use Storage;

class shop implements Controller
{
    protected Model $model;
    protected Storage $session;

    public function __construct(Model $model, Storage $session)
    {
        $this->model = $model;
        $this->session = $session;
        $this->session->set('test', mt_rand(1, 5));
    }

    public function run()
    {
        $articles = $this->model->all();
        foreach ($articles as $art) {
            echo $art['title'] . PHP_EOL;
        }

        $val = $this->session->get('test');
        echo $val;
    }
}