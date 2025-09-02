<?php
$title = 'Admin - Rango do Rei';
ob_start();
?>

<div class="card">
    <div class="card-header">
        <h1>👑 Painel Administrativo</h1>
    </div>

    <!-- Gerenciar Itens -->
    <h2 style="color: #8B0000; margin: 30px 0 20px 0;">Gerenciar Cardápio</h2>
    
    <form method="POST" action="/admin/create-item" style="background: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 30px;">
        <h3>Adicionar Novo Item</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;">
            <div class="form-group">
                <label for="name">Nome:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="price">Preço:</label>
                <input type="number" id="price" name="price" class="form-control" step="0.01" required>
            </div>
        </div>
        <div class="form-group">
            <label for="description">Descrição:</label>
            <textarea id="description" name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label for="image">Imagem (nome do arquivo):</label>
            <input type="text" id="image" name="image" class="form-control" placeholder="ex: lanche.jpg">
        </div>
        <button type="submit" class="btn btn-success">Adicionar Item</button>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item->name) ?></td>
                    <td><?= htmlspecialchars($item->description) ?></td>
                    <td>R$ <?= number_format($item->price, 2, ',', '.') ?></td>
                    <td>
                        <button onclick="editItem(<?= $item->id ?>, '<?= addslashes($item->name) ?>', '<?= addslashes($item->description) ?>', <?= $item->price ?>, '<?= addslashes($item->image) ?>')" 
                                class="btn btn-primary" style="margin-right: 5px;">Editar</button>
                        <a href="/admin/delete-item?id=<?= $item->id ?>" 
                           onclick="return confirmDelete('Excluir este item?')" 
                           class="btn btn-danger">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Gerenciar Pedidos -->
    <h2 style="color: #8B0000; margin: 30px 0 20px 0;">Pedidos</h2>
    
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Status</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td>#<?= $order->id ?></td>
                    <td><?= htmlspecialchars($order->user_name) ?></td>
                    <td>R$ <?= number_format($order->total, 2, ',', '.') ?></td>
                    <td><span class="status status-<?= $order->status ?>"><?= ucfirst($order->status) ?></span></td>
                    <td><?= date('d/m/Y H:i', strtotime($order->created_at)) ?></td>
                    <td>
                        <form method="POST" action="/admin/update-order" style="display: inline;">
                            <input type="hidden" name="order_id" value="<?= $order->id ?>">
                            <select name="status" onchange="this.form.submit()">
                                <option value="pendente" <?= $order->status === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                                <option value="processado" <?= $order->status === 'processado' ? 'selected' : '' ?>>Processado</option>
                                <option value="entregue" <?= $order->status === 'entregue' ? 'selected' : '' ?>>Entregue</option>
                            </select>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal de Edição -->
<div id="editModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; margin: 15% auto; padding: 20px; border-radius: 10px; width: 80%; max-width: 500px;">
        <h3>Editar Item</h3>
        <form method="POST" action="/admin/update-item">
            <input type="hidden" id="edit-id" name="id">
            <div class="form-group">
                <label for="edit-name">Nome:</label>
                <input type="text" id="edit-name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="edit-description">Descrição:</label>
                <textarea id="edit-description" name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="edit-price">Preço:</label>
                <input type="number" id="edit-price" name="price" class="form-control" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="edit-image">Imagem:</label>
                <input type="text" id="edit-image" name="image" class="form-control">
            </div>
            <div style="text-align: right;">
                <button type="button" onclick="closeModal()" class="btn btn-primary" style="margin-right: 10px;">Cancelar</button>
                <button type="submit" class="btn btn-success">Salvar</button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>