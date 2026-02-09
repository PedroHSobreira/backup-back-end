<?php

namespace App\Http\Controllers;

use App\Models\ucModel;
use App\Models\cursoModel;
use Illuminate\Http\Request;

class ucController extends Controller
{
    public function cadastrarUc()
    {
        return view('paginas.unidadesCurriculares');
    } //fim do metodo de direcionamento

    public function inserirUc(Request $request)
    {
        $codigoUc           = $request->input('codigoUc');
        $nome               = $request->input('nome');
        $cargaHoraria       = $request->input('cargaHoraria');
        $dias               = $request->input('dias');
        $presencaMinima     = $request->input('presencaMinima');
        $descricao          = $request->input('descricao');
        $horario            = $request->input('horario');
        $status             = $request->input('status');
        $cursoCodigo        = $request->input('cursoCodigo');

        //chamando model
        $model = new ucModel();

        $model->codigoUc       = $codigoUc;
        $model->nome           = $nome;
        $model->cargaHoraria   = $cargaHoraria;
        $model->dias           = $dias;
        $model->presencaMinima = $presencaMinima;
        $model->descricao      = $descricao;
        $model->horario        = $horario;
        $model->status         = $status;
        $model->dias           = $dias;
        $model->cursoCodigo    = $cursoCodigo;

        $model->save();
        return redirect('/unidadesCurriculares');
    } //fim do metodo inserir

    public function consultarUc()
    {
        $ucs = ucModel::with('curso')->get();
        $cursos = cursoModel::all();

        return view('paginas.unidadesCurriculares', compact('ucs', 'cursos'));
    }

    public function editarUc($id)
    {
        $dado = ucModel::findOrFail($id);
        $cursos = cursoModel::all();

        return view('paginas.editarUnidadesCurriculares', compact('dado', 'cursos'));
    }

    public function atualizarUc(Request $request, $id)
    {
        ucModel::where('id', $id)->update(
            $request->except(['_token', '_method'])
        );

        return redirect('/unidadesCurriculares');
    } //fim do metodo atualizar

    public function excluirUc($id)
    {
        ucModel::where('id', $id)->delete();
        return redirect('/unidadesCurriculares');
    } //fim do metodo excluir
}