<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Models\ucModel;
use App\Models\cursoModel;
use App\Models\aulaModel;
use App\Models\turmaModel;


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
        $presencaMinima     = $request->input('presencaMinima');
        $descricao          = $request->input('descricao');
        $status             = $request->input('status');
        $cursoCodigo        = $request->input('cursoCodigo');

        //chamando model
        $model = new ucModel();

        $model->codigoUc       = $codigoUc;
        $model->nome           = $nome;
        $model->cargaHoraria   = $cargaHoraria;
        $model->presencaMinima = $presencaMinima;
        $model->descricao      = $descricao;
        $model->status         = $status;
        $model->cursoCodigo    = $cursoCodigo;

        $model->save();
        return redirect('/unidadesCurriculares');
    } //fim do metodo inserir

    public function consultarUc()
    {
        $ucs = ucModel::with('curso')->get();
        $cursos = cursoModel::all();
        $turmas = turmaModel::all(); // <-- ADD

        return view('paginas.unidadesCurriculares', compact('ucs', 'cursos', 'turmas'));
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

    public function iniciarUc(Request $request)
    {
        $request->validate([
            'uc_id' => 'required|exists:uc,id',
            'turma_id' => 'required|exists:turma,id',
            'data_inicio' => 'required|date'
        ]);

        $uc = ucModel::findOrFail($request->uc_id);
        $turma = turmaModel::findOrFail($request->turma_id);

        // CONFIG PADRÃO (ajuste se quiser depois)
        $horasPorDia = 4;

        // total de dias necessários
        $diasNecessarios = ceil($uc->cargaHoraria / $horasPorDia);

        $data = Carbon::parse($request->data_inicio);
        $contador = 0;

        while ($contador < $diasNecessarios) {

            // pula sábado e domingo
            if ($data->isWeekend()) {
                $data->addDay();
                continue;
            }

            // cria aula
            $aula = aulaModel::create([
                'nome' => $uc->nome,
                'dia' => $data->format('Y-m-d'),
                'horaInicio' => '08:00',
                'horaFim' => '12:00',
                'uc_id' => $uc->id,
                'curso_id' => $uc->curso->id,
                'status' => 'prevista'
            ]);

            // vincula turma
            $aula->turmas()->attach($turma->id);

            $contador++;
            $data->addDay();
        }

        return back()->with('success', 'UC iniciada e aulas geradas automaticamente!');
    }
}
