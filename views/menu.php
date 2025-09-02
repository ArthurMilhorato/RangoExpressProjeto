<?php
$title = 'Cardápio - Rango do Rei';
ob_start();
?>

<div class="card">
    <div class="card-header">
        <h1>👑 Cardápio Real</h1>
    </div>

    <div class="grid">
        <?php foreach ($items as $item): ?>
            <div class="item-card">
                <div class="item-image">
                    <?php if ($item->image): ?>
                        <img src="/public/images/<?= $item->image ?>" alt="<?= $item->name ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        🍽️
                    <?php endif; ?>
                </div>
                
                <div class="item-content">
                    <h3 class="item-title"><?= htmlspecialchars($item->name) ?></h3>
                    <p class="item-description"><?= htmlspecialchars($item->description) ?></p>
                    <div class="item-price">R$ <?= number_format($item->price, 2, ',', '.') ?></div>
                    
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <input type="number" id="quantity-<?= $item->id ?>" value="1" min="1" max="10" 
                               style="width: 60px; padding: 5px; border: 1px solid #ddd; border-radius: 3px;">
                        <button onclick="addToCart(<?= $item->id ?>)" class="btn btn-success">
                            Adicionar ao Carrinho
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($items)): ?>
        <p style="text-align: center; color: #666; padding: 40px;">
            Nenhum item disponível no momento.
        </p>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>