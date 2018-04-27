<?php

namespace pokemon\Domain;

class Pokemon 
{
    /**
     * pokemon id.
     * @var integer
     */
    private $id;

    /**
     * pokemon name.
     * @var string
     */
    private $name;

    /**
     * pokemon id_type_1.
     * @var integer
     */
    private $id_type_1;
    /**
     * pokemon type_1.
     * @var string
     */
    private $type_1;

    /**
     * pokemon level.
     * @var integer
     */
    private $level;

     /** pokemon id_type_2.
     * @var integer
     */
    private $id_type_2;
     /** pokemon id_parent.
     * @var integer
     */
    private $id_parent;
     /** pokemon parent.
     * @var integer
     */
    private $parent;
     /** pokemon level_evolution.
     * @var integer
     */
    private $level_evolution;
     /** pokemon evolution.
     * Associated pokemon.
     * @var \pokemon\Domain\pokemon
	*/
    private $evolution;
     /** pokemon image.
     * @var blob
     */
    private $image;
     /** pokemon id attack.
     * @var integer
     */
    private $id_attack;
     /** pokemon level_attack_unlocked.
     * @var integer
     */
    private $attack_unlocked;
     /** pokemon attack.
     * @var integer
     */
    private $attack;
     /** pokemon attack.
     * @var integer
     */
    private $desc_attack;
    /**
     * pokemon id_team.
     * @var integer
     */
    private $id_team;
    /**
     * pokemon json.
     * @var integer
     */
    private $json;

    /**
     * pokemon id trainer.
     * @var \pokemon\Domain\trainer
     */
    private $id_trainer;
    /**
     * Associated trainer.
     * @var \pokemon\Domain\trainer
     */
    private $trainer;
	///////////////////:
	function json() {
		$json="";
     foreach ($this as $key => $value) {
         if(($key!="trainer")&&($key!="json")&&($key!="evolution"))
				$json.= ($json ==''?'':',').'"'.$key.'":"'.$value.'"';
		 if(($key=="evolution")){$json.= ($json ==''?'':',').'"'.$key.'":'.($value?$value:'false').'';}
	
     }
	 return $this->json='{'.$json.'}';
  }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getId_Type_1() {
        return $this->id_type_1;
    }

    public function setId_Type_1($id_type_1) {
        $this->id_type_1 = $id_type_1;
        return $this;
    }
    public function getType_1() {
        return $this->type_1;
    }

    public function setType_1($type_1) {
        $this->type_1 = $type_1;
        return $this;
    }

    public function getId_Type_2() {
        return $this->id_type_2;
    }

    public function setId_Type_2($id_type_2) {
        $this->id_type_2 = $id_type_2;
        return $this;
    }

    public function getType_2() {
        return $this->type_2;
    }

    public function setType_2($type_2) {
        $this->type_2 = $type_2;
        return $this;
    }
   public function getId_Trainer() {
        return $this->id_trainer;
    }

    public function setId_Trainer($id_trainer) {
        $this->id_trainer = $id_trainer;
        return $this;
    }

    public function getTrainer() {
        return $this->trainer;
    }

    public function setTrainer(Trainer $trainer) {
        $this->trainer = $trainer;
        return $this;
    }
    public function getImage() {
        return $this->image;
    }

    public function setImage( $image) {
        $this->image = $image;
        return $this;
    }
	

    public function getId_Parent() {
        return $this->id_parent;
    }

    public function setId_Parent($id_parent) {
        $this->id_parent=$id_parent;
        return $this;
    }
    public function getParent() {
        return $this->parent;
    }

    public function setParent($parent) {
        $this->parent=$parent;
        return $this;
    }

    public function getLevel_Evolution() {
        return $this->level_evolution;
    }

    public function setLevel_Evolution($level_evolution) {
        $this->level_evolution=$level_evolution;
        return $this;
    }

    public function getEvolution() {
        return $this->evolution;
    }

    public function setEvolution($evolution) {
        $this->evolution=$evolution;
        return $this;
    }

    public function getId_Attack() {
        return $this->id_attack;
    }

    public function setId_Attack($id_attack) {
        $this->id_attack=$id_attack;
        return $this;
    }
	
    public function getLevel_Attack_Unlocked() {
        return $this->level_attack_unlocked;
    }
    public function getAttack() {
        return $this->attack;
    }

    public function setAttack($attack) {
        $this->attack=$attack;
        return $this;
    }
    public function getDesc_Attack() {
        return $this->desc_attack;
    }

    public function setDesc_Attack($desc_attack) {
        $this->desc_attack=$desc_attack;
        return $this;
    }
	
    public function setLevel_Attack_Unlocked($level_attack_unlocked) {
        $this->level_attack_unlocked=$level_attack_unlocked;
        return $this;
    }
    public function getLevel() {
        return $this->level;
    }
	
    public function setLevel($level) {
        $this->level=$level;
        return $this;
    }
    public function getId_Team() {
        return $this->id_team;
    }

    public function setId_Team($id_team) {
        $this->id_team = $id_team;
        return $this;
    }

}
