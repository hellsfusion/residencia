<?php
include __DIR__ . '/../php/header.php';
$name = 'Documentos Generados';
$page = 'documentos_generados';
$table = 'documentos_generados';
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
 'Apto.',
 'Contacto',
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
           <td><?= funcionMaster($row['idEdificio'], 'id', 'nombre', 'edificios') ?> | <?= funcionMaster($row['idApto'],'id','apartamento','apartamentos') ?></td>
           <td>            
            <p class="m-0 p-0"><strong>Doc:</strong> <?= funcionMaster($row['idDoc'],'id','titulo', 'documentos_generados_plantillas') ?></p>
            <p class="m-0 p-0"><strong>Fecha:</strong> <?= $row['fechaRegistro'] ?></p>
           </td>
           <td class="text-center" style="width: 100px">
            <?php
            $botonesImprimir = [
             ['', base64_encode($Base . 'prints/documento.php?i=' . base64_encode(base64_encode($row['id'])) . ''), 0],
            ];
            $_GET['botones'] = base64_encode(json_encode($botonesImprimir));
            include __DIR__ . '/../creadorImpresiones/seleccionarMetodoImpresion.php';
            ?>
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