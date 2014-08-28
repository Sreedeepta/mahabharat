<?php

namespace App\Controller;

use \Norm\Norm;

class RoutesController extends BaseController
{
    protected $collection;

    /**
     * [__construct description]
     *
     * @param [type] $app [description]
     * @param [type] $uri [description]
     */
    public function __construct($app, $uri)
    {
        parent::__construct($app, $uri);

        $this->collection = Norm::factory($this->clazz);
    }

    /**
     * [mapRoute description]
     *
     * @return [type] [description]
     */
    public function mapRoute()
    {
        parent::mapRoute();

        $this->map('/', 'search')->via('GET', 'POST');
        $this->map('/null/create', 'create')->via('GET', 'POST');
        $this->map('/:id', 'read')->via('GET');
        $this->map('/:id/update', 'update')->via('GET', 'POST');
        $this->map('/:id/delete', 'delete')->via('GET', 'POST');
    }
}