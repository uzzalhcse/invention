<?php
namespace App\Traits;
trait Testable{
    public $items=[1,2,3];

    public function getItems(){
        return $this->items;
    }
}
