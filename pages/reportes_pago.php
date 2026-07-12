<?php
include __DIR__ . '/../php/header.php';
$name = 'Reportes de pago Pendientes';
$page = 'dashboard';
$table = 'reporte_pago';
$rowConfig = null;
// var_dump($_GET);
if ($_GET['FAID'] != null) {
 $id = base64_decode($_GET['FAID']);
 $queryConfig = "SELECT * from $table where id = '$id' LIMIT 1";
 $resultConfig = mysqli_query($conn, $queryConfig);
 if (mysqli_num_rows($resultConfig) > 0) {
  $rowConfig = mysqli_fetch_assoc($resultConfig);
 } else {
  echo "<script>alert('No se encontró el registro.');</script>";
 }
}

// datos lista
$headers = [
 'No.',
 '',
 '',
];

// buscar datos de la tabla
$query = "SELECT * from $table
where 1=1
and activo = 1
";
$result = mysqli_query($conn, $query);
$rowData = [];
if ($result && mysqli_num_rows($result) > 0) {
 while ($row = mysqli_fetch_assoc($result)) {
  $rowData[] = $row;
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

     <div class="card card-secondary">
      <div class="card-header border-transparent">
       <h3 class="card-title"></h3>
      </div>
      <div class="card-body">
       <table class="table table-striped">
        <thead>
         <tr>
          <?php foreach ($headers as $value) { ?>
           <th><?= $value ?></th>
          <?php } ?>
         </tr>
        </thead>
        <tbody>
         <?php foreach ($rowData as $row) { ?>
          <tr>
           <td><?= $row['id'] ?></td>

           <td>
            <p class="m-0 p-0">Edificio: <?= funcionMaster($row['idEdificio'], 'id', 'nombre', 'edificios') ?></p>
            <p class="m-0 p-0">Apartamento: <?= $row['apartamento'] ?></p>
            <p class="m-0 p-0"><strong>Mes: <?= $row['mes'] ?></strong></p>
            <p class="m-0 p-0"><strong>Año:</strong> <?= $row['anio'] ?></p>
            <p class="m-0 p-0"><strong>Cuota Extra:</strong> <?= $row['idCuotaExtra'] ?> <?= funcionMaster($row['idCuotaExtra'], 'id', 'descripcion', 'cuotasExtra') ?></p>
            <p class="m-0 p-0"><strong>Notas:</strong> <?= $row['notas'] ?></p>
            <a href="<?=$Base?>comprobantes/<?= ($row['comprobante']) ?>" class="btn btn-info btn-sm" target="_blank">
             <i class="fas fa-eye"></i>
             <?= $row['comprobante'] ?>
            </a>
           </td>
           <td class="text-center" style="width: 100px">
            <button class="btn btn-danger btn-sm btn-block" onclick="automaticUpdateAlert('Eliminar este registro', '0', 'activo', '<?= $table ?>', '<?= $row['id'] ?>', '');" title="Eliminar">
             <i class="fas fa-trash"></i>
             Eliminar
            </button>
           </td>
          </tr>
         <?php } ?>
        </tbody>
        <tfoot>
         <tr>
          <?php foreach ($headers as $value) { ?>
           <th><?= $value ?></th>
          <?php } ?>
         </tr>
        </tfoot>
       </table>

      </div>
      <div class="card-footer">

      </div>
     </div>

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