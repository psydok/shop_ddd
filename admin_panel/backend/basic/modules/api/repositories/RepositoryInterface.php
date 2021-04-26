<?php
namespace app\modules\api\repositories;

interface RepositoryInterface
{
    public function insert($object): void;
    public function update($object): void;
    public function selectById($id);
    public function select();
    public static function getNewId();
}