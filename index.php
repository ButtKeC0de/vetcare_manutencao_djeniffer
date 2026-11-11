<?php
require_once __DIR__.'/config/database.php';
require_once __DIR__.'/config/auth.php';
exigirLogin();
$titulo='Painel';
$tutores=(int)$pdo->query("SELECT COUNT(*) FROM tutores WHERE ativo=1")->fetchColumn();
$animais=(int)$pdo->query("SELECT COUNT(*) FROM animais")->fetchColumn(); 
$consultas=(int)$pdo->query("SELECT COUNT(*) FROM consultas WHERE status='Agendada'")->fetchColumn();
$proximas=$pdo->query("SELECT c.*, a.nome AS animal FROM consultas c JOIN animais a ON a.id=c.animal_id ORDER BY c.data_consulta LIMIT 5")->fetchAll();
include __DIR__.'/includes/header.php';
?>
<h1>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></h1>
<p class="muted">Visão geral da clínica veterinária.</p>
<div class="grid">
 <div class="stat"><span>Tutores ativos</span><strong><?= $tutores ?></strong></div>
 <div class="stat"><span>Animais cadastrados</span><strong><?= $animais ?></strong></div>
 <div class="stat"><span>Consultas agendadas</span><strong><?= $consultas ?></strong></div>
</div>
<section class="panel"><h2>Próximas consultas</h2>
<div class="table-wrap"><table><tr><th>Animal</th><th>Data</th><th>Veterinário</th><th>Status</th></tr>
<?php foreach($proximas as $c): ?><tr><td><?= htmlspecialchars($c['animal']) ?></td><td><?= date('d/m/Y H:i',strtotime($c['data_consulta'])) ?></td><td><?= htmlspecialchars($c['veterinario']) ?></td><td><span class="tag"><?= htmlspecialchars($c['status']) ?></span></td></tr><?php endforeach; ?>
</table></div></section>
<?php include __DIR__.'/includes/footer.php'; ?>
