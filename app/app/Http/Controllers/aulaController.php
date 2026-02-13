<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\aulaModel;
use App\Models\ucModel;
use App\Models\turmaModel;
use App\Models\docenteModel;
use App\Models\cursoModel;
use App\Models\UcTurmaModel;
use App\Services\CalendarioLetivoService;

class aulaController extends Controller
{

    public function consultarAula()
    {
        $ucs = ucModel::with([
            'aulas' => fn($q) => $q->orderBy('dia', 'asc')
                ->with(['curso', 'turmas', 'docentes'])
        ])->get();


        $cursos   = cursoModel::with('ucs')->get();
        $turmas   = turmaModel::all();
        $docentes = docenteModel::all();

        return view('paginas.aulas', compact(
            'ucs',
            'cursos',
            'turmas',
            'docentes'
        ));
    }


    public function inserirAula(Request $request)
    {
        $request->validate([
            'curso_id' => 'required|exists:curso,id',
            'uc_id'    => 'required|exists:uc,id',
            'dia'      => 'required|date',
            'status'   => 'required|string'
        ]);

        DB::transaction(function () use ($request) {

            $aula = aulaModel::create([
                'curso_id' => $request->curso_id,
                'uc_id'    => $request->uc_id,
                'dia'      => $request->dia,
                'status'   => $request->status
            ]);

            if ($request->turma_id) {
                $aula->turmas()->attach($request->turma_id);
            }

            if ($request->docentes) {
                $aula->docentes()->attach($request->docentes);
            }
        });

        return redirect('/aulas')->with('success', 'Aula criada.');
    }

    public function editarAulas($id)
    {
        $dado     = aulaModel::findOrFail($id);
        $cursos   = cursoModel::with('ucs')->get();
        $turmas   = turmaModel::all();
        $docentes = docenteModel::all();

        return view('paginas.editarAulas', compact(
            'dado',
            'cursos',
            'turmas',
            'docentes'
        ));
    }

    public function atualizarAula(Request $request, $id)
    {
        $request->validate([
            'curso_id' => 'required|exists:curso,id',
            'uc_id'    => 'required|exists:uc,id',
            'dia'      => 'required|date',
            'status'   => 'required|string'
        ]);

        DB::transaction(function () use ($request, $id) {

            $aula = aulaModel::findOrFail($id);

            $aula->update([
                'curso_id' => $request->curso_id,
                'uc_id'    => $request->uc_id,
                'dia'      => $request->dia,
                'status'   => $request->status
            ]);

            $aula->turmas()->sync(
                $request->turma_id ? [$request->turma_id] : []
            );

            $aula->docentes()->sync(
                $request->docentes ?? []
            );
        });

        return redirect('/aulas')->with('success', 'Aula atualizada.');
    }

    public function excluirAula($id)
    {
        $aula = aulaModel::findOrFail($id);

        $aula->turmas()->detach();
        $aula->docentes()->detach();
        $aula->delete();

        return redirect('/aulas')->with('success', 'Aula removida.');
    }

    public function iniciarUc(Request $request, CalendarioLetivoService $service)
    {
        $request->validate([
            'uc_id'       => 'required|exists:uc,id',
            'turma_id'    => 'required|exists:turma,id',
            'data_inicio' => 'required|date'
        ]);

        $uc    = ucModel::findOrFail($request->uc_id);
        $turma = turmaModel::findOrFail($request->turma_id);
        $curso = cursoModel::findOrFail($turma->curso_id);

        // Verifica se já existe UC em andamento na turma
        $existe = UcTurmaModel::where([
            'uc_id'    => $uc->id,
            'turma_id' => $turma->id,
            'status'   => 'em_andamento'
        ])->exists();

        if ($existe) {
            return back()->with('error', 'Essa UC já está em andamento.');
        }

        DB::transaction(function () use ($request, $uc, $turma, $curso, $service) {

            // Calcula a data final da UC usando serviço de calendário
            $dataFim = $service->calcularDataFinal(
                $request->data_inicio,
                $curso->dias,
                $uc->cargaHoraria,
                $turma->horasPorDia
            );

            // Remove aulas antigas dessa UC na turma (evita duplicidade)
            $uc->aulas()->whereHas('turmas', fn($q) => $q->where('turma_id', $turma->id))->delete();

            // Cria ou atualiza vínculo UC-Turma
            UcTurmaModel::updateOrCreate(
                ['uc_id' => $uc->id, 'turma_id' => $turma->id],
                [
                    'data_inicio' => $request->data_inicio,
                    'data_fim'    => $dataFim,
                    'status'      => 'em_andamento'
                ]
            );

            // Gera as datas letivas da UC
            $datas = $service->listarDatasLetivas(
                $request->data_inicio,
                $dataFim,
                $curso->dias
            );

            // Cria aulas na UC e vincula à turma
            foreach ($datas as $dia) {
                $aula = aulaModel::create([
                    'curso_id' => $curso->id,
                    'uc_id'    => $uc->id,
                    'dia'      => $dia,
                    'status'   => 'prevista'
                ]);
                $aula->turmas()->attach($turma->id);
            }
        });

        return redirect('/aulas')->with('success', 'UC iniciada e aulas geradas com sucesso!');
    }
}
