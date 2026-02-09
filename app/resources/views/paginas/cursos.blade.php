<x-layout titulo="Cursos - Senac">

    <div class="container-xl py-4 shadow">
        <!-- Abas -->

        <ul class="nav nav-pills gap-2 mb-4">
            <li class="nav-item">
                <a class="btn btn-primary" href="/dashboardAdm"><i class="bi bi-speedometer2 me-1"></i>
                    Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-primary active" href="/cursos"><i class="bi bi-clipboard2-check me-1"></i>
                    Cursos</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-primary " href="/unidadesCurriculares"><i class="bi bi-people me-1"></i> UCs</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-primary" href="/docentes"><i class="bi bi-calendar2-event me-1"></i> Docentes</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-primary" href="/alunos"><i class="bi bi-graph-up-arrow me-1"></i> Alunos</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-primary" href="/turmas"><i class="bi bi-graph-up-arrow me-1"></i> Turmas</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-primary" href="/relatorios"><i class="bi bi-graph-up-arrow me-1"></i>
                    Relatórios</a>
            </li>
        </ul>

        <!-- Conteúdo principal -->
        <section class="container-fluid">

            <!-- Cabeçalho -->
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h2 class="fw-bold">Cursos</h2>
                    <p class="text-muted">Cadastre e gerencie cursos disponíveis</p>
                </div>
                <a class="text-end btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovoCurso"><i class="bi bi-plus-lg me-1"></i>Novo Curso</a>
            </div>

            <!-- Tabela de Cursos -->
            <div class="card shadow-sm rounded-4">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-muted">Curso</th>
                                <th class="text-muted">Tipo</th>
                                <th class="text-muted">Carga Horária</th>
                                <th class="text-muted">Vagas</th>
                                <th class="text-muted">Preço</th>
                                <th class="text-muted">Status</th>
                                <th class="text-center text-muted">Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <!-- Exemplo de linha (vai virar foreach) -->
                            @if ($ids->isEmpty())
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    Nenhum curso encontrado
                                </td>
                            </tr>
                            @else

                            @foreach ($ids as $curso)

                            <tr>

                                <td>
                                    <div class="fw-semibold">{{ $curso->nome }}</div>
                                    <small class="text-muted">{{$curso->horario}}</small>
                                </td>

                                <td>{{ $curso->tipo }}</td>
                                <td>{{ $curso->cargaHoraria }} </td>
                                <td>{{ $curso->vagas }} ({{ $curso->bolsas }} bolsas)</td>
                                <td>R$ {{ number_format($curso->preco, 2, ',', '.') }}</td>


                                <td>

                                    @if ($curso->situacao === 'ativo')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3">

                                        ativo
                                    </span>

                                    @else
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3">

                                        inativo
                                    </span>

                                    @endif
                                </td>

                                <td class="text-center">
                                    <a href="/editarCurso/{{ $curso->id }}" class="btn btn-sm btn-outline-dark me-1">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>

                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Modal Novo Curso -->
        <div class="modal fade" id="modalNovoCurso" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content rounded-4 border-0">

                    <!-- Header -->
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold">Novo Curso</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Form -->
                    <form action="/inserir" method="POST">

                        @csrf

                        <div class="modal-body">
                            <div class="row g-3">
                                <!-- Nome -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nome do Curso *</label>
                                    <input type="text" name="nome" class="form-control" placeholder="Ex: Técnico em Informática"
                                        required>
                                </div>

                                <!-- Sigla -->
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Sigla *</label>
                                    <input type="text" name="sigla" class="form-control text-uppercase" placeholder="TI, ADM..." maxlength="5"
                                        required>
                                </div>

                                <!-- Tipo -->
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Tipo *</label>
                                    <select name="tipo" class="form-select" required>
                                        <option value="">Selecione</option>
                                        <option value="tecnico">Técnico</option>
                                        <option value="graduacao">Graduação</option>
                                        <option value="livre">Curso Livre</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Carga Horária -->
                                <div class="col">
                                    <label class="form-label fw-semibold">Carga Horária (horas) *</label>
                                    <input type="number" name="cargaHoraria" class="form-control" min="0" value="0"
                                        required>
                                </div>

                                <!-- Turno -->
                                <div class="col">
                                    <label class="form-label fw-semibold">Turno *</label>
                                    <select name="turno" class="form-select" required>
                                        <option value="">Selecione o turno</option>
                                        <option value="manha">Manhã</option>
                                        <option value="tarde">Tarde</option>
                                        <option value="noite">Noite</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Horário -->
                                <div class="col">
                                    <label class="form-label fw-semibold">Horário *</label>
                                    <input type="text" name="horario" class="form-control" placeholder="08:00 - 12:00"
                                        required>
                                </div>

                                <!-- Data Início -->
                                <div class="col">
                                    <label class="form-label fw-semibold">Data de Início *</label>
                                    <input type="date" name="dataInicio" class="form-control" required>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Preço -->
                                <div class="col">
                                    <label class="form-label fw-semibold">Preço (R$) *</label>
                                    <input type="number" name="preco" class="form-control" min="0" step="0.01" value="0"
                                        required>
                                </div>

                                <!-- Vagas -->
                                <div class="col">
                                    <label class="form-label fw-semibold">Número de Vagas *</label>
                                    <input type="number" name="vagas" class="form-control" min="0" value="0" required>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Bolsas -->
                                <div class="col">
                                    <label class="form-label fw-semibold">Bolsas de Estudo</label>
                                    <input type="number" name="bolsas" class="form-control" min="0" value="0">
                                </div>

                                <!-- Status -->
                                <div class="col">
                                    <label class="form-label fw-semibold">Status</label>
                                    <select name="situacao" class="form-select">
                                        <option value="ativo" selected>Ativo</option>
                                        <option value="inativo">Inativo</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="modal-footer border-0 filter-tabs">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn-warning text-white px-4">
                                Salvar
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
        <!-- FIM DO MODAL -->
    </div>

</x-layout>