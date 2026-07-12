<?php
include __DIR__ . '/../php/header.php';
$name = 'Generar Documento';
$page = 'documentos_generados';
$table = 'documentos_generados';
$rowConfig = null;

if (!$_GET['idApto']) {
 echo "<script>location.href = './seleccionar?p=" . base64_encode('documentos_generar') . "&n=" . base64_encode('Apartamento') . "&v=" . base64_encode("selectMaster('where 1=1 and activo = 1', 'id', 'apartamento,propietario', 'apartamentos')") . "&vA=" . base64_encode('idApto') . "&aGet=" . base64_encode('FAID=' . $_GET['FAID'] . '') . "'</script>";
}

// var_dump($_GET);
if ($_GET['FAID'] != null) {
 $id = base64_decode($_GET['FAID']);
 $queryConfig = "SELECT * from documentos_generados_plantillas where id = '$id' LIMIT 1";
 $resultConfig = mysqli_query($conn, $queryConfig);
 if (mysqli_num_rows($resultConfig) > 0) {
  $rowConfig = mysqli_fetch_assoc($resultConfig);
 } else {
  echo "<script>alert('No se encontró el registro.');</script>";
 }
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
      <div class="card card-secondary">
       <div class="card-header border-transparent">
        <h3 class="card-title"><?= ($rowConfig != null ? $rowConfig['titulo'] : '') ?></h3>
       </div>
       <div class="card-body">
        <div class="row">
         <div class="col-md-12">
          <div class="form-group">
           <label for="nombre">Titulo</label>
           <input type="text" class="form-control" id="titulo" name="datos[titulo]" required value="<?= ($rowConfig != null ? $rowConfig['titulo'] : null) ?>" readonly>
          </div>
         </div>
         <div class="col-md-12">
          <div class="form-group">
           <label for="nombre">Documento</label>
           <textarea name="datos[contenido]" class="summernote"><?= ($rowConfig != null ? remplazarVariablesPlantilla($rowConfig['contenido'], $_SESSION['id'], $rowConfig['idEdificio'], $_GET['idApto']) : null) ?></textarea>
          </div>
         </div>
        </div>
       </div>
       <div class="card-footer">
        <input type="hidden" name="datos[activo]" value="1">
        <input type="hidden" name="datos[idDoc]" value="<?= $rowConfig['id'] ?>">
        <input type="hidden" name="datos[idEdificio]" value="<?= $rowConfig['idEdificio'] ?>">
        <input type="hidden" name="datos[idApto]" value="<?= $_GET['idApto'] ?>">
        <input type="hidden" name="datos[idUsuario]" value="<?= $_SESSION['id'] ?>">
        <input type="hidden" name="datos[random]" value="<?= rand(0, 99999) ?>">
        <button type="submit" class="btn btn-primary" onclick="$('#configForm').automaticForm({type:'1', idUpdate:'0', table:'<?= $table ?>', reload:'', page:'<?= $page ?>?FAID='});">
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