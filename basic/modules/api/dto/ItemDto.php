<?php

namespace app\modules\api\dto;

class ItemDto
{
    /** @var int */
    public $id;
    /** @var string */
    public $name;
    /** @var int */
    public $category_id;
    /** @var double */
    public $price;
    /** @var string */
    public $img_link;
}