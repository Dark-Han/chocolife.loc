<?php

namespace App\Contracts;

interface iFile
{
    public function getInsertedData(): array;
    public function getChangedRow(): array;
}
