<?php

    class Field{
        private $id;
        private $name;
        private $data;


        public function __construct($id, $name, $data){
            $this->id = $id;
            $this->name = $name;
            $this->data = $data;
        }

        public function getID(){
            return $this->id;
        }

        public function getName(){
            return $this->name;
        }

        public function getData(){
            return $this->data;
        }
    }
