<?php

namespace App\Http\Controllers;

use App\Models\administradorModel;
use App\Models\alunoModel;
use App\Models\cursoModel;
use App\Models\docenteModel;
use App\Models\turmaModel;
use App\Models\ucModel;
use Illuminate\Http\Request;

class administradorController extends Controller
{
    public function cadastrarAdministrador()
    {
        return view('paginas.dashboardAdm');
    } //fim do metodo de direcionamento

    public function inserirAdministrador(Request $request)
    {
        $nome                   = $request->input('nome');
        $cpf                    = $request->input('cpf');
        $dataNascimento         = $request->input('dataNascimento');
        $telefone               = $request->input('telefone');
        $email                  = $request->input('email');
        $senha                  = $request->input('senha');
        $dataCadastro           = $request->input('dataCadastro');
        $status                 = $request->input('status');

        //chamando model
        $model = new administradorModel();

        $model->nome             = $nome;
        $model->cpf              = $cpf;
        $model->dataNascimento   = $dataNascimento;
        $model->telefone         = $telefone;
        $model->email            = $email;
        $model->senha            = $senha;
        $model->dataCadastro     = $dataCadastro;
        $model->status           = $status;


        $model->save();
        return redirect('/');
    } //fim do metodo inserir

    public function consultarAdministrador()
    {
        // cards do dashboard
        $totalCursos   = cursoModel::count();
        $totalDocentes = docenteModel::count();
        $totalAlunos   = alunoModel::count();
        $turmasAtivas  = turmaModel::where('status', 'ativo')->count();

        // dados para gestão de vínculos (modais)
        $alunos   = alunoModel::all();
        $turmas   = turmaModel::with('curso')->get();
        $cursos   = cursoModel::all();
        $docentes = docenteModel::all();
        $ucs      = ucModel::all();

        return view('paginas.dashboardAdm', compact(
            'totalCursos',
            'totalDocentes',
            'totalAlunos',
            'turmasAtivas',
            'alunos',
            'turmas',
            'cursos',
            'docentes',
            'ucs'
        ));
    } //fim do metodo consultar


    public function editarAdministrador($id)
    {
        $dado = administradorModel::findOrFail($id);
        return view('paginas.', compact('dado'));
    } //fim do metodo editar

    public function atualizarAdministrador(Request $request, $id)
    {
        administradorModel::where('id', $id)->update($request->all());
        return redirect('/consultar');
    } //fim do metodo atualizar

    public function excluirAdministrador(Request $request, $id)
    {
        administradorModel::where('id', $id)->delete($request->all());
        return redirect('/consultar');
    } //fim do metodo excluir

}
