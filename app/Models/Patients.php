<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patients extends Model
{
    use HasFactory;
    protected $fillable = ['external_id', 'name', 'age', 'gender'];

    public function labResults()
    {
        return $this->hasMany(lab_results::class);
    }

    public function results()
    {
        return $this->hasMany(lab_results::class, 'patient_id');
    }

}
