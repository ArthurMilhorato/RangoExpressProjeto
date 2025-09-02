<?php
$title = 'Carrinho - Rango do Rei';
ob_start();
?>

<div class="card">
    <div class="card-header">
        <h1>🛒 Seu Carrinho</h1>
    </div>

    <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
        <?php $total = 0; ?>
        <?php foreach ($_SESSION['cart'] as $itemId => $item): ?>
            <div class="cart-item" id="cart-item-<?= $itemId ?>">
                <div style="flex: 1;">
                    <h4><?= htmlspecialchars($item['name']) ?></h4>
                    <p style="color: #666; margin: 5px 0;">Preço unitário: R$ <?= number_format($item['price'], 2, ',', '.') ?></p>
                </div>
                
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <button onclick="updateQuantity(<?= $itemId ?>, -1)" class="btn-quantity">-</button>
                        <input type="number" id="quantity-<?= $itemId ?>" value="<?= $item['quantity'] ?>" 
                               min="1" max="10" style="width: 50px; text-align: center; padding: 5px; border: 1px solid #ddd; border-radius: 3px;"
                               onchange="changeQuantity(<?= $itemId ?>, this.value)">
                        <button onclick="updateQuantity(<?= $itemId ?>, 1)" class="btn-quantity">+</button>
                    </div>
                    
                    <div style="min-width: 80px; text-align: right;">
                        <strong id="item-total-<?= $itemId ?>">R$ <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?></strong>
                    </div>
                    
                    <button onclick="removeFromCart(<?= $itemId ?>)" class="btn-remove" title="Remover item">
                        ❌
                    </button>
                </div>
            </div>
            <?php $total += $item['price'] * $item['quantity']; ?>
        <?php endforeach; ?>

        <div class="cart-total">
            Total: R$ <?= number_format($total, 2, ',', '.') ?>
        </div>

        <div style="text-align: center; padding: 20px;">
            <a href="/cardapio" class="btn btn-primary" style="margin-right: 10px;">Continuar Comprando</a>
            <a href="/pagamento" class="btn btn-success">Ir para Pagamento</a>
        </div>
    <?php else: ?>
        <p style="text-align: center; color: #666; padding: 40px;">
            Seu carrinho está vazio.
        </p>
        <div style="text-align: center;">
            <a href="/cardapio" class="btn btn-primary">Ver Cardápio</a>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>