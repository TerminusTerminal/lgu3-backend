<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncentiveAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id','incentive_id','allocated_amount','start_date','end_date','notes'
    ];

    public function application() { return $this->belongsTo(Application::class); }
    public function incentive() { return $this->belongsTo(Incentive::class); }
}
