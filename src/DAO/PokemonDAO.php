<?php

namespace pokemon\DAO;

use pokemon\Domain\Pokemon;

class PokemonDAO extends DAO
{
    /**
     * @var \pokemon\DAO\TrainerDAO
     */
    private $trainerDAO;

    public function setTrainerDAO(TrainerDAO $trainerDAO) {
        $this->trainerDAO = $trainerDAO;
    }
    /**
     * Return a list of all pokemons, sorted by id (most recent first).
     *
     * @return array A list of all pokemons.
     */
    public function findAll() {
        $sql = "select * from pokemon order by id desc";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result to an array of domain objects
        $pokemons = array();
        foreach ($result as $row) {
            $pokemonId = $row['id'];
            $pokemons[$pokemonId] = $this->buildDomainObject($row);
        }
        return $pokemons;
    }
	
	
	
    /**
     * Return a list of all pokekom for an trainer.
     * @param integer $trainerId The trainer id.
     * @return array A list of all pokemons for the trainer.
     */
    public function findAllByTrainer($trainerId) {
        // The associated article is retrieved only once
        $trainer = $this->trainerDAO->find($trainerId);

        // Convert query result to an array of domain objects
        $pokemons = array();

        // id_trainer and  id_team are not selected by the SQL query
        // trainer & team won't be retrieved during domain objet construction
        $sql = "select * from trainer_to_pokemon where id_trainer=? order by id";
        $resultT2P = $this->getDb()->fetchAll($sql, array($trainerId));
        foreach ($resultT2P as $rowT2P) {

			$sql = "select * from pokemon where id=? order by id";
			$result = $this->getDb()->fetchAll($sql, array($rowT2P['id_pokemon']));
				foreach ($result as $row) {
					$Id = $row['id'];
					// trainer & id_team are defined for the constructed pokemon
					$row['id_trainer']=$trainerId;
					$row['id_team']=$rowT2P['id'];

					$pokemons[$Id] = $this->buildDomainObject($row);
				}

		}

        return $pokemons;
    }

    /**
     * Return a details of one pokemon.
     * @param integer $Id The pokemon id.
     * @return array A details of one pokemons.
     */
    public function find($id) {
        $sql = "select * from pokemon where id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No pokemon matching id " . $id);
    }
     /**
     * Return details of Pokemon Evolution.
     * @param void.
     * @return array A details of of Pokemon Evolution.
     */
   public function findEvolutions() {
        $sql = "select * from pokemon WHERE id_parent IS NULL order by id DESC";
        $pokemonsEnd = $this->getDb()->fetchAll($sql);
		$pokemons=[];
        foreach ($pokemonsEnd as $row){
				$pokemons[$row['id']] = $this->buildDomainObject($row);
				$sql = "select * from pokemon where id_parent=?";
				$continue=$row['id'];
				while($continue!==false){
					$pokemon = $this->getDb()->fetchAssoc($sql, array($continue));
					if($pokemon){
						$pokemons[$pokemon['id']]= $this->buildDomainObject($pokemon);
						$pokemons[$pokemon['id']]->setEvolution($pokemons[$continue]->json());
						unset($pokemons[$continue]);
						$continue=$pokemon['id'];
					}else{$continue=false;}
				}
			}
			return $pokemons;
	}
	
     /**
     * Return a details of one Pokemon of a team.
     * @param integer $teamId The Team id.
     * @return array A details of one Pokemon of a team.
     */
   public function findByTeam($teamId) {
        $sql = "select * from trainer_to_pokemon where id=?";
        $team = $this->getDb()->fetchAssoc($sql, array($teamId));
        //foreach ($resultT2P as $rowT2P) {

			$sql = "select * from pokemon where id=?";
			$pokemon = $this->getDb()->fetchAssoc($sql, array($team['id_pokemon']));
			return $this->buildDomainObject($pokemon);
	}

     /**
     * Return a upgraded details of one upgraded pokemon of a team.
     * @param integer $Id The Team id.
     * @param integer $Idpokemon The pokemon id.
     * @return array A details of one upgraded pokemon of a team.
     */
    public function upgrade($id) {
		
		$pokemon=$this->findByTeam($id);
		if ($pokemon){
				$level=$pokemon->getLevel()+1;
				$nextL=($pokemon->getLevel_Evolution());
				$up=$nextL?($level>=$nextL):false;
				$pId=$pokemon->getId_Parent();
				$idp=$pokemon->getId();
				$levelup = array(
					'id_pokemon' =>($up?$pId:$idp) ,
					'level' => $level
					);
        
				$this->getDb()->update('trainer_to_pokemon',$levelup,array('id_pokemon' => $idp));
				
				if($up){
					$saveAttack = array('id_pokemon' =>$pId);
					$this->getDb()->update('pokemon_to_attack',$saveAttack,array('id_pokemon' => $idp));
				}

			return $this->find($up?$pId:$idp)->json();//('{"level":"'.($level).'"}');

		}
    }
	
     /**
     * initilaise details of all initial pokemon of all team.
     * @param integer $Id The Team id.
     * @param integer $Idpokemon The pokemon id.
     * @return array A details of one initialised pokemon of a team.
     */
	public function reinitialise($idTeam) {
		$assocTP=array(1=>2,2=>5,3=>8);//les team/les pokemon de départ
		$assocAP=array(2=>2,5=>5,8=>8,3=>2,6=>5,9=>8,4=>2,7=>5,10=>8);//les parents/les pokemon de départ
		
		$sql = "select * from trainer_to_pokemon order by id ASC";
		$result = $this->getDb()->fetchAll($sql);
		foreach ($result as $row) {
				$reinit = array(
					'id_pokemon' =>$assocTP[$row['id_trainer']],
					'level' => 15
					);
				$this->getDb()->update('trainer_to_pokemon',$reinit,array('id' => $row['id']));
		}
		$sql = "select * from pokemon_to_attack order by id ASC";
		$result = $this->getDb()->fetchAll($sql);
		foreach ($result as $row) {
				$reinit = array(
					'id_pokemon' =>$assocAP[$row['id_pokemon']]
					);
            
			$this->getDb()->update('pokemon_to_attack',$reinit,array('id' => $row['id']));
		}
			return $this->findByTeam($idTeam)->json();
    }

    /**
     * Creates an Pokemon object based on a DB row.
     *
     * @param array $row The DB row containing Comment data.
     * @return \pokemon\Domain\Pokemon
     */
    protected function buildDomainObject(array $row) {
        $pokemon = new Pokemon();
        $pokemon->setId($row['id']);
        $pokemon->setName($row['name']);
		$pokemon->setImage("data:image/jpeg;base64,".base64_encode($row["image"]));
		$level=$this->getDb()->fetchAssoc("select * from trainer_to_pokemon where id_pokemon=?",Array($row['id']));
		if($level){
			$pokemon->setLevel($level['level']);
			$pokemon->setId_trainer($level['id_trainer']);
			$pokemon->setId_Team($level['id']);
		}

		$pokemon->setId_Type_1($row['id_type_1']);
		$types=$this->getDb()->fetchAssoc("select * from type where id=?",Array($row['id_type_1']));
		$pokemon->setType_1($types['name']);
		
		$pokemon->setId_Type_2($row['id_type_2']);
		$types=$this->getDb()->fetchAssoc("select * from type where id=?",Array($row['id_type_2']));
		if($types)$pokemon->setType_2($types['name']);

		$pokemon->setId_Parent($row['id_parent']);

		$parent=$this->getDb()->fetchAssoc("select * from pokemon where id=?",Array($row['id_parent']));
		if($parent)$pokemon->setParent($parent['name']);
		
		$pokemon->setLevel_Evolution($row['level_evolution']);

		$attack=$this->getDb()->fetchAssoc("select * from pokemon_to_attack where id_pokemon=?",Array($row['id']));
		if($attack){
			$pokemon->setId_Attack($attack['id_attack']);
			$pokemon->setLevel_Attack_Unlocked($attack['level_attack_unlocked']);
			
			$attackS=$this->getDb()->fetchAssoc("select * from attack where id=?",Array($attack['id_attack']));
			if($attackS){
				$pokemon->setAttack($attackS['name']);
				$pokemon->setDesc_Attack($attackS['desc']);
			}
		
		
		}
        if ($pokemon->getId_trainer()) {
            // Find and set the associated Pokemon
            $Id = $pokemon->getId_trainer();
            $trainer = $this->trainerDAO->find($Id);
            $pokemon->setTrainer($trainer);
        }
        return $pokemon;
    }
}