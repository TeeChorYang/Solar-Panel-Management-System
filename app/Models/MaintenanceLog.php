<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaintenanceLog extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['installation_id', 'manager_id', 'log_date', 'description'];

    public function installation()
    {
        return $this->belongsTo(Installation::class, 'installation_id', 'id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id', 'id');
    }
}