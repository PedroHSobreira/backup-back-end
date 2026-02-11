<?php

namespace App\Http\Controllers;

use App\Models\turmaModel;
use App\Models\cursoModel;
use App\Models\docenteModel;
use App\Models\alunoModel;
use Illuminate\Http\Request;

class turmaController extends Controller
{
    public function __construct(){

    }//fim do método

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
        $model->dataFim     = $this->retornarDataFinal($model->dataInicio);
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
    //fim do metodo editar

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

    public function chamarChatGPT($pergunta) {
        // Substitua pela sua chave API real
        $apiKey = 'AIzaSyBLyGF2xt0NUicNtk3Z9GrXt1zFO1wLlX0';
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=".$apiKey;
        
        $data = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => "$pergunta"]
                    ]
                ]
            ]
        ];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $result = json_decode($response, true);
        
        // Exibe a resposta do modelo
        if (preg_match('/\d{4}-\d{2}-\d{2}/', $result['candidates'][0]['content']['parts'][0]['text'], $matches)) {
            return $matches[0];
        } else {
            return "Erro: formato inesperado -> " . $response;
        }//fim do método
    }//fim do método chatgpt

    public function retornarDataFinal($dataInicio){
        $pergunta = "A partir da data $dataInicio, calcule a data final considerando: curso de 1200 horas, 4 horas por dia, apenas de segunda a sexta-feira. Retorne SOMENTE a data final no formato YYYY-MM-DD, sem explicações.";
        return $this->chamarChatGPT($pergunta);
    }//fim do método
}//fim da classe
