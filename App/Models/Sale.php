<?php

namespace App\Models;

use App\Services\CsvFile;
use App\Contracts\iFile;
use Core\Model;
use Exception;


class Sale extends Model
{
    private $table = 'sales';

    public function getUrls(): array
    {
        $urls = [];
        $data = $this->all();
        foreach ($data as $key => $value) {
            $urls[] = "/sales/" . convertUrl($value["NAME"]) . "/" . $value["ID"];
        }
        return $urls;
    }

    private function all(): array
    {
        $data = $this->fetchAll("SELECT ID,NAME FROM sales");
        return $data;
    }

    public function find($id)
    {
        $row = $this->fetch("SELECT 
                             ID,
                             NAME,
                             FROM_UNIXTIME(DATE_ST,'%d-%m-%Y') as DATE_ST,
                             FROM_UNIXTIME(DATE_EN,'%d-%m-%Y') as DATE_EN,
                             STATUS
                             FROM $this->table WHERE ID=:ID LIMIT 1", ['ID' => $id]);
        return $row;
    }

    public function store(iFile $file): array
    {
        $insertedData = $file->getInsertedData();
        $this->insert($insertedData);
        return $file->getChangedRow();
    }

    private function insert(array $insertedData): void
    {
        if ($this->createTable()) {
            $sql = "INSERT INTO 
                                $this->table 
                            (ID,NAME,DATE_ST,DATE_EN,STATUS) 
                                VALUES 
                            (:ID,:NAME,:DATE_ST,:DATE_EN,:STATUS)";
            $this->transactQuery($sql, $insertedData);
        }
    }

    private function createTable(): bool
    {
        $query = $this->query("CREATE TABLE IF NOT EXISTS `$this->table` (
            `ID` INT  UNSIGNED NOT NULL,
            `NAME` varchar(255) NOT NULL,
            `DATE_ST` INT UNSIGNED NOT NULL,
            `DATE_EN` INT UNSIGNED NOT NULL,
            `STATUS` varchar(255)  NOT NULL,
            PRIMARY KEY (`ID`))");
        return true;
    }

    public function destroy()
    {
        $this->query("DELETE FROM $this->table");
    }
}
