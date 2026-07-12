<?php
include __DIR__ . '/../php/header.php';
$name = 'Reportar Pago';
$page = 'apartamento';
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
           <label for="nombre">Edificio</label>
           <select name="datos[idEdificio]" class="form-control" id="idEdificio" readonly>
            <?= selectMaster('where 1=1 and activo = 1', 'id', 'nombre', 'edificios') ?>
           </select>
          </div>
         </div>
         <div class="col-md-6">
          <div class="form-group">
           <label for="nombre">Apartamento</label>
           <input type="text" class="form-control" id="apartamento" name="datos[apartamento]" required value="<?= ($rowConfig != null ? $rowConfig['apartamento'] : null) ?>" readonly>
          </div>
         </div>

         <?php if ($_GET['f']) {
          $f = base64_decode($_GET['f']);
          $mes = explode("-", $f)[1];
          $anio = explode("-", $f)[0];
         ?>

          <div class="col-md-2">
           <div class="form-group">
            <label for="nombre">Mes</label>
            <select name="datos[mes]" class="form-control" id="mes" required>
             <option value="0"> - </option>
             <?php
             $meses = array(1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre');
             for ($m = 1; $m <= 12; $m++) {
              echo '<option value="' . $m . '" ' . ($m == $mes ? 'selected' : '') . '>' . $m . ' - ' . $meses[$m] . '</option>';
             }
             ?>
            </select>
           </div>
          </div>
          <div class="col-md-2">
           <div class="form-group">
            <label for="nombre">Año</label>
            <input type="number" class="form-control" id="anio" name="datos[anio]" required value="<?= $anio ?>" required>
           </div>
          </div>
          <div class="col-md-8">
           <div class="form-group">
            <label for="nombre">Notas</label>
            <input type="text" class="form-control" id="notas" name="datos[notas]" value="<?= ($rowConfig != null ? $rowConfig['notas'] : null) ?>">
           </div>
          </div>
          <div class="col-md-12">
           <div class="form-group">
            <label for="nombre">Comprobante</label>
            <input type="file" class="form-control" id="comprobante" name="archivos[comprobante|comprobantes/]" accept="image/*,application/pdf" required>
            <small class="form-text text-muted">Formatos permitidos: JPG, PNG, PDF.</small>
           </div>
          </div>

         <?php } ?>

         <?php if ($_GET['iC']) {
          $iC = base64_decode($_GET['iC']);          
         ?>

          <div class="col-md-12">
           <div class="form-group">
            <label for="nombre">Cuota Extra</label>
            <select name="datos[idCuotaExtra]" class="form-control" id="idCuotaExtra">
             <option value="0"> - </option>
             <?= selectMaster('where 1=1 and activo = 1 order by id desc', 'id', 'fechaRegistro,descripcion', 'cuotasExtra', $iC) ?>
            </select>
           </div>
          </div>
          <div class="col-md-12">
           <div class="form-group">
            <label for="nombre">Comprobante</label>
            <input type="file" class="form-control" id="comprobante" name="archivos[comprobante|comprobantes/]" accept="image/*,application/pdf" required>
            <small class="form-text text-muted">Formatos permitidos: JPG, PNG, PDF.</small>
           </div>
          </div>

         <?php } ?>



        </div>
       </div>
       <div class="card-footer">
        <input type="hidden" name="datos[activo]" value="1">
        <input type="hidden" name="datos[random]" value="<?= rand(0, 99999) ?>">
        <button type="submit" class="btn btn-primary" onclick="$('#configForm').automaticForm({type:'<?= 1 ?>', idUpdate:'<?= 0 ?>', table:'reporte_pago', reload:'', page:'<?= $page ?>?FAID='});">
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