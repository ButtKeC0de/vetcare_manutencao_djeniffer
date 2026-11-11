<?php
require_once __DIR__.'/../config/database.php';require_once __DIR__.'/../config/auth.php';exigirLogin();
$id=(int)($_GET['id']??0);$r=['animal_id'=>'','data_consulta'=>'','veterinario'=>'','motivo'=>'','status'=>'Agendada','observacoes'=>''];
if($id){$s=$pdo->prepare("SELECT * FROM consultas WHERE id=?");$s->execute([$id]);$r=$s->fetch()?:$r;}
$animais=$pdo->query("SELECT id,nome FROM animais ORDER BY nome")->fetchAll();$erro='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $data=$_POST['data_consulta']??'';$dt=str_replace('T',' ',$data).(strlen($data)===16?':00':'');
 if(trim($_POST['veterinario']??'')==='')$erro='Informe o veterinário.';
 else{
  $dados=[(int)$_POST['animal_id'],$dt,$_POST['veterinario'],$_POST['motivo'],$_POST['status'],$_POST['observacoes']];
  if($id){$s=$pdo->prepare("UPDATE consultas SET animal_id=?,data_consulta=?,veterinario=?,motivo=?,status=?,observacoes=? WHERE id=?");$s->execute(array_merge($dados,[$id]));}
  else{$s=$pdo->prepare("INSERT INTO consultas(animal_id,data_consulta,veterinario,motivo,status,observacoes) VALUES(?,?,?,?,?,?)");$s->execute($dados);}
  header('Location: index.php');exit;
 }
}
$titulo=$id?'Editar consulta':'Nova consulta';include __DIR__.'/../includes/header.php';?>
<h1><?=$titulo?></h1><section class="panel"><?php if($erro):?><div class="alert"><?=$erro?></div><?php endif;?>
<form method="post"><div class="field"><label>Animal</label><select name="animal_id" required><option value="">Selecione</option><?php foreach($animais as $a):?><option value="<?=$a['id']?>" <?=$r['animal_id']==$a['id']?'selected':''?>><?=htmlspecialchars($a['nome'])?></option><?php endforeach;?></select></div>
<div class="field"><label>Data e horário</label><input type="datetime-local" name="data_consulta" value="<?=$r['data_consulta']?date('Y-m-d\TH:i',strtotime($r['data_consulta'])):''?>" required></div>
<div class="field"><label>Veterinário *</label><input name="veterinario" value="<?=htmlspecialchars($r['veterinario'])?>" required></div>
<div class="field"><label>Motivo</label><input name="motivo" value="<?=htmlspecialchars($r['motivo'])?>"></div>
<div class="field"><label>Status</label><select name="status"><?php foreach(['Agendada','Realizada','Cancelada'] as $op):?><option <?=$r['status']===$op?'selected':''?>><?=$op?></option><?php endforeach;?></select></div>
<div class="field"><label>Observações</label><textarea name="observacoes"><?=htmlspecialchars($r['observacoes'])?></textarea></div>
<button>Salvar</button> <a class="btn secondary" href="index.php">Cancelar</a></form></section><?php include __DIR__.'/../includes/footer.php';?>
