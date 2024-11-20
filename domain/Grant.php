<?php

class Grant {
    private $id;
    private $name;
    private $funder;
    private $open_date;
    private $due_date;
    private $description;
    private $completed;
    private $type;
    private $partners;
    private $amount;
    private $archived;

    public function __construct($id, $name, $funder, $open_date, $due_date, $description, $completed, $type, $partners, $amount, $archived){
        $this->id = $id;
        $this->name = $name;
        $this->funder = $funder;
        $this->open_date = $open_date;
        $this->due_date = $due_date;
        $this->description = $description;
        $this->completed = $completed;
        $this->type = $type;
        $this->partners = $partners;
        $this->amount = $amount;
        $this->archived = $archived;
    }

    public function getID() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getFunder() {
        return $this->funder;
    }

    public function getOpenDate() {
        return $this->open_date;
    }

    public function getDueDate() {
        return $this->due_date;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getCompleted() {
        return $this->completed;
    }

    public function getType() {
        return $this->type;
    }

    public function getPartners() {
        return $this->partners;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function getArchived() {
        return $this->archived;
    }

}
?>