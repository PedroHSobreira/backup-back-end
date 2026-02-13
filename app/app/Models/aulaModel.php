<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class aulaModel extends Model
{
    use HasFactory;

    protected $table = 'aulas';

    protected $fillable = [
        'curso_id',
        'uc_id',
        'dia',
        'status'
    ];

    public function turmas()
    {
        return $this->belongsToMany(turmaModel::class, 'aula_turma', 'aula_id', 'turma_id');
    }

    public function docentes()
    {
        return $this->belongsToMany(docenteModel::class, 'aula_docente', 'aula_id', 'docente_id');
    }

    public function curso()
    {
        return $this->belongsTo(cursoModel::class, 'curso_id');
    }

    public function uc()
    {
        return $this->belongsTo(ucModel::class, 'uc_id');
    }


    // STATUS CALCULADO (SEM SALVAR NO BANCO)
    public function getStatusCalculadoAttribute()
    {
        $hoje = date('Y-m-d');

        if ($this->dia > $hoje) return 'prevista';
        if ($this->dia == $hoje) return 'andamento';
        return 'concluida';
    }
}
