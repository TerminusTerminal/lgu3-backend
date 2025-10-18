<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'investor_id','name','sector','investment_amount','location','description','status'
    ];

    public function investor() { return $this->belongsTo(Investor::class); }
    public function applications() { return $this->hasMany(Application::class); }
}
