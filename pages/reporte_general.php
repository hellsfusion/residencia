<?php
include __DIR__ . '/../php/header.php';
include __DIR__ . '/../php/error_reporting.php';
// var_dump(__DIR__ . '/../php/error_reporting.php');

// calcular cuantos meses han pasado desde el inicio del sistema hasta hoy
$mesesCal = calcularMesesEntreFechas($fechaInicioSistema, date('Y-m-d'));

// echo "Han pasado " . $mesesCal . " meses.";
// Salida: Han pasado 16 meses.


// cantidad de operaciones en el mes
// cuotas extra 
$queryCuotas = "SELECT * from cuotasExtra
where 1=1
and activo = 1
";
$resultCuotas = mysqli_query($conn, $queryCuotas);
$rowCuotas = [];
while ($row = mysqli_fetch_assoc($resultCuotas)) {
  $rowCuotas[] = $row;
}

// recibos 
$idApartamento = $_SESSION['id'];
$queryRecibos = "SELECT * from ingresos
where 1=1
and activo = 1
and idApartamento = '$idApartamento'
";
$resultRecibos = mysqli_query($conn, $queryRecibos);
$rowRecibos = [];
while ($row = mysqli_fetch_assoc($resultRecibos)) {
  $rowRecibos[] = $row;
}

// contar recibos de pago
$cant_recibos = 0;
foreach ($rowRecibos as $recibo) {
  if (($recibo['idCuotaExtra'] == 0 || $recibo['idCuotaExtra'] == null || $recibo['idCuotaExtra'] == '') && ($recibo['mes'] != null && $recibo['mes'] != '' && $recibo['mes'] != 0)) {
    $cant_recibos++;
  }
}

// contar cuotas extra
$cant_cuotas = 0;
foreach ($rowRecibos as $recibo) {
  if ($recibo['idCuotaExtra'] != 0 && $recibo['idCuotaExtra'] != null && $recibo['idCuotaExtra'] != '') {
    $cant_cuotas++;
  }
}



$infoBoxes = [
  ['icon' => 'fas fa-bars', 'text' => 'Cuotas Extra', 'number' => $cant_cuotas . '/' . (count($rowCuotas)), 'bg' => 'bg-secondary'],
  ['icon' => 'fas fa-bars', 'text' => 'Recibos de pago', 'number' => $cant_recibos . '/' . $mesesCal, 'bg' => 'bg-secondary'],
];



?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Inicio</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">


      <?php

      // buscar datos de la tabla
      $query = "SELECT * from apartamentos
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

      foreach ($rowData as $apto) {
      ?>
        <div class="row">


          <div class="col-12 col-sm-12 col-md-12">
            <div class="card card-secondary collapsed-card">

              <div class="card-header">
                <h3 class="card-title">Resumen General - <?= $apto['apartamento'] ?> <?= $apto['propietario'] ?></h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-plus"></i>
                  </button>
                </div>
              </div>

              <div class="card-body">
                <?php
                $_GET['idApartamento'] = $apto['id'];
                $_GET['fechaInicio'] = $fechaInicioSistema;
                $_GET['fechaFin'] = date('Y-m-d');
                $_GET['include'] = 1;

                $botonesImprimir = [
                  ['', base64_encode($Base . 'prints/resumen_general.php?idApartamento=' . $apto['id'] . '&fechaInicio=' . $fechaInicioSistema . '&fechaFin=' . date('Y-m-d') . '&include=0'), 0],
                ];
                $_GET['botones'] = base64_encode(json_encode($botonesImprimir));
                include __DIR__ . '/../creadorImpresiones/seleccionarMetodoImpresion.php';


                include __DIR__ . '/../prints/resumen_general.php';
                ?>
              </div>
            </div>
          </div>


        </div>
      <?php
      }

      ?>





    </div><!--/. container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php
include __DIR__ . '/../php/footer.php';
?>

<script>
  document
    .querySelectorAll(".toggle-header")
    .forEach((header) => {
      header.addEventListener("click", function() {
        // Buscamos el card-body que está justo debajo de este header
        const cardBody = this.nextElementSibling;

        if (cardBody.style.display === "none") {
          cardBody.style.display = "block";
        } else {
          cardBody.style.display = "none";
        }

        // Opcional: Si usas las clases de AdminLTE para animar el contenedor completo:
        // this.closest('.card').classList.toggle('collapsed-card');
      });
    });
</script>