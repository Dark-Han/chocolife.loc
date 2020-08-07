<?php

namespace App\Models;

use Core\Model;
use Exception;

class Sale extends Model
{
    private $table = 'sales';
    private $insertedData = [];
    private $changedRow = [];

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

    public function handleCsv(): array
    {
        if ($_FILES['data']['type'] === 'text/csv') {
            $this->insert($_FILES['data']['tmp_name']);
            return $this->changedRow;
        } else {
            throw new Exception('Не верный формат файла !');
        }
    }

    private function insert(string $path): void
    {
        $this->makeInsertedData($path);
        if ($this->createTable()) {
            $sql = "INSERT INTO 
                                $this->table 
                            (ID,NAME,DATE_ST,DATE_EN,STATUS) 
                                VALUES 
                            (:ID,:NAME,:DATE_ST,:DATE_EN,:STATUS)";
            $this->transactQuery($sql, $this->insertedData);
        }
    }

    private function makeInsertedData(string $path): void
    {
        if (($file = fopen($path, "r")) !== FALSE) {
            while (($data = fgetcsv($file)) !== FALSE) {
                $arr = explode(';', $data[0]);
                $this->makeRow($arr);
            }
            fclose($file);
            unset($this->insertedData[0]);
            $this->changeRandRowStatusToOpposite();
        }
    }

    private function makeRow(array $arr): void
    {
        $row['ID'] = (int)$arr[0];
        $row['NAME'] = trim($arr[1], '"');
        $row['DATE_ST'] = strtotime($arr[2]);
        $row['DATE_EN'] = strtotime($arr[3]);
        $row['STATUS'] = trim($arr[4]);
        $this->insertedData[] = $row;
    }

    private function changeRandRowStatusToOpposite(): void
    {
        $rand = rand(1, count($this->insertedData));
        $this->changedRow = $this->insertedData[$rand];
        $this->changedRow['DATE_ST'] = date("m-d-Y", $this->changedRow['DATE_ST']);
        $this->changedRow['DATE_EN'] = date("m-d-Y", $this->changedRow['DATE_EN']);
        if ($this->changedRow['STATUS'] === 'On') {
            $newStatus = 'Off';
        } else {
            $newStatus = 'On';
        }
        $this->insertedData[$rand]['STATUS'] = $newStatus;
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
