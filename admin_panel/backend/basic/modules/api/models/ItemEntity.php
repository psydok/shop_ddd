<?php


namespace app\modules\api\models;


class ItemEntity
{
    private $id;
    private $name;
    private $category_id;
    private $price;
    private $img_link;

    public function __construct()
    {
    }

    public static function withFullProps($id, CategoryEntity $category_id, $name, $price, $img_link)
    {
        $instance = new self();
        $instance->loadProperties($id, $category_id, $name, $price, $img_link);
        return $instance;
    }

    public static function withID($id)
    {
        $instance = new self();
        $instance->id = $id;
        return $instance;
    }

    protected function loadProperties($id, CategoryEntity $category_id, $name, $price, $img_link)
    {
        $this->id = $id;
        $this->name = $name;
        $this->category_id = $category_id;
        $this->price = $price;
        $this->img_link = $img_link;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return CategoryEntity
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @param CategoryEntity $category_id
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed|null
     */
    public function getImgLink()
    {
        return $this->img_link;
    }

    /**
     * @param mixed|null $img_link
     */
    public function setImgLink($img_link)
    {
        $this->img_link = $img_link;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}