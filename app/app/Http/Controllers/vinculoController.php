<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\turmaModel;
use App\Models\cursoModel;
use App\Models\docenteModel;
use App\Models\ucModel;

class vinculoController extends Controller
{
    // Aluno -> Turma
    public function alunoTurma(Request $request)
    {
        $request->validate([
            'aluno_id' => 'required',
            'turma_id' => 'required',
        ]);

        $turma = turmaModel::findOrFail($request->turma_id);
        $turma->alunos()->syncWithoutDetaching([$request->aluno_id]);

        return back()->with('successo', 'Aluno vinculado à Turma');
    }

    // Curso -> UCs
    public function cursoUc(Request $request)
    {
        $curso = cursoModel::findOrFail($request->curso_id);
        $curso->ucs()->sync($request->ucs);

        return back()->with('successo', 'UCs vinculadas ao Curso');
    }

    // Docente -> Cursos
    public function docenteCurso(Request $request)
    {
        $docente = docenteModel::findOrFail($request->docente_id);
        $docente->cursos()->sync($request->cursos);

        return back()->with('successo', 'Cursos vinculados ao Docente');
    }

    // Docente -> UC
    public function docenteUc(Request $request)
    {
        $uc = ucModel::findOrFail($request->uc_id);
        $uc->docentes()->sync($request->docentes);

        return back()->with('successo', 'Docentes vinculados à UC');
    }

    // Docente -> Turmas
    public function docenteTurma(Request $request)
    {
        $docente = docenteModel::findOrFail($request->docente_id);
        $docente->turmas()->sync($request->turmas);

        return back()->with('successo', 'Turmas vinculadas ao Docente');
    }
}

