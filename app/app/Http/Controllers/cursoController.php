<?php

namespace App\Http\Controllers;

use App\Models\cursoModel;
use Illuminate\Http\Request;

class cursoController extends Controller
{
    public function cadastrarCurso()
    {
        return view('paginas.cursos');
    } //fim do metodo de direcionamento

    public function inserirCurso(Request $request)
    {
        $id                 = $request->input('id');
        $nome               = $request->input('nome');
        $tipo               = $request->input('tipo');
        $cargaHoraria       = $request->input('cargaHoraria');
        $turno              = $request->input('turno');
        $horario            = $request->input('horario');
        $preco              = $request->input('preco');
        $vagas              = $request->input('vagas');
        $bolsas             = $request->input('bolsas');
        $dataInicio         = $request->input('dataInicio');
        $situacao           = $request->input('situacao');
        $sigla              = $request->input('sigla');


        //chamando model
        $model = new cursoModel();

        $model->id               = $id;
        $model->nome             = $nome;
        $model->tipo             = $tipo;
        $model->cargaHoraria     = $cargaHoraria;
        $model->turno            = $turno;
        $model->horario          = $horario;
        $model->preco            = $preco;
        $model->vagas            = $vagas;
        $model->bolsas           = $bolsas;
        $model->dataInicio       = $dataInicio;
        $model->situacao         = $situacao;
        $model->sigla            = $sigla;

        $model->save();
        return redirect('/cursos');
    } //fim do metodo inserir

    public function consultarCurso()
    {
        $ids = cursoModel::all();
        return view('paginas.cursos', compact('ids'));
    } //fim do metodo de consulta

    public function editarCursos($id)
    {
        $dado = cursoModel::findOrFail($id);
        return view('paginas.editarCurso', compact('dado'));
    } //fim do metodo editar

    public function atualizarCurso(Request $request, $id)
    {
        cursoModel::where('id', $id)->update(
            $request->except(['_token', '_method'])
        );

        return redirect('/cursos');
    } //fim do metodo atualizar

    public function excluirCurso($id)
    {
        cursoModel::where('id', $id)->delete();
        return redirect('/cursos');
    } //fim do metodo excluir
}