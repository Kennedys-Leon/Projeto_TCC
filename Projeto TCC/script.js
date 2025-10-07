document.addEventListener('DOMContentLoaded', () => {
    /* =========================
       TEMA ESCURO/CLARO
    ========================== */
    const toggleThemeHeader  = document.getElementById('toggle-theme');
    const toggleThemeSideBtn = document.getElementById('toggle-theme-sidebar');

    function getImgPath(img) {
        // Detecta se está em subpasta (ex: painel_vendedor) ou na raiz
        if (window.location.pathname.includes('painel_vendedor/')) {
            return `../img/${img}`;
        } else {
            return `img/${img}`;
        }
    }

    function setThemeLabels() {
        if (toggleThemeHeader) {
            const isDark = document.body.classList.contains('dark-mode');
            toggleThemeHeader.innerHTML = isDark
                ? `<img src="${getImgPath('sol.png')}" alt="Tema claro" style="width:22px; vertical-align:middle;">`
                : `<img src="${getImgPath('lua.png')}" alt="Tema escuro" style="width:22px; vertical-align:middle;">`;
        }
        // Botão da sidebar: igual ao anterior
        if (toggleThemeSideBtn) {
            const isDark = document.body.classList.contains('dark-mode');
            const icon = isDark
                ? `<img src="${getImgPath('sol.png')}" alt="Tema claro" style="width:18px; vertical-align:middle; margin-right:6px;">`
                : `<img src="${getImgPath('lua.png')}" alt="Tema escuro" style="width:18px; vertical-align:middle; margin-right:6px;">`;
            toggleThemeSideBtn.innerHTML = `
                ${icon}
                ${isDark ? ' Tema claro' : ' Tema escuro'}
            `;
        }
    }

    function toggleTheme() {
        // Adiciona um leve delay antes de alternar o tema
        setTimeout(() => {
            const isDark = document.body.classList.contains('dark-mode');
            if (isDark) {
                // Se estiver no modo escuro, remove com delay
                document.body.classList.remove('dark-mode');
            } else {
                // Se estiver no modo claro, adiciona com delay
                document.body.classList.add('dark-mode');
            }
            setThemeLabels();
            // (opcional) persistir tema
            localStorage.setItem('ma_theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
        }, 200); // 200ms de delay
    }

    // inicia tema (padrão dark + recupera preferido)
    const saved = localStorage.getItem('ma_theme');
    if (saved === 'light') document.body.classList.remove('dark-mode');
    else document.body.classList.add('dark-mode');
    setThemeLabels();

    if (toggleThemeHeader)  toggleThemeHeader.addEventListener('click', toggleTheme);
    if (toggleThemeSideBtn) toggleThemeSideBtn.addEventListener('click', toggleTheme);


    /* =========================
       CARRINHO (MODAL)
    ========================== */
    let cartItems = JSON.parse(localStorage.getItem('cart')) || [];

    function saveCart() {
        localStorage.setItem('cart', JSON.stringify(cartItems));
    }

    function updateCartCount() {
        const cartIcon = document.getElementById('cart-icon');
        if (!cartIcon) return;
        cartIcon.setAttribute('data-count', cartItems.length);
    }

    function updateCartModal() {
        const list = document.querySelector('.cart-items');
        const emptyMsg = document.getElementById('cart-empty-message');
        const totalEl = document.getElementById('cart-total-price');

        if (!list || !emptyMsg || !totalEl) return;

        list.innerHTML = '';
        if (cartItems.length === 0) {
            emptyMsg.style.display = 'block';
            totalEl.textContent = 'R$ 0,00';
            return;
        }

        emptyMsg.style.display = 'none';
        let total = 0;

        cartItems.forEach((item, idx) => {
            const li = document.createElement('li');
            li.textContent = `${item.nome} — R$ ${item.preco.toFixed(2)}`;

            const rm = document.createElement('button');
            rm.textContent = 'Remover';
            rm.style.marginLeft = '8px';
            rm.addEventListener('click', () => {
                cartItems.splice(idx, 1);
                saveCart();
                updateCartCount();
                updateCartModal();
            });

            li.appendChild(rm);
            list.appendChild(li);
            total += item.preco;
        });

        totalEl.textContent = `R$ ${total.toFixed(2)}`;
    }

    const cartIcon   = document.getElementById('cart-icon');
    const cartLink   = document.getElementById('open-cart'); // link da sidebar
    const cartModal  = document.getElementById('cart-modal');
    const closeBtn   = document.querySelector('.cart-close-btn');

    function openCart(e) {
        if (e) e.preventDefault();
        if (!cartModal) return;
        cartModal.style.display = 'block';
        updateCartModal();
    }

    if (cartIcon) cartIcon.addEventListener('click', openCart);
    if (cartLink) cartLink.addEventListener('click', openCart);

    if (closeBtn) {
        closeBtn.addEventListener('click', () => cartModal.style.display = 'none');
    }
    window.addEventListener('click', (e) => {
        if (e.target === cartModal) cartModal.style.display = 'none';
    });

    // Botões de "R$ 25,00 +" adicionam item
    document.querySelectorAll('.btn-preco').forEach(btn => {
        btn.addEventListener('click', () => {
            const card = btn.closest('.card-produto');
            if (!card) return;
            const nome  = card.querySelector('p')?.textContent || 'Produto';
            // pega o número do botão (ex.: "R$ 25,00 +")
            const preco = parseFloat(btn.textContent.replace(/[^\d,]/g,'').replace(',', '.')) || 0;
            cartItems.push({ nome, preco });
            saveCart();
            updateCartCount();
            Swal.fire('Adicionado!', `${nome} foi adicionado ao carrinho.`, 'success');
        });
    });

    updateCartCount();


    /* =========================
       SIDEBAR (abre/fecha)
    ========================== */
    const menuToggle   = document.getElementById('menu-toggle');
    const sidebar      = document.getElementById('sidebar');
    const closeSidebar = document.getElementById('close-sidebar');

    function openSidebar()  { if (sidebar) sidebar.classList.add('active'); }
    function closeSide()    { if (sidebar) sidebar.classList.remove('active'); }

    if (menuToggle)   menuToggle.addEventListener('click', openSidebar);
    if (closeSidebar) closeSidebar.addEventListener('click', closeSide);

    // Clicar fora fecha
    document.addEventListener('click', (e) => {
        if (!sidebar || !sidebar.classList.contains('active')) return;
        const clickInsideSidebar = sidebar.contains(e.target);
        const clickedToggle = e.target === menuToggle;
        if (!clickInsideSidebar && !clickedToggle) closeSide();
        
    });
    /* =========================
       MODO DALTÔNICO
    ========================== */
        /* =========================
       MODO DALTÔNICO (PRETO E BRANCO)
    ========================== */
        
    const daltonicoSidebar = document.getElementById('modo-daltonico-sidebar');

    if (daltonicoSidebar) {
        const isColorblind = localStorage.getItem('colorblind_mode') === 'true';
        if (isColorblind) {
            document.body.classList.add('colorblind-mode');
            daltonicoSidebar.setAttribute('aria-pressed', 'true');
            daltonicoSidebar.textContent = '👁️ Modo Normal';
        }

        daltonicoSidebar.addEventListener('click', () => {
            document.body.classList.toggle('colorblind-mode');
            const ativo = document.body.classList.contains('colorblind-mode');
            daltonicoSidebar.setAttribute('aria-pressed', ativo);
            daltonicoSidebar.textContent = ativo ? '👁️ Modo Normal' : '👁️ Daltonismo';
            localStorage.setItem('colorblind_mode', ativo);
        });
    }

    

    /* =========================
       ACESSIBILIDADE POR TECLADO + LEITURA DE CAMPOS
    ========================== */

    // Função que "fala" o texto em voz alta
    function falar(texto) {
        const sintese = window.speechSynthesis;
        const fala = new SpeechSynthesisUtterance(texto);
        fala.lang = 'pt-BR';
        sintese.speak(fala);
    }

    // Mapeia atalhos Alt + tecla → elemento + mensagem falada
    const atalhos = {
        '1': { seletor: '#search-input', texto: 'Campo de busca' },
        '2': { seletor: '#toggle-theme', texto: 'Botão de alternar tema' },
        '3': { seletor: '#modo-daltonico', texto: 'Botão modo daltônico' },
        '4': { seletor: '#cart-icon', texto: 'Ícone do carrinho de compras' },
        '5': { seletor: '#menu-toggle', texto: 'Botão do menu lateral' },
        '6': { seletor: '#toggle-theme-sidebar', texto: 'Botão de tema na barra lateral' },
        '7': { seletor: '#modo-daltonico-sidebar', texto: 'Botão modo daltônico na barra lateral' },
    };

    // Escuta combinações com Alt + número
    document.addEventListener('keydown', (event) => {
        if (event.altKey && atalhos[event.key]) {
            event.preventDefault();
            const { seletor, texto } = atalhos[event.key];
            const elemento = document.querySelector(seletor);
            if (elemento) {
                elemento.focus();
                falar(texto);
            }
        }
    });

    // Fala automaticamente o nome ao focar (Tab navega)
    document.querySelectorAll('a, button, input, select, textarea').forEach(el => {
        el.addEventListener('focus', () => {
            const texto = el.getAttribute('aria-label') || el.placeholder || el.textContent;
            if (texto) falar(texto.trim());
        });
    });

});
