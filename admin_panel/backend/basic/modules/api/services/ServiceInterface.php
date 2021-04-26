<?php

namespace app\modules\api\services;

interface ServiceInterface
{
    public function create($json_object);
    public function getAll();
    public function getById($id);
    public function deleteById($id);
    public function update($json_object);
}