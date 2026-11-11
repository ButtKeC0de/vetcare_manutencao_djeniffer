<?php
require_once __DIR__.'/../config/database.php';require_once __DIR__.'/../config/auth.php';exigirLogin();
$id=(int)($_GET['id']??0);$s=$pdo->prepare("SELECT * FROM chamados WHERE id=?");$s->execute([$id]);$c=$s->fetch();
if(!$c){http_response_code(404);die('Chamado não encontrado.');}
$titulo='Chamado #'.$id;$erro='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $autor=trim($_POST['autor']??'');$comentario=trim($_POST['comentario']??'');$novo=$_POST['status']??$c['status'];$responsavel=trim($_POST['responsavel']??'');
 if($autor===''||$comentario==='')$erro='Informe seu nome e o comentário.';
 else{
  $pdo->beginTransaction();
  $u=$pdo->prepare("UPDATE chamados SET status=?,responsavel=? WHERE id=?");$u->execute([$novo,$responsavel,$id]);
  $h=$pdo->prepare("INSERT INTO historico_chamados(chamado_id,autor,comentario,status_anterior,status_novo) VALUES(?,?,?,?,?)");
  $h->execute([$id,$autor,$comentario,$c['status'],$novo]);
  $pdo->commit();header('Location: ver.php?id='.$id);exit;
 }
}
$h=$pdo->prepare("SELECT * FROM historico_chamados WHERE chamado_id=? ORDER BY criado_em DESC");$h->execute([$id]);$historico=$h->fetchAll();
include __DIR__.'/../includes/header.php';
?>
<h1>Chamado #<?=str_pad($id,4,'0',STR_PAD_LEFT)?></h1>
<?php if(isset($_GET['novo'])):?><div class="alert success">Chamado registrado com sucesso.</div><?php endif;?>
<div class="actions"><a class="btn secondary" href="index.php">← Voltar à central</a></div>
<section class="panel"><div class="grid"><div><span class="muted">Status</span><h2><?=htmlspecialchars($c['status'])?></h2></div><div><span class="muted">Prioridade</span><h2><?=htmlspecialchars($c['prioridade'])?></h2></div><div><span class="muted">Severidade</span><h2><?=htmlspecialchars($c['severidade'])?></h2></div><div><span class="muted">Módulo</span><h2><?=htmlspecialchars($c['modulo'])?></h2></div></div>
<h2><?=htmlspecialchars($c['titulo'])?></h2>
<p><b>Solicitante:</b> <?=htmlspecialchars($c['solicitante'])?> &nbsp; <b>Equipe:</b> <?=htmlspecialchars($c['equipe']?:'Não informada')?></p>
<p><b>Responsável:</b> <?=htmlspecialchars($c['responsavel']?:'Não atribuído')?></p>
<p><b>Descrição</b><br><?=nl2br(htmlspecialchars($c['descricao']))?></p>
<p><b>Passos para reprodução</b><br><?=nl2br(htmlspecialchars($c['passos']))?></p>
<p><b>Resultado esperado</b><br><?=nl2br(htmlspecialchars($c['resultado_esperado']))?></p>
<p><b>Resultado obtido</b><br><?=nl2br(htmlspecialchars($c['resultado_obtido']))?></p>
<p class="muted">Criado em <?=date('d/m/Y H:i',strtotime($c['criado_em']))?> • Atualizado em <?=date('d/m/Y H:i',strtotime($c['atualizado_em']))?></p>
</section>
<section class="panel"><h2>Atualizar atendimento / adicionar comentário</h2>
<?php if($erro):?><div class="alert"><?=htmlspecialchars($erro)?></div><?php endif;?>
<form method="post"><div class="grid">
<div class="field"><label>Seu nome *</label><input name="autor" required></div>
<div class="field"><label>Responsável pelo chamado</label><input name="responsavel" value="<?=htmlspecialchars($c['responsavel']??'')?>"></div>
<div class="field"><label>Novo status</label><select name="status"><?php foreach(['Aberto','Em análise','Aguardando informações','Resolvido','Fechado'] as $v):?><option <?=$c['status']===$v?'selected':''?>><?=$v?></option><?php endforeach;?></select></div>
</div><div class="field"><label>Comentário / ação realizada *</label><textarea name="comentario" placeholder="Registre análise, solicitação de informação, solução aplicada ou teste de validação..." required></textarea></div><button>Salvar atualização</button></form>
</section>
<section class="panel"><h2>Histórico do chamado</h2>
<?php if(!$historico):?><p class="muted">Ainda não há atualizações.</p><?php endif;?>
<?php foreach($historico as $item):?><div style="border-left:3px solid #126c79;padding:8px 15px;margin:12px 0"><b><?=htmlspecialchars($item['autor'])?></b> <span class="muted">• <?=date('d/m/Y H:i',strtotime($item['criado_em']))?></span><p><?=nl2br(htmlspecialchars($item['comentario']))?></p><?php if($item['status_novo']):?><span class="tag"><?=htmlspecialchars($item['status_anterior']?:'Novo')?> → <?=htmlspecialchars($item['status_novo'])?></span><?php endif;?></div><?php endforeach;?>
</section>
<?php include __DIR__.'/../includes/footer.php';?>
