<?php
namespace app\services;

interface ClientServiceInterface
{
    public function getItemsByIdCategory(int $id);
    public function getCatalogsCategories();
}