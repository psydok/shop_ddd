<?php

namespace app\modules\api\services;

interface ServiceInterface
{
    public function create($dto);
    public function getAll();
    public function getById($id);
    public function deleteById($id);
    public function update($dto);
}