<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incentive extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','description','type','max_amount','duration_months','conditions','active'
    ];
}
