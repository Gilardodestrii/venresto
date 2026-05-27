<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WasteRecordItem extends Model
{
    protected $fillable = [
        'waste_record_id',
        'material_id',
        'qty',
    ];

    public function wasteRecord()
    {
        return $this->belongsTo(WasteRecord::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
