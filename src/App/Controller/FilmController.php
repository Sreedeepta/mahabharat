<?php

namespace App\Controller;

use \Norm\Norm;
use \Bono\Helper\URL;

class FilmController extends BaseController
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

    public function create()
    {
        $entry = $this->getCriteria();

        if ($this->request->isPost()) {
            try {
                $entry = array_merge($entry, $this->request->post());
                // var_dump($entry);
                // exit;
                $model = $this->collection->newInstance();
                $result = $model->set($entry)->save();

                $entry = $model;

                h('notification.info', $this->clazz.' created.');

                h('controller.create.success', array(
                    'model' => $model
                ));

            } catch (\Slim\Exception\Stop $e) {
                throw $e;
            } catch (\Exception $e) {

                h('notification.error', $e);

                h('controller.create.error', array(
                    'model' => $model,
                    'error' => $e,
                ));

                // $this->flashNow('error', $e);
            }
            $this->redirect(URL::site('/film'));

        }

        $this->data['entry'] = $entry;
    }
}