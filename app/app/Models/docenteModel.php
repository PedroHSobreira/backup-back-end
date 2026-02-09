<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class docenteModel extends Model
{
    use HasFactory;
    protected $table = 'docente';

    public function turmas()
    {
        return $this->belongsToMany(
            turmaModel::class,
            'turma_docente',
            'docente_id',
            'turma_id'
        );
    }
}