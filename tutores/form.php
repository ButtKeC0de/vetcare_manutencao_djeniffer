<?php
require_once __DIR__.'/../config/database.php'; require_once __DIR__.'/../config/auth.php'; exigirLogin();
$id=(int)($_GET['id']??0); $r=['nome'=>'','telefone'=>'','email'=>'','endereco'=>'','ativo'=>1];
if($id){$s=$pdo->prepare("SELECT * FROM tutores WHERE id=?");$s->execute([$id]);$r=$s->fetch()?:$r;}
$erro='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $nome=trim($_POST['nome']??''); $telefone=trim($_POST['telefone']??'');
 if($nome==='') $erro='Informe o nome.';
 else {
  if($id){$s=$pdo->prepare("UPDATE tutores SET nome=?,telefone=?,email=?,endereco=?,ativo=? WHERE id=?");$s->execute([$nome,$telefone,$_POST['email'],$_POST['endereco'],(int)($_POST['ativo']??1),$id]);}
  else{$s=$pdo->prepare("INSERT INTO tutores(nome,telefone,email,endereco) VALUES(?,?,?,?)");$s->execute([$nome,$telefone,$_POST['email'],$_POST['endereco']]);}
  header('Location: index.php');exit;
 }
}
$titulo=$id?'Editar tutor':'Novo tutor';include __DIR__.'/../includes/header.php';
?>
<h1><?=$titulo?></h1><section class="panel">
<?php if($erro):?><div class="alert"><?=$erro?></div><?php endif;?>
<form method="post">
<div class="field"><label>Nome *</label><input name="nome" value="<?=htmlspecialchars($r['nome'])?>" required></div>
<div class="field"><label>Telefone</label><input name="telefone" value="<?=htmlspecialchars($r['telefone'])?>"></div>
<div class="field"><label>E-mail</label><input type="email" name="email" value="<?=htmlspecialchars($r['email'])?>"></div>
<div class="field"><label>Endereço</label><input name="endereco" value="<?=htmlspecialchars($r['endereco'])?>"></div>
<?php if($id):?><div class="field"><label>Situação</label><select name="ativo"><option value="1" <?=$r['ativo']?'selected':''?>>Ativo</option><option value="0" <?=!$r['ativo']?'selected':''?>>Inativo</option></select></div><?php endif;?>
<button>Salvar</button> <a class="btn secondary" href="index.php">Cancelar</a>
</form></section><?php include __DIR__.'/../includes/footer.php';?>
