<?php

namespace App\Http\Controllers;

use App\Models\turmaModel;
use App\Models\cursoModel;
use App\Models\docenteModel;
use App\Models\alunoModel;
use App\Models\aulaModel;
use App\Services\CalendarioLetivoService;
use Illuminate\Http\Request;

class turmaController extends Controller
{
    public function __construct() {}

    public function cadastrarTurma()
    {
        return view('paginas.turmas');
    }

    public function inserirTurma(Request $request)
    {
        $curso = cursoModel::findOrFail($request->curso_id);

        $ultimaTurma = turmaModel::where('curso_id', $curso->id)
            ->orderBy('id', 'desc')
            ->first();

        $numeroSequencial = 1;

        if ($ultimaTurma) {
            preg_match('/(\d+)/', $ultimaTurma->codigoTurma, $matches);
            $numeroSequencial = intval($matches[1]) + 1;
        }

        $codigoTurma = $curso->sigla . $numeroSequencial . $request->turno;

        $service = new CalendarioLetivoService();

        $dataFim = $service->calcularDataFinal(
            $request->dataInicio,
            $curso->dias,
            $curso->cargaHoraria,
            $request->horasPorDia
        );

        $turma = new turmaModel();

        $turma->curso_id    = $curso->id;
        $turma->codigoTurma = $codigoTurma;
        $turma->dataInicio  = $request->dataInicio;
        $turma->dataFim     = $dataFim;
        $turma->horasPorDia = $request->horasPorDia;
        $turma->turno       = $request->turno;
        $turma->status      = $request->status;

        $turma->save();

        if ($request->has('docentes')) {
            $turma->docentes()->sync($request->docentes);
        }

        if ($request->has('alunos')) {
            $turma->alunos()->sync($request->alunos);
        }

        return redirect('/turmas');
    }

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
    }

    public function editarTurma($id)
    {
        $dado = turmaModel::with(['docentes', 'alunos'])->findOrFail($id);

        $cursos   = cursoModel::all();
        $docentes = docenteModel::all();
        $alunos   = alunoModel::all();

        return view('paginas.editarTurmas', compact(
            'dado',
            'cursos',
            'docentes',
            'alunos'
        ));
    }

    public function atualizarTurma(Request $request, $id)
    {
        $turma = turmaModel::findOrFail($id);
        $curso = cursoModel::findOrFail($request->curso_id);

        $service = new CalendarioLetivoService();

        // Recalcula data final da turma
        $dataFim = $service->calcularDataFinal(
            $request->dataInicio,
            $curso->dias,
            $curso->cargaHoraria,
            $request->horasPorDia
        );

        // Atualiza dados da turma
        $turma->update([
            'curso_id'    => $curso->id,
            'dataInicio'  => $request->dataInicio,
            'dataFim'     => $dataFim,
            'horasPorDia' => $request->horasPorDia,
            'turno'       => $request->turno,
            'status'      => $request->status,
        ]);

        // Atualiza docentes e alunos
        if ($request->has('docentes')) {
            $turma->docentes()->sync($request->docentes);
        }
        if ($request->has('alunos')) {
            $turma->alunos()->sync($request->alunos);
        }

        // --- REGERA AULAS DAS UCs DA TURMA ---
        $ucsDaTurma = $turma->ucs()->get(); // Relação turma->ucs via UcTurmaModel

        foreach ($ucsDaTurma as $ucTurma) {
            $uc = $ucTurma->uc;

            // Remove aulas antigas da UC nessa turma
            $uc->aulas()->whereHas('turmas', fn($q) => $q->where('turma_id', $turma->id))->delete();

            // Calcula nova data final da UC
            $dataFimUc = $service->calcularDataFinal(
                $turma->dataInicio,
                $curso->dias,
                $uc->cargaHoraria,
                $turma->horasPorDia
            );

            // Lista datas letivas da UC
            $datas = $service->listarDatasLetivas(
                $turma->dataInicio,
                $dataFimUc,
                $curso->dias
            );

            // Cria aulas novamente
            foreach ($datas as $dia) {
                $aula = aulaModel::create([
                    'curso_id' => $curso->id,
                    'uc_id'    => $uc->id,
                    'dia'      => $dia,
                    'status'   => 'prevista'
                ]);
                $aula->turmas()->attach($turma->id);
            }

            // Atualiza datas da UC na turma
            $ucTurma->update([
                'data_inicio' => $turma->dataInicio,
                'data_fim'    => $dataFimUc
            ]);
        }

        return redirect('/turmas')->with('success', 'Turma atualizada e aulas das UCs regeneradas.');
    }


    public function excluirTurma($id)
    {
        turmaModel::where('id', $id)->delete();
        return redirect('/turmas');
    }
}
