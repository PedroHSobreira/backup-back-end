<?php

namespace App\Http\Controllers;

use App\Models\docenteModel;
use Illuminate\Http\Request;

class docenteController extends Controller
{
    public function cadastrarDocente()
    {
        return view('paginas.docentes');
    } //fim do metodo de direcionamento

    public function inserirDocente(Request $request)
    {
        $request->validate([
            'turno' => 'required|array|min:1'
        ], [
            'turno.required' => 'Selecione pelo menos um turno.',
        ]);

        $nomeDocente            = $request->input('nomeDocente');
        $cpf                    = $request->input('cpf');
        $dataNascimento         = $request->input('dataNascimento');
        $telefone               = $request->input('telefone');
        $emailDocente           = $request->input('emailDocente');
        $formacao               = $request->input('formacao');
        $especializacao         = $request->input('especializacao');
        $status                 = $request->input('status');
        $dataCadastro           = now();
        $cargaHoraria           = $request->input('cargaHoraria');
        $turno                  = $request->input('turno');
        $senhaDocente           = $request->input('senhaDocente');
        $endereco               = $request->input('endereco');

        $turno = $request->input('turno', []);

        //chamando model
        $model = new docenteModel();

        $model->nomeDocente                     = $nomeDocente;
        $model->cpf                             = $cpf;
        $model->dataNascimento                  = $dataNascimento;
        $model->telefone                        = $telefone;
        $model->emailDocente                    = $emailDocente;
        $model->formacao                        = $formacao;
        $model->especializacao                  = $especializacao;
        $model->status                          = $status;
        $model->dataCadastro                    = $dataCadastro;
        $model->cargaHoraria                    = $cargaHoraria;
        $model->turno                           = $turno;
        $model->senhaDocente                    = $senhaDocente;
        $model->endereco                        = $endereco;

        $model->save();
        return redirect('/docentes');
    } //fim do metodo inserir

    public function consultarDocente()
    {
        $docentes = docenteModel::all();
        return view('paginas.docentes', compact('docentes'));
    } //fim do metodo consultar


    public function editarDocente($id)
    {
        $dado = docenteModel::findOrFail($id);
        return view('paginas.editarDocentes', compact('dado'));
    } //fim do metodo editar

    public function atualizarDocente(Request $request, $id)
    {
        $request->validate([
            'turno' => 'required|array|min:1'
        ], [
            'turno.required' => 'Selecione pelo menos um turno.',
        ]);
        $docente = docenteModel::findOrFail($id);

        $docente->update($request->except('_token'));

        return redirect('/docentes');
    } //fim do metodo atualiziar

    public function excluirDocente($id)
    {
        docenteModel::where('id', $id)->delete();
        return redirect('/docentes');
    } //fim do metodo excluir
}
