<?php

namespace App\Services;

use App\Contracts\iFile;

class CsvFile implements iFile
{

    private $insertedData = [];
    private $changedRow = [];
    private $path;

    public function __construct()
    {
        $this->ifValidateSetPath();
    }

    private function ifValidateSetPath(): void
    {
        if (is_uploaded_file($_FILES['data']['tmp_name'])) {
            if ($_FILES['data']['type'] === 'text/csv') {
                $this->path = $_FILES['data']['tmp_name'];
            } else {
                throw new \Exception('Не верный формат файла !');
            }
        } else {
            throw new \Exception('Файл не загружен !');
        }
    }

    public function getInsertedData(): array
    {
        if (($file = fopen($this->path, "r")) !== FALSE) {
            while (($data = fgetcsv($file)) !== FALSE) {
                $arr = explode(';', $data[0]);
                $this->makeRow($arr);
            }
            fclose($file);
            unset($this->insertedData[0]);
            $this->changeRandRowStatusToOpposite();
            return $this->insertedData;
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

    public function getChangedRow(): array
    {
        return $this->changedRow;
    }
}
