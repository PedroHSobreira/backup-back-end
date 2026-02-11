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

    // Curso -> UCs (1 curso, várias UCs)
    public function cursoUc(Request $request)
    {
        $request->validate([
            'curso_id' => 'required',
            'ucs' => 'required|array',
        ]);

        ucModel::whereIn('id', $request->ucs)
            ->update(['cursoCodigo' => $request->curso_id]);

        return back()->with('successo', 'UCs vinculadas ao Curso');
    }

    // Docente -> Cursos (N:N)
    public function docenteCurso(Request $request)
    {
        $request->validate([
            'docente_id' => 'required',
            'cursos' => 'required|array'
        ]);

        $docente = docenteModel::findOrFail($request->docente_id);
        $docente->cursos()->sync($request->cursos);

        return back()->with('successo', 'Cursos vinculados ao Docente');
    }

    // Docente -> UC (N:N)
    public function docenteUc(Request $request)
    {
        $request->validate([
            'uc_id' => 'required',
            'docentes' => 'required|array'
        ]);

        $uc = ucModel::findOrFail($request->uc_id);
        $uc->docentes()->sync($request->docentes);

        return back()->with('successo', 'Docentes vinculados à UC');
    }

    // Docente -> Turmas (N:N)
    public function docenteTurma(Request $request)
    {
        $request->validate([
            'docente_id' => 'required',
            'turmas' => 'required|array'
        ]);

        $docente = docenteModel::findOrFail($request->docente_id);
        $docente->turmas()->sync($request->turmas);

        return back()->with('successo', 'Turmas vinculadas ao Docente');
    }
}
