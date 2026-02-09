<?php

namespace App\Http\Controllers;

use App\Models\turmaModel;
use App\Models\cursoModel;
use App\Models\docenteModel;
use App\Models\alunoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class turmaController extends Controller
{
    public function cadastrarTurma()
    {
        return view('paginas.turmas');
    } //fim do metodo de direcionamento

    public function inserirTurma(Request $request)
    {
        $cursoModel = cursoModel::findOrFail($request->curso_id);

        $ultimaTurma = turmaModel::where('curso_id', $cursoModel->id)
            ->where('turno', $request->turno)
            ->orderBy('id', 'desc')
            ->first();

        if ($ultimaTurma) {
            preg_match('/(\d+)/', $ultimaTurma->codigoTurma, $matches);
            $numeroSequencial = intval($matches[1]) + 1;
        } else {
            $numeroSequencial = 1;
        }

        $codigoTurma = $cursoModel->sigla . $numeroSequencial . $request->turno;

        $model = new turmaModel();
        $model->curso_id    = $cursoModel->id;
        $model->codigoTurma = $codigoTurma;
        $model->dataInicio  = $request->dataInicio;
        $model->dataFim     = $request->dataFim;
        $model->turno       = $request->turno;
        $model->status      = $request->status;

        $model->save();

        // vincular docente
        if ($request->has('docentes')) {
            $model->docentes()->sync($request->docentes);
        }

        // vincular aluno
        if ($request->has('alunos')) {
            $model->alunos()->sync($request->alunos);
        }

        return redirect('/turmas');
    } //fim do metodo inserir

    public function consultarTurma()
    {
        $turmas   = turmaModel::with(['curso', 'docentes', 'alunos'])->get();
        $cursos   = cursoModel::all();
        $docentes = docenteModel::all();
        $alunos   = alunoModel::all();

        return view('paginas.turmas', compact(
            'turmas',
            'cursos',
            'docentes',
            'alunos'
        ));
    } //fim do metodo consultar


    public function editarTurma($id)
    {
        $dado     = turmaModel::findOrFail($id);
        $cursos   = cursoModel::all();
        $docentes = docenteModel::all();
        $alunos   = alunoModel::all();

        return view('paginas.editarTurmas', compact(
            'dado',
            'cursos',
            'docentes',
            'alunos'
        ));
    } //fim do metodo editar

    public function atualizarTurma(Request $request, $id)
    {
        $turma = turmaModel::findOrFail($id);

        $turma->update(
            $request->except(['_token', 'docentes', 'alunos'])
        );

        if ($request->has('docentes')) {
            $turma->docentes()->sync($request->docentes);
        }

        if ($request->has('alunos')) {
            $turma->alunos()->sync($request->alunos);
        }

        return redirect('/turmas');
    } //fim do metodo atualizar

    public function excluirTurma($id)
    {
        turmaModel::where('id', $id)->delete();
        return redirect('/turmas');
    } //fim do metodo excluir
}