// Carrinho
let cartItems = JSON.parse(localStorage.getItem('cart')) || [];

function updateCartCount() {
    const cartIcon = document.getElementById('cart-icon');
    const count = cartItems.length;
    cartIcon.setAttribute('data-count', count);
}

function updateCartModal() {
    const cartList = document.querySelector('.cart-items');
    const emptyMessage = document.getElementById('cart-empty-message');
    const totalPriceEl = document.getElementById('cart-total-price');

    cartList.innerHTML = '';
    if (cartItems.length === 0) {
        emptyMessage.style.display = 'block';
        totalPriceEl.textContent = "R$ 0,00";
    } else {
        emptyMessage.style.display = 'none';
        let total = 0;
        cartItems.forEach((item, index) => {
            const li = document.createElement('li');
            li.textContent = `${item.nome} - R$ ${item.preco.toFixed(2)}`;
            const btnRemove = document.createElement('button');
            btnRemove.textContent = "Remover";
            btnRemove.onclick = () => {
                cartItems.splice(index, 1);
                saveCart();
                updateCartCount();
                updateCartModal();
            };
            li.appendChild(btnRemove);
            cartList.appendChild(li);
            total += item.preco;
        });
        totalPriceEl.textContent = `R$ ${total.toFixed(2)}`;
    }
}

function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cartItems));
}

// Modal do carrinho
const cartIcon = document.getElementById('cart-icon');
const cartModal = document.getElementById('cart-modal');
const cartCloseBtn = document.querySelector('.cart-close-btn');

cartIcon.addEventListener('click', (e) => {
    e.preventDefault();
    cartModal.style.display = "block";
    updateCartModal();
});

cartCloseBtn.addEventListener('click', () => {
    cartModal.style.display = "none";
});

window.addEventListener('click', (event) => {
    if (event.target === cartModal) {
        cartModal.style.display = "none";
    }
});

// Botões de preço adicionam item ao carrinho
document.querySelectorAll('.btn-preco').forEach(btn => {
    btn.addEventListener('click', () => {
        const card = btn.closest('.card-produto');
        const nome = card.querySelector('p').textContent;
        const preco = parseFloat(btn.textContent.replace("R$", "").replace(",", "."));
        cartItems.push({ nome, preco });
        saveCart();
        updateCartCount();
        Swal.fire('Adicionado!', `${nome} foi adicionado ao carrinho.`, 'success');
    });
});

// Tema claro/escuro
const toggleTheme = document.getElementById('toggle-theme');
toggleTheme.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');
    toggleTheme.value = document.body.classList.contains('dark-mode') ? "☾" : "☼︎";
});

// Atualizar ao carregar
updateCartCount();
