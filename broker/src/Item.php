<?php


require_once __DIR__ . '/../vendor/autoload.php';

class Item extends \Sokil\Mongo\Structure
{
    public function getId() { return $this->get('id'); }
    public function getName() { return $this->get('name'); }
    public function getPrice() { return $this->get('price'); }
    public function getImgLink() { return $this->get('img_link'); }

    public function setName(string $name) { return $this->set('name', $name); }
    public function setPrice(float $price) { return $this->set('price', $price); }
    public function setImgLink(string  $imgLink) { return $this->set('img_link', $imgLink); }
}
