<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class alunoModel extends Model
{
    use HasFactory;
    protected $table = 'aluno';

    public function turmas()
    {
        return $this->belongsToMany(
            turmaModel::class,
            'aluno_turma',
            'aluno_id',
            'turma_id'
        );
    }
}