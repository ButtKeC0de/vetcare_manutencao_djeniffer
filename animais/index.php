<?php
require_once __DIR__.'/../config/database.php';require_once __DIR__.'/../config/auth.php';exigirLogin();
$titulo='Animais';
$rows=$pdo->query("SELECT a.*,t.nome AS tutor FROM animais a JOIN tutores t ON t.id=a.tutor_id ORDER BY a.nome")->fetchAll();
include __DIR__.'/../includes/header.php';?>
<h1>Animais</h1><p class="muted">Pacientes cadastrados na clínica.</p><div class="actions"><a class="btn" href="form.php">+ Novo animal</a></div>
<div class="panel table-wrap"><table><tr><th>Nome</th><th>Espécie</th><th>Raça</th><th>Tutor</th><th>Nascimento</th><th>Idade</th><th>Ações</th></tr>
<?php foreach($rows as $r): $idade=$r['nascimento']?date('Y')-(int)date('Y',strtotime($r['nascimento'])):0; ?>
<tr><td><?=htmlspecialchars($r['nome'])?></td><td><?=htmlspecialchars($r['especie'])?></td><td><?=htmlspecialchars($r['raca'])?></td><td><?=htmlspecialchars($r['tutor'])?></td><td><?=$r['nascimento']?date('d/m/Y',strtotime($r['nascimento'])):'-'?></td><td><?=$idade?> ano(s)</td><td><a href="form.php?id=<?=$r['id']?>">Editar</a> | <a href="excluir.php?id=<?=$r['id']?>">Excluir</a></td></tr><?php endforeach;?>
</table></div><?php include __DIR__.'/../includes/footer.php';?>
