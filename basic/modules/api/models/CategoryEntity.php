<?php


namespace app\modules\api\models;


class CategoryEntity
{
    private $id;
    private $name;

    public function __construct()
    {
    }

    public static function withID($id)
    {
        $instance = new self();
        $instance->id = $id;
        return $instance;
    }

    public static function withProps($id, $name)
    {
        $instance = new self();
        $instance->id = $id;
        $instance->name = $name;
        return $instance;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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

    public function __toString()
    {
        $arr = [];
        $arr['id' ]= $this->id;
        $arr['name'] = $this->name;

        return (string) json_encode($arr);
    }
}