<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VaccinationSchedule extends Model
{
    protected $fillable = ['user_id', 'vaccine_center_id', 'scheduled_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vaccineCenter()
    {
        return $this->belongsTo(VaccineCenter::class);
    }
}
