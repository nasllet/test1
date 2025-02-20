<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class company extends Model
{
    protected $table = 'companies';

    protected $fillable = [
            'id',
            'company_name',
            'street_address',
            'representative_name',
        ];
    public function getCompany()
    {
        return DB::table('companies')->get();
    }
    }