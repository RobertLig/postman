<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthTranslation extends Model
{
    protected $fillable = ['month_id', 'month', 'language_id'];
}
