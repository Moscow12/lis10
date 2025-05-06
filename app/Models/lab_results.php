<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lab_results extends Model
{
    use HasFactory;
    protected $fillable = ['patient_id', 'test_code', 'value', 'unit', 'reference_range', 'flag'];

    public function patient()
    {
        return $this->belongsTo(patients::class);
    }
}
