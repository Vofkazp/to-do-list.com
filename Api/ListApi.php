<?php

class ListApi extends Api
{
    public $apiName = 'list';

    public function indexAction()
    {
        $list = ListModel::getList();

        if ($list) {
            return $this->response($list, 200);
        }
        return $this->response('Data not found', 404);
    }

    public function viewAction()
    {
        $id = array_shift($this->requestUri);

        if ($id) {
            $list = ListModel::getById($id);
            if ($list) {
                return $this->response($list, 200);
            }
        }
        return $this->response('Data not found', 404);
    }

    public function createAction()
    {
        $formData = json_decode(file_get_contents('php://input'), true);
        $list['name'] = $formData['name'];
        $list['description'] = $formData['description'];
        $list['visibility'] = $formData['visibility'];
        if (!empty($list)) {
            $id = ListModel::create($list);
            if ($id !== 0) {
                return $this->response($id, 200);
            }
        }
        return $this->response("Saving error", 500);
    }

    public function updateAction()
    {
        $formData = json_decode(file_get_contents('php://input'), true);
        $list['id'] = $formData['id'];
        $list['name'] = $formData['name'];
        $list['description'] = $formData['description'];
        $list['visibility'] = $formData['visibility'];

        if (!empty($list)) {
            $list = ListModel::edit($list);
            if ($list) {
                return $this->response('Data updated', 200);
            } else {
                return $this->response("Update error", 400);
            }
        } else {
            return $this->response("Update error", 400);
        }
    }

    public function deleteAction()
    {
        $id = array_shift($this->requestUri);

        if (!$id && !ListModel::getById($id)) {
            return $this->response("Item with id=$id not found", 404);
        } elseif (ListModel::deleteById($id)) {
            return $this->response('Data deleted.', 200);
        } else {
            return $this->response("Delete error", 500);
        }
    }

    public function statusAction()
    {
        $id = array_shift($this->requestUri);

        $formData = json_decode(file_get_contents('php://input'), true);
        $list['id'] = $id;
        $list['visibility'] = $formData['visibility'];

        if ($list) {
            ListModel::editStaus($list);
            return $this->response('Data updated.', 200);
        } else {
            return $this->response("Updated error", 500);
        }
    }
}
