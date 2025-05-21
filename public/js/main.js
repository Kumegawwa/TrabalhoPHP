document.addEventListener('DOMContentLoaded', () => {
    // Confirmação para exclusão
    const deletar = document.querySelectorAll('.btn-danger[data-confirm]');
    deletar.forEach(btn => {
        btn.addEventListener('click', (e) => {
            if (!confirm('Tem certeza que deseja excluir?')) {
                e.preventDefault();
            }
        });
    });

    // Validação simples de formulário
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            const inputs = form.querySelectorAll('input[required], textarea[required]');
            let valid = true;
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.style.borderColor = 'red';
                    valid = false;
                } else {
                    input.style.borderColor = '#ccc';
                }
            });
            if (!valid) {
                e.preventDefault();
                alert('Por favor, preencha todos os campos obrigatórios.');
            }
        });
    });
});
