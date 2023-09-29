<?php 
namespace Pop\Model;

use Pop\Database\DatabaseConnection;

abstract class AbstractModel extends DatabaseConnection
{
    public function find(): array
    {
        // SQL String
        $sql = "SELECT * FROM `$this->table`";

        // Query execution
        $query = $this->stmt->query( $sql );

        // Fetch all result
        return $query->fetchAll(\PDO::FETCH_OBJ);
    }

    public function findOne( $id)
    {
        // SQL String
        $sql = "SELECT * FROM `$this->table` WHERE `id`=:id";

        // Query preparation
        $query = $this->stmt->prepare( $sql );
        $query->bindValue(':id', $id, \PDO::PARAM_INT);

        // Query execution
        $query->execute();

        // Fetch all result
        return $query->fetch(\PDO::FETCH_OBJ);
    }

    public function findBy(array $criteria=[], array $orderBy=[], array $limit=[])
    {
    }

    public function add(array $data=[])
    {
        $keys   = array_keys($data);
        $cols   = "`".implode("`, `", $keys)."`";
        $params = ":".implode(",:", $keys);

        // SQL String
        $sql = "INSERT INTO `$this->table` ($cols) VALUES ($params)";

        // Query preparation
        $query = $this->stmt->prepare($sql);
        foreach ($data as $key => $value)
        {
            $query->bindValue(":$key", $value);
        }

        // Query execution
        $query->execute();

        // Get the last insert ID
        return $this->stmt->lastInsertId();
    }

    public function update(int $id, array $data=[]): int|false
    {
        $cols = '';
        foreach (array_keys($data) as $key)
        {
            $cols.= empty($cols) ? null : ", ";
            $cols.= "`$key`=:$key";
        }

        // SQL String
        $sql = "UPDATE `$this->table` SET $cols WHERE `id`=:id";

        // Query preparation
        $query = $this->stmt->prepare($sql);
        $query->bindValue(":id", $id);

        foreach ($data as $key => $value)
        {
            $query->bindValue(":$key", $value);
        }

        // Query execution
        return $query->execute() ? $id : false;
    }

    public function remove(int $id)
    {
        $sql = "DELETE FROM `$this->table` WHERE `id`=:id";

        // Query preparation
        $query = $this->stmt->prepare( $sql );
        $query->bindValue(':id', $id, \PDO::PARAM_INT);

        // Query execution
        return $query->execute();
    }  

    /**
     * Shortcut to add() or update()
     *
     * @param object $data
     * @param integer|null $id
     * @return integer|false
     */
    public function save(object $data, ?int $id=null): int|false
    {
        $data = json_decode(json_encode($data), true);

        return $id !== null 
            ? $this->update($id, $data)
            : $this->add($data)
        ;
    }
}
