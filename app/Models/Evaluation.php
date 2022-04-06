<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $table='evaluations';
    protected $fillable=['ponit','exams_team_id','judge_round_id'];
    use HasFactory;
}
