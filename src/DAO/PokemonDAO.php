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
     * Return a list of all comments for an article, sorted by date (most recent last).
     *
     * @param integer $articleId The article id.
     *
     * @return array A list of all comments for the article.
     */
    public function findAllByTrainer($trainerId) {
        // The associated article is retrieved only once
        $trainer = $this->trainerDAO->find($trainerId);

        // Convert query result to an array of domain objects
        $pokemons = array();

        // art_id is not selected by the SQL query
        // The article won't be retrieved during domain objet construction
        $sql = "select * from trainer_to_pokemon where id_trainer=? order by id";
        $resultT2P = $this->getDb()->fetchAll($sql, array($trainerId));
        foreach ($resultT2P as $rowT2P) {

			$sql = "select * from pokemon where id=? order by id";
			$result = $this->getDb()->fetchAll($sql, array($rowT2P['id_pokemon']));
				foreach ($result as $row) {
					$Id = $row['id'];
					$row['id_trainer']=$trainerId;
					//$row['image']=base64_encode($row['image']);
					$pokemon = $this->buildDomainObject($row);
					// The associated article is defined for the constructed comment
					//$pokemon->setTrainer($trainerId);
					$pokemons[$Id] = $pokemon;
				}

		}

        return $pokemons;
    }
    public function find($id) {
        $sql = "select * from pokemon where id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No pokemon matching id " . $id);
    }

    public function upgrade($id) {
		
		$pokemon=$this->find($id);
		if ($pokemon){
				$level=$pokemon->getLevel()+1;
				$nextL=($pokemon->getLevel_Evolution());
				$up=($level>=$nextL);
				$pId=$pokemon->getId_Parent();
				$id;
				$levelup = array(
        
					'id_pokemon' =>($up?$pId:$id) ,
        
					'level' => $level
					);
        
				$this->getDb()->update('trainer_to_pokemon',$levelup,array('id_pokemon' => $id));

				//	//return($upgrade?$upgrade->json():'{"level":"'.($level).'"}');
			return('{"level":"'.($level).'"}');

		}
    }

    /**
     * Creates an Comment object based on a DB row.
     *
     * @param array $row The DB row containing Comment data.
     * @return \pokemon\Domain\Comment
     */
    protected function buildDomainObject(array $row) {
        $pokemon = new Pokemon();
        $pokemon->setId($row['id']);
        $pokemon->setName($row['name']);
		$pokemon->setImage(base64_encode($row["image"]));
		
		$level=$this->getDb()->fetchAll("select * from trainer_to_pokemon where id_pokemon=?",Array($row['id']));
		if($level){
			$pokemon->setLevel($level[0]['level']);
			$pokemon->setId_trainer($level[0]['id_trainer']);
		}

		$pokemon->setId_Type_1($row['id_type_1']);
		$types=$this->getDb()->fetchAll("select * from type where id=?",Array($row['id_type_1']));
		$pokemon->setType_1($types[0]['name']);
		
		$pokemon->setId_Type_2($row['id_type_2']);
		$types=$this->getDb()->fetchAll("select * from type where id=?",Array($row['id_type_2']));
		if($types)$pokemon->setType_2($types[0]['name']);

		$pokemon->setId_Parent($row['id_parent']);

		$parent=$this->getDb()->fetchAll("select * from pokemon where id=?",Array($row['id_parent']));
		if($parent)$pokemon->setParent($parent[0]['name']);
		
		$pokemon->setLevel_Evolution($row['level_evolution']);

		$attack=$this->getDb()->fetchAll("select * from pokemon_to_attack where id_pokemon=?",Array($row['id']));
		if($attack){
			$pokemon->setId_Attack($attack[0]['id_attack']);
			$pokemon->setLevel_Attack_Unlocked($attack[0]['level_attack_unlocked']);
			
			$attackS=$this->getDb()->fetchAll("select * from attack where id=?",Array($attack[0]['id_attack']));
			if($attackS){
				$pokemon->setAttack($attackS[0]['name']);
				$pokemon->setDesc_Attack($attackS[0]['desc']);
			}
		
		
		}
        if ($pokemon->getId_trainer()) {
            // Find and set the associated article
            $Id = $pokemon->getId_trainer();
            $trainer = $this->trainerDAO->find($Id);
            $pokemon->setTrainer($trainer);
        }
        return $pokemon;
    }
}