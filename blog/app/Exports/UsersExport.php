<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{
    public function collection()
    {

        $a = new Collection([1,2,3],[1,2,3]);
        return $a;
    }
}