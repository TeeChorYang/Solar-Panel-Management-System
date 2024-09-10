<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use League\CommonMark\Node\Query\OrExpr;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Installation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['order_id', 'manager_id', 'schedule_date', 'status'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id', 'id');
    }

    public function maintenanceLogs()
    {
        return $this->hasMany(MaintenanceLog::class, 'installation_id', 'id');
    }
}