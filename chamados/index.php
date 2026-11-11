<?php
require_once __DIR__.'/../config/database.php';require_once __DIR__.'/../config/auth.php';exigirLogin();
$titulo='Central de suporte';
$status=$_GET['status']??'';$modulo=$_GET['modulo']??'';
$sql="SELECT * FROM chamados WHERE 1=1";$params=[];
if($status!==''){$sql.=" AND status=?";$params[]=$status;}
if($modulo!==''){$sql.=" AND modulo=?";$params[]=$modulo;}
$sql.=" ORDER BY FIELD(prioridade,'Urgente','Alta','Média','Baixa'), criado_em DESC";
$stmt=$pdo->prepare($sql);$stmt->execute($params);$rows=$stmt->fetchAll();
$counts=$pdo->query("SELECT status,COUNT(*) total FROM chamados GROUP BY status")->fetchAll(PDO::FETCH_KEY_PAIR);
include __DIR__.'/../includes/header.php';
?>
<h1>Central de suporte</h1>
<p class="muted">Registre incidentes encontrados no VetCare e acompanhe o atendimento.</p>
<div class="grid">
 <div class="stat"><span>Chamados abertos</span><strong><?= (int)($counts['Aberto']??0) ?></strong></div>
 <div class="stat"><span>Em análise</span><strong><?= (int)($counts['Em análise']??0) ?></strong></div>
 <div class="stat"><span>Aguardando informações</span><strong><?= (int)($counts['Aguardando informações']??0) ?></strong></div>
 <div class="stat"><span>Resolvidos</span><strong><?= (int)($counts['Resolvido']??0) ?></strong></div>
</div>
<div class="actions"><a class="btn" href="novo.php">+ Abrir chamado</a></div>
<section class="panel">
<h2>Consultar chamados</h2>
<form method="get" class="grid">
 <div class="field"><label>Status</label><select name="status"><option value="">Todos</option><?php foreach(['Aberto','Em análise','Aguardando informações','Resolvido','Fechado'] as $v):?><option value="<?=htmlspecialchars($v)?>" <?=$status===$v?'selected':''?>><?=htmlspecialchars($v)?></option><?php endforeach;?></select></div>
 <div class="field"><label>Módulo</label><select name="modulo"><option value="">Todos</option><?php foreach(['Login','Dashboard','Tutores','Animais','Consultas','Outro'] as $v):?><option <?=$modulo===$v?'selected':''?>><?=$v?></option><?php endforeach;?></select></div>
 <div class="field" style="align-self:end"><button>Filtrar</button> <a class="btn secondary" href="index.php">Limpar</a></div>
</form></section>
<section class="panel table-wrap"><table>
<tr><th>Nº</th><th>Título</th><th>Módulo</th><th>Prioridade</th><th>Status</th><th>Aberto em</th><th></th></tr>
<?php if(!$rows):?><tr><td colspan="7" class="empty">Nenhum chamado encontrado. Abra o primeiro chamado da equipe.</td></tr><?php endif;?>
<?php foreach($rows as $r):?><tr>
<td>#<?=str_pad($r['id'],4,'0',STR_PAD_LEFT)?></td><td><?=htmlspecialchars($r['titulo'])?></td><td><?=htmlspecialchars($r['modulo'])?></td><td><span class="tag"><?=htmlspecialchars($r['prioridade'])?></span></td><td><span class="tag"><?=htmlspecialchars($r['status'])?></span></td><td><?=date('d/m/Y H:i',strtotime($r['criado_em']))?></td><td><a href="ver.php?id=<?=$r['id']?>">Detalhes</a></td>
</tr><?php endforeach;?>
</table></section>
<?php include __DIR__.'/../includes/footer.php';?>
