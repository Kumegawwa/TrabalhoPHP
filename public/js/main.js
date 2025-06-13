document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.getElementById('menu-toggle');
    const hamburger = document.querySelector('.hamburger');
    
    if (hamburger && menuToggle) {
        hamburger.addEventListener('click', () => {
            document.body.classList.toggle('menu-open');
        });
    }
    
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                e.preventDefault();
                const parent = toggle.parentElement;
                parent.classList.toggle('active');
            }
        });
    });
    
    const deleteBtns = document.querySelectorAll('.btn-danger[data-confirm]');
    
    deleteBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            if (!confirm('Tem certeza que deseja excluir?')) {
                e.preventDefault();
            }
        });
    });
    
    const forms = document.querySelectorAll('form[data-validate]');
    
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            const requiredInputs = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredInputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                    
                    let errorMessage = input.nextElementSibling;
                    if (!errorMessage || !errorMessage.classList.contains('error-message')) {
                        errorMessage = document.createElement('div');
                        errorMessage.className = 'error-message';
                        errorMessage.textContent = 'Este campo é obrigatório';
                        input.parentNode.insertBefore(errorMessage, input.nextSibling);
                    }
                } else {
                    input.classList.remove('is-invalid');
                    
                    const errorMessage = input.nextElementSibling;
                    if (errorMessage && errorMessage.classList.contains('error-message')) {
                        errorMessage.remove();
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                const firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
        
        const inputs = form.querySelectorAll('[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', () => {
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    
                    let errorMessage = input.nextElementSibling;
                    if (!errorMessage || !errorMessage.classList.contains('error-message')) {
                        errorMessage = document.createElement('div');
                        errorMessage.className = 'error-message';
                        errorMessage.textContent = 'Este campo é obrigatório';
                        input.parentNode.insertBefore(errorMessage, input.nextSibling);
                    }
                } else {
                    input.classList.remove('is-invalid');
                    
                    const errorMessage = input.nextElementSibling;
                    if (errorMessage && errorMessage.classList.contains('error-message')) {
                        errorMessage.remove();
                    }
                }
            });
            
            input.addEventListener('input', () => {
                if (input.value.trim()) {
                    input.classList.remove('is-invalid');
                    
                    const errorMessage = input.nextElementSibling;
                    if (errorMessage && errorMessage.classList.contains('error-message')) {
                        errorMessage.remove();
                    }
                }
            });
        });
    });
    
    const loadingBtns = document.querySelectorAll('.btn[data-loading]');
    
    loadingBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const originalText = btn.innerHTML;
            
            btn.classList.add('btn-loading');
            btn.innerHTML = '<span>' + originalText + '</span>';
            
            btn.disabled = true;
            

            setTimeout(() => {
                btn.classList.remove('btn-loading');
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 2000);
        });
    });
    
    const buttons = document.querySelectorAll('.btn');
    
    buttons.forEach(btn => {
        btn.addEventListener('mousedown', () => {
            btn.style.transform = 'scale(0.98)';
        });
        
        btn.addEventListener('mouseup', () => {
            btn.style.transform = '';
        });
        
        btn.addEventListener('mouseleave', () => {
            btn.style.transform = '';
        });
    });
    
    const fadeElements = document.querySelectorAll('.fade-in');
    
    fadeElements.forEach(element => {
        element.style.opacity = '0';
        
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'fadeIn 0.5s forwards';
                    observer.unobserve(entry.target);
                }
            });
        });
        
        observer.observe(element);
    });
});
