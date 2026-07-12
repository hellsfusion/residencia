<?php
include __DIR__ . '/../php/header.php';
$name = 'Cambiar Contraseña';
$page = 'salir';
$table = 'apartamentos';
$rowConfig = null;

$id = $_SESSION['id'];
$queryConfig = "SELECT * from $table where id = '$id' LIMIT 1";
$resultConfig = mysqli_query($conn, $queryConfig);
if (mysqli_num_rows($resultConfig) > 0) {
 $rowConfig = mysqli_fetch_assoc($resultConfig);
} else {
 echo "<script>alert('No se encontró el registro.');</script>";
}




?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
 <!-- Content Header (Page header) -->
 <div class="content-header">
  <div class="container-fluid">
   <div class="row mb-2">
    <div class="col-sm-6">
     <h1 class="m-0"><?= $name ?></h1>
    </div><!-- /.col -->
   </div><!-- /.row -->
  </div><!-- /.container-fluid -->
 </div>
 <!-- /.content-header -->

 <!-- Main content -->
 <section class="content">
  <div class="container-fluid">

   <!-- Main row -->
   <div class="row">
    <!-- Left col -->
    <div class="col-md-12">
     <form id="configForm">
      <div class="card card-secondary <?= ($rowConfig == null ? 'collapsed-card' : null) ?>">
       <div class="card-header border-transparent">
       </div>
       <div class="card-body">
        <div class="row">

         <div class="col-md-6">
          <div class="form-group">
           <label for="nombre">Nueva Contraseña</label>
           <input type="text" class="form-control" id="pass" required value="">
          </div>
         </div>

         <div class="col-md-6">
          <div class="form-group">
           <label for="nombre">Confirmar Contraseña</label>
           <input type="text" class="form-control" id="confirm_pass" required value="">
          </div>
         </div>
        </div>
       </div>
       <div class="card-footer">
        <input type="hidden" name="datos[pass]" value="<?= ($rowConfig != null ? $rowConfig['pass'] : null) ?>">
        <input type="hidden" name="datos[activo]" value="1">
        <input type="hidden" name="datos[random]" value="<?= rand(0, 99999) ?>">
        <button type="button" class="btn btn-primary" onclick="verificarDatos()">
         <i class="fas fa-save"></i> Guardar Cambios
       </div>
      </div>
     </form>
    </div>
    <!-- /.col -->
   </div>
   <!-- /.row -->


  </div><!--/. container-fluid -->
 </section>
 <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include __DIR__ . '/../php/footer.php'; ?>

<!-- <button type="submit" class="btn btn-primary" onclick="$('#configForm').automaticForm({type:'<?= ($rowConfig != null ? 2 : 1) ?>', idUpdate:'<?= ($rowConfig != null ? $rowConfig['id'] : null) ?>', table:'<?= $table ?>', reload:'', page:'<?= $page ?>?FAID='});">
 <i class="fas fa-save"></i>
 Guardar
</button> -->

<script>
 
  function verificarDatos() {

  var pass = $('#pass').val();
  var confirm_pass = $('#confirm_pass').val();

  if (pass === confirm_pass) {
   // Si las contraseñas coinciden, envía el formulario
   $('#configForm').automaticForm({
    type: '<?= ($rowConfig != null ? 2 : 1) ?>',
    idUpdate: '<?= ($rowConfig != null ? $rowConfig['id'] : null) ?>',
    table: '<?= $table ?>',
    reload: '',
    page: '<?= $page ?>?FAID='
   });

   //submit del formulario
   $('#configForm').submit();

  } else {
   // Si las contraseñas no coinciden, muestra un mensaje de error
   alert('Las contraseñas no coinciden. Por favor, inténtalo de nuevo.');
  }
 };

 // on input de los campos de contraseña, verifica si coinciden y si coinciden, cambiar el valor del campo oculto de pass
 $('#pass, #confirm_pass').on('input', function() {
 var pass = $('#pass').val();
 var confirm_pass = $('#confirm_pass').val();

 if (pass === confirm_pass) {
  // Si las contraseñas coinciden, cambia el valor del campo oculto de pass
  $('input[name="datos[pass]"]').val(CryptoJS.MD5(pass + '<?= $salt ?>').toString());
 } else {
  // Si las contraseñas no coinciden, limpia el valor del campo oculto de pass
  $('input[name="datos[pass]"]').val('');
 }
 });
 
</script>