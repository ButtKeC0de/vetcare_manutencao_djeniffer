<?php
require_once __DIR__.'/../config/database.php';require_once __DIR__.'/../config/auth.php';exigirLogin();
$id=(int)($_GET['id']??0);$r=['tutor_id'=>'','nome'=>'','especie'=>'','raca'=>'','nascimento'=>'','sexo'=>'','observacoes'=>'','ativo'=>1];
if($id){$s=$pdo->prepare("SELECT * FROM animais WHERE id=?");$s->execute([$id]);$r=$s->fetch()?:$r;}
$tutores=$pdo->query("SELECT id,nome FROM tutores ORDER BY nome")->fetchAll();$erro='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $nome=trim($_POST['nome']??'');$especie=trim($_POST['especie']??'');
 if($nome==='')$erro='Informe o nome do animal.';
 else{
  $dados=[(int)$_POST['tutor_id'],$nome,$especie,$_POST['raca'],$_POST['nascimento']?:null,$_POST['sexo'],$_POST['observacoes']];
  if($id){$s=$pdo->prepare("UPDATE animais SET tutor_id=?,nome=?,especie=?,raca=?,nascimento=?,sexo=?,observacoes=?,ativo=? WHERE id=?");$s->execute(array_merge($dados,[(int)($_POST['ativo']??1),$id]));}
  else{$s=$pdo->prepare("INSERT INTO animais(tutor_id,nome,especie,raca,nascimento,sexo,observacoes) VALUES(?,?,?,?,?,?,?)");$s->execute($dados);}
  header('Location: index.php');exit;
 }
}
$titulo=$id?'Editar animal':'Novo animal';include __DIR__.'/../includes/header.php';?>
<h1><?=$titulo?></h1><section class="panel"><?php if($erro):?><div class="alert"><?=$erro?></div><?php endif;?>
<form method="post">
<div class="field"><label>Tutor</label><select name="tutor_id" required><option value="">Selecione</option><?php foreach($tutores as $t):?><option value="<?=$t['id']?>" <?=$r['tutor_id']==$t['id']?'selected':''?>><?=htmlspecialchars($t['nome'])?></option><?php endforeach;?></select></div>
<div class="field"><label>Nome *</label><input name="nome" value="<?=htmlspecialchars($r['nome'])?>" required></div>
<div class="field"><label>Espécie</label><input name="especie" value="<?=htmlspecialchars($r['especie'])?>"></div>
<div class="field"><label>Raça</label><input name="raca" value="<?=htmlspecialchars($r['raca'])?>"></div>
<div class="field"><label>Data de nascimento</label><input type="date" name="nascimento" value="<?=$r['nascimento']?>"></div>
<div class="field"><label>Sexo</label><select name="sexo"><option value="">Selecione</option><option <?=$r['sexo']==='Macho'?'selected':''?>>Macho</option><option <?=$r['sexo']==='Fêmea'?'selected':''?>>Fêmea</option></select></div>
<div class="field"><label>Observações</label><textarea name="observacoes"><?=htmlspecialchars($r['observacoes'])?></textarea></div>
<?php if($id):?><div class="field"><label>Situação</label><select name="ativo"><option value="1" <?=$r['ativo']?'selected':''?>>Ativo</option><option value="0" <?=!$r['ativo']?'selected':''?>>Inativo</option></select></div><?php endif;?>
<button>Salvar</button> <a class="btn secondary" href="index.php">Cancelar</a></form></section><?php include __DIR__.'/../includes/footer.php';?>
