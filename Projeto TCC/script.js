document.addEventListener('DOMContentLoaded', () => {
    
    const toggleThemeInput = document.getElementById('toggle-theme');

    if (!toggleThemeInput) {
        console.error('Campo de alternar tema não encontrado!');
        return;
    }

    
    toggleThemeInput.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        
        if (document.body.classList.contains('dark-mode')) {
            toggleThemeInput.value = '☀️ Tema Claro';
        } else {
            toggleThemeInput.value = '🌙 Tema Escuro';
        }
    });
});
