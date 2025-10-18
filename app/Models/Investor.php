<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','email','phone','address','type','tax_id'
    ];

    public function projects() {
        return $this->hasMany(Project::class);
    }

    public function applications() {
        return $this->hasMany(Application::class);
    }
}
