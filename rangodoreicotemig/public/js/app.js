// Função para adicionar item ao carrinho
function addToCart(itemId) {
    const quantity = document.getElementById(`quantity-${itemId}`).value;
    
    fetch('/add-to-cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `item_id=${itemId}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Item adicionado ao carrinho!', 'success');
            updateCartCount();
        } else {
            showAlert('Erro ao adicionar item', 'error');
        }
    })
    .catch(error => {
        showAlert('Erro de conexão', 'error');
    });
}

// Função para mostrar alertas
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    
    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}

// Função para atualizar contador do carrinho
function updateCartCount() {
    // Implementar se necessário
}

// Remover item do carrinho
function removeFromCart(itemId) {
    if (!confirm('Remover este item do carrinho?')) {
        return;
    }
    
    fetch('/remove-from-cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `item_id=${itemId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Item removido do carrinho!', 'success');
            // Recarregar página para atualizar carrinho
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showAlert('Erro ao remover item', 'error');
        }
    })
    .catch(error => {
        showAlert('Erro de conexão', 'error');
    });
}

// Atualizar quantidade do item
function updateQuantity(itemId, change) {
    const quantityInput = document.getElementById(`quantity-${itemId}`);
    let newQuantity = parseInt(quantityInput.value) + change;
    
    if (newQuantity < 1) newQuantity = 1;
    if (newQuantity > 10) newQuantity = 10;
    
    quantityInput.value = newQuantity;
    changeQuantity(itemId, newQuantity);
}

// Alterar quantidade do item
function changeQuantity(itemId, quantity) {
    if (quantity < 1 || quantity > 10) {
        showAlert('Quantidade deve ser entre 1 e 10', 'error');
        return;
    }
    
    fetch('/update-cart-quantity', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `item_id=${itemId}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateItemTotal(itemId);
            updateCartTotal();
        } else {
            showAlert('Erro ao atualizar quantidade', 'error');
        }
    })
    .catch(error => {
        showAlert('Erro de conexão', 'error');
    });
}

// Atualizar total do item
function updateItemTotal(itemId) {
    // Esta função seria implementada se tivéssemos o preço disponível no frontend
    // Por simplicidade, vamos recarregar a página
    setTimeout(() => {
        window.location.reload();
    }, 500);
}

// Atualizar total do carrinho
function updateCartTotal() {
    // Recarregar página para atualizar totais
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}

// Função para confirmar exclusão
function confirmDelete(message) {
    return confirm(message || 'Tem certeza que deseja excluir?');
}

// Validação de formulários
document.addEventListener('DOMContentLoaded', function() {
    // Validação de email
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('blur', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                this.style.borderColor = '#dc3545';
                showAlert('Email inválido', 'error');
            } else {
                this.style.borderColor = '#ddd';
            }
        });
    });
    
    // Validação de preço
    const priceInputs = document.querySelectorAll('input[name="price"]');
    priceInputs.forEach(input => {
        input.addEventListener('input', function() {
            let value = this.value.replace(/[^0-9.,]/g, '');
            value = value.replace(',', '.');
            this.value = value;
        });
    });
    
    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});

// Função para modal de edição de item (admin)
function editItem(id, name, description, price, image) {
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-description').value = description;
    document.getElementById('edit-price').value = price;
    document.getElementById('edit-image').value = image;
    
    document.getElementById('editModal').style.display = 'block';
}

// Função para fechar modal
function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Fechar modal clicando fora
window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}

