<?php

class Link {
    private $id;
    private $name;
    private $url;


    public function __construct($id, $name, $url) {
        $this->id = $id;
        $this->name = $name;
        $this->url = $url;
        
    }

    public function getID(){
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }

    public function getURL(){
        return $this->url;
    }
}
?>