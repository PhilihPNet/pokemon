<?php
namespace pokemon\DAO;

use pokemon\Domain\Trainer;

class TrainerDAO extends DAO
{
    /**
     * Return a list of all trainers, sorted by date (most recent first).
     *
     * @return array A list of all trainers.
     */
    public function findAll() {
        $sql = "select * from trainer order by id desc";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result to an array of domain objects
        $trainers = array();
        foreach ($result as $row) {
            $trainerId = $row['id'];
            $trainers[$trainerId] = $this->buildDomainObject($row);
        }
        return $trainers;
    }

    /**
     * Returns an trainer matching the supplied id.
     *
     * @param integer $id The trainer id.
     *
     * @return \pokemon\Domain\trainer|throws an exception if no matching trainer is found
     */
    public function find($id) {
        $sql = "select * from trainer where id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No trainer matching id " . $id);
    }

    /**
     * Creates an trainer object based on a DB row.
     *
     * @param array $row The DB row containing Trainer data.
     * @return \pokemon\Domain\Trainer
     */

    protected function buildDomainObject(array $row) {
        $trainer = new Trainer();
        $trainer->setId($row['id']);
        $trainer->setName($row['name']);
        $trainer->setGender($row['gender']);
        return $trainer;
    }
}
