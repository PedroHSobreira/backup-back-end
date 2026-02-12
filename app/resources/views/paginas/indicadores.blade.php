<X-layout>
    <div class="container-xl py-4 shadow">
        <main class="container-xl py-4 shadow ">
            <!-- Abas -->
            <ul class="nav nav-pills gap-2 mb-4">
                <li class="nav-item">
                    <a class="btn btn-primary" href="dashboardAdm"><i class="bi bi-bar-chart"></i></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-primary" href="cursos"><i class="bi bi-backpack"></i> Cursos</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-primary " href="unidadesCurriculares"><i class="bi bi-book"></i> UCs</a>
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
                    <a class="btn btn-primary" href="aulas"><i class="bi bi-file-bar-graph"></i>
                        Aulas</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-primary active" href="indicadores"><i class="bi bi-card-list"></i>
                        Indicadores</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-primary" href="relatorios"><i class="bi bi-clipboard-data"></i>
                        Relatórios</a>
                </li>
            </ul>

            <!-- Conteúdo principal -->
            <section class="container-fluid">

                <!-- Cabeçalho -->
                <div class="d-flex align-items-center justify-content-between  mb-3">
                    <div>
                        <h2 class="fw-bold ">Indicadores</h2>
                        <p class="text-muted">Editar os indicadores de avaliação e suas UCs relacionadas</p>
                    </div>
                    <a href="/indicadores.html" class="text-end btn btn-primary">
                        <i class="bi bi-arrow-left me-1"></i> Voltar
                    </a>
                </div>

                <form action="../atualizar/{{$dado->id}}" method="POST">

                    <div class="modal-body">

                        <!-- Nome -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Nome do Indicador *
                            </label>
                            <input type="text" name="nome" class="form-control rounded-3"
                                placeholder="Ex: Competência em Programação" value="{{$dado->nome}}" required>
                        </div>

                        <!-- Descrição -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Descrição *
                            </label>
                            <textarea name="descricao" rows="4" class="form-control rounded-3"
                                placeholder="Descreva o que este indicador avalia..." style="resize: none;" required
                                value="{{$dado->descricao}}"></textarea>
                        </div>

                        <!-- UCs -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Unidades Curriculares *
                                <small class="text-muted">(múltipla seleção)</small>
                            </label>

                            <div class="border rounded-3 p-3 lista-scroll">

                                @foreach($cursos as $curso)
                                <div class="mb-3">

                                    <!-- Nome do Curso -->
                                    <div class="fw-semibold text-secondary mb-2">
                                        {{ $curso->nome }}
                                    </div>

                                    <!-- UCs do Curso -->
                                    @foreach($curso->ucs as $uc)
                                    <div class="form-check mb-2 ms-3">
                                        <input class="form-check-input" type="checkbox" name="ucs[]" value="{{ $uc->id }}"
                                            id="uc{{ $uc->id }}" {{ $dado->uc->contains($uc->id) ? 'checked' : ''
                                    }}>

                                        <label class="form-check-label" for="uc{{ $uc->id }}">
                                            {{ $uc->nome }}
                                        </label>
                                    </div>
                                    @endforeach

                                </div>
                                @endforeach

                            </div>
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="modal-footer border-0 filter-tabs mt-3 gap-3">
                        <button type="button" class="btn btn-danger text-white px-4" data-bs-toggle="modal"
                            data-bs-target="#modalExcluirIndicardores">
                            Excluir
                        </button>
                        <button type="submit" class="btn btn-warning text-white px-4">
                            Salvar
                        </button>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="modalExcluirIndicadores" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Excluir</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Tem certeza que deseja excluir o compromisso: {{$dado->nome}}?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Não</button>
                                    <a type="button" class="btn btn-danger" href="/excluir/{{$dado->id}}">Sim</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </section>

        </main>

    </div>
    <x-layout>