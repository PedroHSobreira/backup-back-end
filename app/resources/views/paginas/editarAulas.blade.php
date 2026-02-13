<x-layout titulo="Editar Aula - Senac">
    <div class="container-xl py-4 shadow">
        <!-- Abas -->
        <ul class="nav nav-pills gap-2 mb-4">
            <li class="nav-item">
                <a class="btn btn-primary" href="/dashboardAdm"><i class="bi bi-bar-chart"></i> Dashboard</a>
            </li>

            <li class="nav-item">
                <a class="btn btn-primary" href="/cursos"><i class="bi bi-backpack"></i> Cursos</a>
            </li>

            <li class="nav-item">
                <a class="btn btn-primary" href="/unidadesCurriculares"><i class="bi bi-book"></i> UCs</a>
            </li>

            <li class="nav-item">
                <a class="btn btn-primary" href="/docentes"><i class="bi bi-person-workspace"></i> Docentes</a>
            </li>

            <li class="nav-item">
                <a class="btn btn-primary" href="/alunos"><i class="bi bi-person"></i> Alunos</a>
            </li>

            <li class="nav-item">
                <a class="btn btn-primary" href="/turmas"><i class="bi bi-people-fill"></i> Turmas</a>
            </li>

            <li class="nav-item">
                <a class="btn btn-primary active" href="/aulas"><i class="bi bi-person"></i> Aulas</a>
            </li>

            <li class="nav-item">
                <a class="btn btn-primary" href="/indicadores"><i class="bi bi-person"></i> Indicadores</a>
            </li>

            <li class="nav-item">
                <a class="btn btn-primary" href="/relatorios"> <i class="bi bi-clipboard-data"></i> Relatórios</a>
            </li>
        </ul>

        <section class="container-fluid">
            <!-- Cabeçalho -->
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h2 class="fw-bold">Aulas</h2>
                    <p class="text-muted">Editar as aulas dos cursos e turmas</p>
                </div>
                <a href="/aulas" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-1"></i> Voltar
                </a>
            </div>

            <!-- Formulário -->
            <form action="{{ url('/atualizarAula/'.$dado->id) }}" method="POST">
                @csrf

                <!-- Unidade Curricular -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Unidade Curricular *</label>
                    <div class="border rounded-3 p-3 lista-scroll">
                        @foreach($cursos as $curso)
                        <div class="mb-3">
                            <div class="fw-semibold text-secondary mb-2">{{ $curso->nome }}</div>
                            @foreach($curso->ucs as $uc)
                            <div class="form-check mb-2 ms-3">
                                <input class="form-check-input" type="radio" name="uc_id"
                                    value="{{ $uc->id }}" id="uc{{ $uc->id }}"
                                    {{ $dado->uc_id == $uc->id ? 'checked' : '' }}>
                                <label class="form-check-label" for="uc{{ $uc->id }}">{{ $uc->nome }}</label>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Curso -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Curso *</label>
                    <select name="curso_id" class="form-select" required>
                        <option value="">Selecione o curso</option>
                        @foreach ($cursos as $curso)
                        <option value="{{ $curso->id }}" {{ $curso->id == $dado->curso_id ? 'selected' : '' }}>
                            {{ $curso->nome }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Dia da Aula e Status -->
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label fw-semibold">Dia da Aula *</label>
                        <input type="date" name="dia" class="form-control" value="{{ $dado->dia }}" required>
                    </div>
                </div>

                <!-- Turma -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Turma *</label>
                    <select name="turma_id" class="form-select" required>
                        <option value="">Selecione a turma</option>
                        @foreach ($turmas as $turma)
                        <option value="{{ $turma->id }}" {{ $dado->turmas->contains($turma->id) ? 'selected' : '' }}>
                            {{ $turma->codigoTurma }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Docentes -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Selecionar Docentes</label>
                    <div class="lista-scroll">
                        @foreach ($docentes as $docente)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="docentes[]"
                                value="{{ $docente->id }}" id="docente{{ $docente->id }}"
                                {{ $dado->docentes->contains($docente->id) ? 'checked' : '' }}>
                            <label class="form-check-label" for="docente{{ $docente->id }}">
                                {{ $docente->nomeDocente }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Botões -->
                <div class="d-flex gap-3 mt-4">
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalExcluirAula">
                        Excluir
                    </button>
                    <button type="submit" class="btn btn-warning text-white px-4">Salvar</button>
                </div>
            </form>

            <!-- Modal Excluir -->
            <div class="modal fade" id="modalExcluirAula" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Excluir Aula</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Tem certeza que deseja excluir esta aula?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Não</button>
                            <a href="{{ url('/excluirAula/'.$dado->id) }}" class="btn btn-danger">Sim</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-layout>