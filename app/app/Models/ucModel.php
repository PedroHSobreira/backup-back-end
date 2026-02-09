<?php

namespace App\Models;

use App\Models\cursoModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ucModel extends Model
{
    use HasFactory;
    protected $table='uc';

    public function curso()
    {
        return $this->belongsTo(cursoModel::class, 'cursoCodigo', 'id');
    }
}