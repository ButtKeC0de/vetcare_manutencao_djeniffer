<?php
require_once __DIR__.'/../config/database.php';require_once __DIR__.'/../config/auth.php';exigirLogin();
$titulo='Consultas';$status=$_GET['status']??'';
$sql="SELECT c.*,a.nome AS animal FROM consultas c JOIN animais a ON a.id=c.animal_id";
$rows=$pdo->query($sql." ORDER BY c.data_consulta DESC")->fetchAll(); 
include __DIR__.'/../includes/header.php';?>
<h1>Consultas</h1><p class="muted">Agenda e histórico de atendimentos.</p><div class="actions"><a class="btn" href="form.php">+ Nova consulta</a></div>
<section class="panel"><form method="get"><div class="field"><label>Filtrar por status</label><select name="status"><option value="">Todos</option><?php foreach(['Agendada','Realizada','Cancelada'] as $op):?><option value="<?=$op?>" <?=$status===$op?'selected':''?>><?=$op?></option><?php endforeach;?></select></div><button>Filtrar</button></form></section>
<div class="panel table-wrap"><table><tr><th>Animal</th><th>Data</th><th>Veterinário</th><th>Motivo</th><th>Status</th><th>Ações</th></tr>
<?php foreach($rows as $r):?><tr><td><?=htmlspecialchars($r['animal'])?></td><td><?=date('d/m/Y H:i',strtotime($r['data_consulta']))?></td><td><?=htmlspecialchars($r['veterinario'])?></td><td><?=htmlspecialchars($r['motivo'])?></td><td><?=htmlspecialchars($r['status'])?></td><td><a href="form.php?id=<?=$r['id']?>">Editar</a> | <a href="excluir.php?id=<?=$r['id']?>">Excluir</a></td></tr><?php endforeach;?>
</table></div><?php include __DIR__.'/../includes/footer.php';?>
