<?php

namespace app\modules\api\services;

interface ClientServiceInterface
{
    public function getAll();
    public function getById($id);
}