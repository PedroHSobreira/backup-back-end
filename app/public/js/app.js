function simularEstado(tipo) {

    const card = document.getElementById('ucStatusCard');
    const dataAtual = document.getElementById('dataAtual');
    const btnAvaliar = document.getElementById('btnAvaliar');

    btnAvaliar.classList.add('d-none');

    if (tipo === 1) {
        card.className = 'alert alert-success';
        card.innerHTML = `
            <strong>UC em andamento</strong><br>
            63 dias restantes<br>
            <small>Período: 19/01/2026 até 23/03/2026</small>
        `;
        dataAtual.innerText = '19/01/2026';
    }

    if (tipo === 2) {
        card.className = 'alert alert-warning';
        card.innerHTML = `
            <strong>Atenção: Próximo ao fim</strong><br>
            Restam 3 dias - Avaliação liberada<br>
            <small>Período: 19/01/2026 até 23/03/2026</small>
        `;
        dataAtual.innerText = '20/03/2026';
        btnAvaliar.classList.remove('d-none');
    }

    if (tipo === 3) {
        card.className = 'alert alert-warning';
        card.innerHTML = `
            <strong>Atenção: Próximo ao fim</strong><br>
            Restam 0 dias - Avaliação liberada<br>
            <small>Período: 19/01/2026 até 23/03/2026</small>
        `;
        dataAtual.innerText = '23/03/2026';
        btnAvaliar.classList.remove('d-none');
    }

    if (tipo === 4) {
        card.className = 'alert alert-danger';
        card.innerHTML = `
            <strong>UC finalizada</strong><br>
            Finalizada há 5 dias<br>
            <small>Período: 19/01/2026 até 23/03/2026</small>
        `;
        dataAtual.innerText = '28/03/2026';
        btnAvaliar.classList.remove('d-none');
    }
}

function atualizarData(data) {
    const dias = ['domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado'];
    const meses = ['janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];

    const d = new Date(data + 'T00:00');
    const texto = `${dias[d.getDay()]}, ${d.getDate()} de ${meses[d.getMonth()]} de ${d.getFullYear()}`;

    document.getElementById('dataFormatada').innerText = texto;
}

const filterButtons = document.querySelectorAll('[data-filter]');
const blocos = document.querySelectorAll('.relacionamento');

filterButtons.forEach(button => {
    button.addEventListener('click', () => {

        // botão ativo
        filterButtons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');

        const tipo = button.dataset.filter;

        blocos.forEach(bloco => {
            if (bloco.dataset.tipo === tipo) {
                bloco.classList.remove('d-none');
            } else {
                bloco.classList.add('d-none');
            }
        });
    });
});

// function atualizarResumo() {
//     let presentes = document.querySelectorAll('input[value="P"]:checked').length;
//     let total = {{ count($alunos) }};
//     let faltas = total - presentes;
//     let percentual = Math.round((faltas / total) * 100);

//     document.getElementById('totalPresentes').innerText = presentes;
//     document.getElementById('totalFaltas').innerText = faltas;
//     document.getElementById('percentualFaltas').innerText = percentual + '%';
// }

document.getElementById('form-docente').addEventListener('submit', function (e) {
    const checkboxes = document.querySelectorAll('.turno-checkbox');
    let marcado = false;

    checkboxes.forEach(cb => {
        if (cb.checked) marcado = true;
    });

    if (!marcado) {
        e.preventDefault();

        let erro = document.getElementById('erro-turnos');

        if (!erro) {
            erro = document.createElement('div');
            erro.id = 'erro-turnos';
            erro.className = 'text-danger small mb-2';
            erro.innerText = 'Selecione pelo menos um turno.';
            checkboxes[0].closest('.lista-scroll').before(erro);
        }

        checkboxes.forEach(cb => cb.classList.add('is-invalid'));
    }
});

document.querySelectorAll('.btn-ver-turma').forEach(btn => {
    btn.addEventListener('click', function () {

        document.getElementById('modalTitulo').innerText =
            'Detalhes da Turma ' + this.dataset.nome;

        document.getElementById('modalCurso').innerText =
            this.dataset.curso;

        document.getElementById('modalStatus').innerText =
            this.dataset.status;

        document.getElementById('modalDocente').innerText =
            this.dataset.docente;

        document.getElementById('modalPeriodo').innerText =
            this.dataset.periodo;

        const alunos = JSON.parse(this.dataset.alunos);
        const lista = document.getElementById('listaAlunos');
        lista.innerHTML = '';

        document.getElementById('totalAlunos').innerText = alunos.length;

        alunos.forEach(aluno => {
            lista.innerHTML += `
                <div class="d-flex align-items-center bg-light rounded-3 p-3">
                    <div class="rounded-circle bg-warning text-white fw-bold
                        d-flex align-items-center justify-content-center me-3"
                        style="width: 40px; height: 40px;">
                        ${aluno.nome.charAt(0)}
                    </div>
                    <div>
                        <strong>${aluno.nome}</strong><br>
                        <small class="text-muted">RA: ${aluno.ra}</small>
                    </div>
                </div>
            `;
        });
    });
});


