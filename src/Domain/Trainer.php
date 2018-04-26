<?php

namespace pokemon\Domain;

class Trainer 
{
    /**
     * Trainer id.
     *
     * @var integer
     */
    private $id;

    /**
     * Trainer gender.
     *
     * @var string
     */
    private $gender;

    /**
     * Trainer name.
     *
     * @var string
     */
    private $name;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getGender() {
        return $this->gender;
    }

    public function setGender($gender) {
        $this->gender = $gender;
        return $this;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }
}
