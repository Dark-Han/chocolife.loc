<?php

namespace Core;

use PDO;

class Model
{

    private $db;

    public function __construct()
    {
        $config = require $_SERVER['DOCUMENT_ROOT'] . '/Config/Db.php';
        $this->db = new PDO(
            'mysql:host=' . $config['host'] . ';dbname=' . $config['name'],
            $config['user'],
            $config['pass'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    protected function query($sql, $params = [])
    {
        try {
            $stmt = $this->db->prepare($sql);
            if (!empty($params)) {
                foreach ($params as $i => $value) {
                    $stmt->bindParam(':' . $i, $value);
                }
            }
            $stmt->execute();
            return $stmt;
        } catch (\PDOException $e) {
            //Поидее можно написать обработчик на каждый тип исключения
            throw new \Exception($e->getMessage());
        }
    }

    protected function fetchAll($sql, $params = [])
    {
        $query = $this->query($sql, $params);
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function fetch($sql, $params = [])
    {
        $query = $this->query($sql, $params);
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    protected function transactQuery($sql, $data)
    {
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($sql);
            foreach ($data as $i => $row) {
                foreach ($row as $j => $value) {
                    $executeData[$j] = $value;
                }
                $stmt->execute($executeData);
            }
            $this->db->commit();
        } catch (\PDOException $e) {
            $this->db->rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}
