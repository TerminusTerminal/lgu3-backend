<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'investor_id','project_id','incentive_id','requested_amount','status','remarks','submitted_at','decision_at'
    ];

    protected $dates = ['submitted_at','decision_at'];

    public function investor() { return $this->belongsTo(Investor::class); }
    public function project() { return $this->belongsTo(Project::class); }
    public function incentive() { return $this->belongsTo(Incentive::class); }
    public function allocation() { return $this->hasOne(IncentiveAllocation::class); }
}
