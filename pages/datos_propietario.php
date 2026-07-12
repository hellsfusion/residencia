<?php
include __DIR__ . '/../php/header.php';
$name = 'Datos del Propietario / Beneficiario';
$page = 'datos_propietario';
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
         <div class="col-md-3">
          <div class="form-group">
           <label for="nombre">Edificio</label>
           <select name="datos[idEdificio]" class="form-control" id="idEdificio" readonly>
            <?= selectMaster('where 1=1 and activo = 1', 'id', 'nombre', 'edificios') ?>
           </select>
          </div>
         </div>
         <div class="col-md-3">
          <div class="form-group">
           <label for="nombre">Apartamento</label>
           <input type="text" class="form-control" id="apartamento" name="datos[apartamento]" required value="<?= ($rowConfig != null ? $rowConfig['apartamento'] : null) ?>" readonly>
          </div>
         </div>
         <div class="col-md-6">
          <div class="form-group">
           <label for="nombre">Propietario</label>
           <input type="text" class="form-control" id="propietario" name="datos[propietario]" required value="<?= ($rowConfig != null ? $rowConfig['propietario'] : null) ?>">
          </div>
         </div>
         <div class="col-md-4">
          <div class="form-group">
           <label for="nombre">Nombre [Contacto]</label>
           <input type="text" class="form-control" id="contacto" name="datos[contacto]" required value="<?= ($rowConfig != null ? $rowConfig['contacto'] : null) ?>">
          </div>
         </div>
         <div class="col-md-4">
          <div class="form-group">
           <label for="nombre">Telefono [Contacto]</label>
           <input type="text" class="form-control" id="telefono" name="datos[telefono]" required value="<?= ($rowConfig != null ? $rowConfig['telefono'] : null) ?>">
          </div>
         </div>
         <div class="col-md-4">
          <div class="form-group">
           <label for="nombre">Correo [Contacto]</label>
           <input type="text" class="form-control" id="correo" name="datos[correo]" required value="<?= ($rowConfig != null ? $rowConfig['correo'] : null) ?>">
          </div>
         </div>
        </div>
       </div>
       <div class="card-footer">
        <input type="hidden" name="datos[activo]" value="1">
        <input type="hidden" name="datos[random]" value="<?= rand(0, 99999) ?>">
        <button type="submit" class="btn btn-primary" onclick="$('#configForm').automaticForm({type:'<?= ($rowConfig != null ? 2 : 1) ?>', idUpdate:'<?= ($rowConfig != null ? $rowConfig['id'] : null) ?>', table:'<?= $table ?>', reload:'', page:'<?= $page ?>?FAID='});">
         <i class="fas fa-save"></i>
         Guardar
        </button>
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