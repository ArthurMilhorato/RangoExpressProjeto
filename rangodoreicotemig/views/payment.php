<?php
$title = 'Pagamento PIX - Rango do Rei';
ob_start();
?>

<div class="card">
    <div class="card-header">
        <h1>💳 Pagamento via PIX</h1>
    </div>

    <div class="payment-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin: 20px 0;">
        <!-- Resumo do Pedido -->
        <div>
            <h3 style="color: #8B0000; margin-bottom: 20px;">📋 Resumo do Pedido</h3>
            
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <div style="display: flex; justify-content: space-between; padding: 10px; border-bottom: 1px solid #eee;">
                    <div>
                        <strong><?= htmlspecialchars($item['name']) ?></strong><br>
                        <small>Qtd: <?= $item['quantity'] ?></small>
                    </div>
                    <div>
                        <strong>R$ <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?></strong>
                    </div>
                </div>
            <?php endforeach; ?>

            <div style="background: #f8f9fa; padding: 15px; margin-top: 15px; border-radius: 5px;">
                <div style="display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: bold; color: #8B0000;">
                    <span>Total:</span>
                    <span>R$ <?= number_format($total, 2, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <!-- QR Code PIX -->
        <div style="text-align: center;">
            <h3 style="color: #8B0000; margin-bottom: 20px;">📱 Pague com PIX</h3>
            
            <div id="pix-container" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div id="loading" style="padding: 40px;">
                    <div style="color: #8B0000; font-size: 18px;">⏳ Gerando PIX...</div>
                </div>
                
                <div id="pix-content" style="display: none;">
                    <!-- QR Code do Mercado Pago -->
                    <div id="qr-code-container" style="margin-bottom: 20px;">
                        <img id="qr-code-image" style="max-width: 200px; border: 3px solid #DAA520; border-radius: 10px;">
                    </div>
                    
                    <p style="color: #666; margin-bottom: 20px;">
                        Escaneie o QR Code com seu app do banco ou copie o código PIX
                    </p>
                    
                    <!-- Código PIX -->
                    <div class="pix-key">
                        <strong>Código PIX:</strong><br>
                        <textarea id="pix-code" readonly style="width: 100%; height: 60px; font-size: 10px; resize: none; border: 1px solid #ddd; padding: 5px;"></textarea>
                        <button onclick="copyPixCode()" style="margin-top: 10px; padding: 5px 10px; background: #DAA520; color: white; border: none; border-radius: 3px; cursor: pointer;">
                            📋 Copiar Código PIX
                        </button>
                    </div>

                    <!-- Status do pagamento -->
                    <div id="payment-status" style="margin: 20px 0; padding: 10px; border-radius: 5px; background: #fff3cd; color: #856404;">
                        ⏳ Aguardando pagamento...
                    </div>
                </div>
                
                <div id="error-container" style="display: none; color: #dc3545; padding: 20px;">
                    ❌ Erro ao gerar PIX. Tente novamente.
                </div>
            </div>
        </div>
    </div>

    <!-- Botões de ação -->
    <div style="text-align: center; padding: 20px; border-top: 1px solid #eee;">
        <a href="/carrinho" class="btn btn-primary" style="margin-right: 15px;">← Voltar ao Carrinho</a>
        <button onclick="cancelPayment()" class="btn btn-danger">❌ Cancelar Pagamento</button>
    </div>

    <!-- Instruções -->
    <div class="payment-instructions">
        <h4 style="color: #004085; margin-bottom: 15px;">📝 Como pagar:</h4>
        <ol style="color: #004085;">
            <li>Abra o app do seu banco</li>
            <li>Escolha a opção PIX</li>
            <li>Escaneie o QR Code ou cole a chave PIX</li>
            <li>Confirme o valor: <strong>R$ <?= number_format($total, 2, ',', '.') ?></strong></li>
            <li>Finalize o pagamento</li>
            <li>Clique em "Confirmar Pagamento" após pagar</li>
        </ol>
    </div>
</div>

<script>
let paymentId = null;
let checkInterval = null;

// Gerar PIX ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    console.log('Página de pagamento carregada');
    generatePix();
});

// Gerar PIX via API do Mercado Pago
function generatePix() {
    console.log('Iniciando geração de PIX...');
    
    fetch('/api/create-pix', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na resposta do servidor');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            paymentId = data.payment_id;
            showPixData(data);
            startPaymentCheck();
        } else {
            console.error('Erro do servidor:', data.error || data.details);
            showError(data.error || 'Erro desconhecido');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showError('Erro de conexão: ' + error.message);
    });
}

// Mostrar dados do PIX
function showPixData(data) {
    document.getElementById('loading').style.display = 'none';
    document.getElementById('pix-content').style.display = 'block';
    
    // QR Code como imagem base64
    document.getElementById('qr-code-image').src = 'data:image/png;base64,' + data.qr_code_base64;
    
    // Código PIX
    document.getElementById('pix-code').value = data.qr_code;
    
    // Status inicial
    const statusDiv = document.getElementById('payment-status');
    statusDiv.innerHTML = '⏳ Aguardando pagamento...';
    statusDiv.style.background = '#fff3cd';
    statusDiv.style.color = '#856404';
}

// Mostrar erro
function showError(message) {
    document.getElementById('loading').style.display = 'none';
    document.getElementById('error-container').style.display = 'block';
    
    if (message) {
        document.getElementById('error-container').innerHTML = '❌ ' + message + '<br><button onclick="generatePix()" class="btn btn-primary" style="margin-top: 10px;">Tentar Novamente</button>';
    }
}

// Verificar status do pagamento
function startPaymentCheck() {
    checkInterval = setInterval(checkPaymentStatus, 3000); // Verifica a cada 3 segundos
}

function checkPaymentStatus() {
    if (!paymentId) return;
    
    fetch('/api/check-payment', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const statusDiv = document.getElementById('payment-status');
        
        if (data.status === 'approved') {
            statusDiv.innerHTML = '✅ Pagamento aprovado! Redirecionando...';
            statusDiv.style.background = '#d4edda';
            statusDiv.style.color = '#155724';
            
            clearInterval(checkInterval);
            setTimeout(() => {
                window.location.href = '/checkout';
            }, 2000);
        } else if (data.status === 'rejected') {
            statusDiv.innerHTML = '❌ Pagamento rejeitado';
            statusDiv.style.background = '#f8d7da';
            statusDiv.style.color = '#721c24';
            clearInterval(checkInterval);
        } else {
            statusDiv.innerHTML = '⏳ Aguardando pagamento...';
        }
    })
    .catch(error => {
        console.error('Erro ao verificar pagamento:', error);
    });
}

// Copiar código PIX
function copyPixCode() {
    const pixCode = document.getElementById('pix-code');
    pixCode.select();
    document.execCommand('copy');
    showAlert('Código PIX copiado!', 'success');
}


function cancelPayment() {
    if (confirm('Deseja cancelar o pagamento?')) {
        if (checkInterval) {
            clearInterval(checkInterval);
        }
        window.location.href = '/carrinho';
    }
}

// Limpar interval ao sair da página
window.addEventListener('beforeunload', function() {
    if (checkInterval) {
        clearInterval(checkInterval);
    }
});
</script>

<?php
$content = ob_get_clean();
include 'layout.php';
?>