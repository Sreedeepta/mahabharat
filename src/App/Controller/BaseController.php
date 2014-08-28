<?php

namespace App\Controller;

use \Norm\Controller\NormController;

/**
 * Base Controller
 */
class BaseController extends NormController
{
    /**
     * Get the criteria from the request
     *
     * @return array criteria that will be used to get the record(s)
     */
    public function getCriteria()
    {
        $criteria = parent::getCriteria();
        $criteria = array_merge(array('status' => 1), $criteria);

        return $criteria;
    }

    /**
     * Trashed a single record
     *
     * @param mixed $id The identifier of the Model
     *
     * @return Response
     */
    public function delete($id)
    {
        if ($this->request->isPost() || $this->request->isDelete()) {
            $id = explode(',', $id);

            foreach ($id as $value) {
                $model = $this->collection->findOne($value);
                $model->set('status', 0);
                $model->save();
            }

            $this->flash('info', $this->clazz.' trashed.');
            $this->redirect($this->getRedirectUri());
        }

        $this->data['ids'] = $id;
    }

    /**
     * Restore a single record
     *
     * @param string $id The identifier of the Model
     *
     * @return Response
     */
    public function restore($id)
    {
        if ($this->request->isPost() || $this->request->isDelete()) {
            if ($this->request->isPost() || $this->request->isDelete()) {
                $id = explode(',', $id);

                foreach ($id as $value) {
                    $model = $this->collection->findOne($value);
                    $model->set('status', 1);
                    $model->save();
                }

                $this->flash('info', $this->clazz.' restore.');
                $this->redirect($this->getRedirectUri());
            }
        }
    }
}
