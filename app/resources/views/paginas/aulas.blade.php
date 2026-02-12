<x-layout titulo="Aulas - Senac">
    <div class="container-xl py-4 shadow">
        <!-- Abas -->
        <ul class="nav nav-pills gap-2 mb-4">
            <li class="nav-item">
                <a class="btn btn-primary" href="dashboardAdm"><i class="bi bi-bar-chart"></i> Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-primary" href="cursos"><i class="bi bi-backpack"></i> Cursos</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-primary" href="unidadesCurriculares"><i class="bi bi-book"></i> UCs</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-primary" href="docentes"><i class="bi bi-person-workspace"></i> Docentes</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-primary" href="alunos"><i class="bi bi-person"></i> Alunos</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-primary" href="turmas"><i class="bi bi-people-fill"></i> Turmas</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-primary active" href="aulas"><i class="bi bi-file-bar-graph"></i> Aulas</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-primary" href="indicadores"><i class="bi bi-card-list"></i> Indicadores</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-primary" href="relatorios"><i class="bi bi-clipboard-data"></i> Relatórios</a>
            </li>
        </ul>

        <!-- Conteúdo Principal -->
        <section class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2 class="fw-bold">Aulas</h2>
                    <p class="text-muted">Gerencie as aulas dos cursos e turmas</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovaAula">
                    <i class="bi bi-plus-lg"></i> Nova aula
                </button>
            </div>

            <!-- Tabela -->
            <div class="card shadow-sm rounded-4">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-muted">Dia da aula</th>
                                <th class="text-muted">UC Vinculado</th>
                                <th class="text-muted">Curso</th>
                                <th class="text-muted">Docentes</th>
                                <th class="text-muted">Status</th>
                                <th class="text-muted">Turmas</th>
                                <th class="text-center text-muted">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($aulas as $aula)
                            <tr>
                                <td><span class="badge bg-light text-dark rounded-pill px-3">{{ $aula->dia ?? '-' }}</span></td>
                                <td class="fw-semibold">{{ $aula->uc->nome ?? '-' }}</td>
                                <td><span class="badge rounded-pill px-3" style="background:#e7f0ff;color:#1d4ed8;">{{ $aula->curso->nome ?? '-' }}</span></td>
                                <td>
                                    @foreach($aula->docentes as $docente)
                                        <span class="badge rounded-pill px-2 bg-light text-dark me-1">{{ $docente->nomeDocente }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @switch($aula->status)
                                        @case('pendente')
                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3">Pendente</span>
                                        @break
                                        @case('andamento')
                                            <span class="badge bg-warning-subtle text-warning rounded-pill px-3">Em andamento</span>
                                        @break
                                        @default
                                            <span class="badge bg-light text-dark rounded-pill px-3">Não definido</span>
                                    @endswitch
                                </td>
                                <td>
                                    @foreach($aula->turmas as $turma)
                                        <span class="badge rounded-pill px-3 me-1" style="background:#f3e8ff;color:#7c3aed;">{{ $turma->codigoTurma }}</span>
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    <a href="/editarAulas/{{ $aula->id }}" class="btn btn-sm btn-outline-dark me-1"><i class="bi bi-pencil"></i></a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Nenhuma aula encontrada</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Modal Nova Aula -->
        <div class="modal fade" id="modalNovaAula" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content rounded-4 border-0">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold">Nova Aula</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ url('/inserirAula') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <label class="form-label fw-semibold">Unidades Curriculares *</label>
                                    <div class="border rounded-3 p-3 lista-scroll">
                                        @foreach($cursos as $curso)
                                            <div class="mb-3">
                                                <div class="fw-semibold text-secondary mb-2">{{ $curso->nome }}</div>
                                                @foreach($curso->ucs as $uc)
                                                    <div class="form-check mb-2 ms-3">
                                                        <input class="form-check-input" type="radio" name="uc_id" value="{{ $uc->id }}" id="uc{{ $uc->id }}">
                                                        <label class="form-check-label" for="uc{{ $uc->id }}">{{ $uc->nome }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col">
                                    <label class="form-label fw-semibold">Curso *</label>
                                    <select name="curso_id" class="form-select" required>
                                        <option value="">Selecione o curso</option>
                                        @foreach($cursos as $curso)
                                            <option value="{{ $curso->id }}">{{ $curso->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col">
                                    <label class="form-label fw-semibold">Dia da Aula *</label>
                                    <input type="date" name="dia" class="form-control" required>
                                </div>
                                <div class="col">
                                    <label class="form-label fw-semibold">Status *</label>
                                    <select name="status" class="form-select" required>
                                        <option value="">Selecione o status</option>
                                        <option value="pendente">Pendente</option>
                                        <option value="andamento">Em andamento</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col">
                                    <label class="form-label fw-semibold">Turma *</label>
                                    <select name="turma_id" class="form-select" required>
                                        <option value="">Selecione a turma</option>
                                        @foreach($turmas as $turma)
                                            <option value="{{ $turma->id }}">{{ $turma->codigoTurma }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col">
                                    <label class="form-label fw-semibold">Selecionar Docentes</label>
                                    <div class="lista-scroll">
                                        @foreach($docentes as $docente)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="docentes[]" value="{{ $docente->id }}" id="docente{{ $docente->id }}">
                                                <label class="form-check-label" for="docente{{ $docente->id }}">{{ $docente->nomeDocente }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer border-0 filter-tabs">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-warning text-white px-4">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- FIM DO MODAL -->
    </div>
</x-layout>
