<?php
include __DIR__ . '/../php/header.php';
// include __DIR__ . '/../php/error_reporting.php';
// var_dump(__DIR__ . '/../php/error_reporting.php');

// cantidad de operaciones en el mes
$cant_ingresos = floatval(funcionMaster('1', 'activo = 1 and fecha like "%' . date('Y-m') . '%" and 1', 'count(id)', 'ingresos'));
$cant_egresos = 0;
$cant_egresos += floatval(funcionMaster('1', 'activo = 1 and fecha like "%' . date('Y-m') . '%" and 1', 'count(id)', 'gastos'));
$cant_egresos += floatval(funcionMaster('1', 'activo = 1 and fecha like "%' . date('Y-m') . '%" and 1', 'count(id)', 'pagos_conserjeria'));

$infoBoxes = [
  ['icon' => 'fas fa-bars', 'text' => 'Cant. Ingresos', 'number' => number_format($cant_ingresos), 'bg' => 'bg-secondary'],
  ['icon' => 'fas fa-bars', 'text' => 'Cant. Gastos', 'number' => number_format($cant_egresos), 'bg' => 'bg-secondary'],
];

$monedas = funcionMaster('1', 'activo', 'group_concat(id)', 'monedas');
$monedas = explode(',', $monedas);
foreach ($monedas as $moneda) {
  $ingresos = floatval(funcionMaster('1', 'activo = 1 and fecha like "%' . date('Y-m') . '%" and idMoneda = ' . $moneda . ' and  1', 'sum(monto)', 'ingresos'));
  $egresos = 0;
  $egresos += floatval(funcionMaster('1', 'activo = 1 and fecha like "%' . date('Y-m') . '%" and idMoneda = ' . $moneda . ' and  1', 'sum(monto)', 'gastos'));
  $egresos += floatval(funcionMaster('1', 'activo = 1 and fecha like "%' . date('Y-m') . '%" and idMoneda = ' . $moneda . ' and  1', 'sum(monto)', 'pagos_conserjeria'));
  // 21 12 2025
  // agregar comisiones
  $egresos += floatval(funcionMaster('1', 'activo = 1 and fecha like "%' . date('Y-m') . '%" and idMoneda = ' . $moneda . ' and  1', 'sum(comision)', 'gastos'));
  


  $infoBoxes[] = ['icon' => 'fas fa-arrow-down', 'text' => funcionMaster($moneda, 'id', 'prefijo', 'monedas') . ' - Ingresos', 'number' => number_format($ingresos, 2), 'bg' => 'bg-success'];
  $infoBoxes[] = ['icon' => 'fas fa-arrow-up', 'text' => funcionMaster($moneda, 'id', 'prefijo', 'monedas') . ' - Gastos', 'number' => number_format($egresos, 2), 'bg' => 'bg-danger'];
}



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
      <!-- Info boxes -->
      <div class="row">
        <?php foreach ($infoBoxes as $box) { ?>
          <div class="col-6 col-sm-6 col-md-6">
            <div class="info-box">
              <span class="info-box-icon <?= $box['bg']; ?> elevation-1"><i class="<?= $box['icon']; ?>"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?= $box['text']; ?></span>
                <span class="info-box-number">
                  <?= $box['number']; ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        <?php } ?>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <?php foreach ($monedas as $moneda) { ?>
          <div class="col-12 col-sm-12 col-md-12">
            <div class="card card-secondary">
              <div class="card-header">
                <h3 class="card-title"><?= funcionMaster($moneda, 'id', 'concat(nombre, " - ", prefijo)', 'monedas') ?> </h3>
              </div>
              <div class="card-body">
                <div id="chart-<?= $moneda ?>"></div>
              </div>
            </div>

          </div>

        <?php } ?>
      </div>

    </div><!--/. container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php
include __DIR__ . '/../php/footer.php';
?>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    setTimeout(() => {
      <?php foreach ($monedas as $moneda) {
        // traer datos de los ultimos 12 meses
        $ingresos_data = array();
        $egresos_data = array();
        $categories = array();

        for ($i = 12; $i >= 0; $i--) { // Invertir el orden para que sea cronológico
          $mes = date('Y-m', strtotime('-' . $i . ' month'));
          $ingresos = floatval(funcionMaster('1', 'activo = 1 and fecha like "%' . $mes . '%" and idMoneda = ' . $moneda . ' and  1', 'sum(monto)', 'ingresos'));

          $egresos = 0;
          $egresos += floatval(funcionMaster('1', 'activo = 1 and fecha like "%' . $mes . '%" and idMoneda = ' . $moneda . ' and  1', 'sum(monto)', 'gastos'));          
          $egresos += floatval(funcionMaster('1', 'activo = 1 and fecha like "%' . $mes . '%" and idMoneda = ' . $moneda . ' and  1', 'sum(monto)', 'pagos_conserjeria'));

          $comisiones = 0;
          $comisiones += floatval(funcionMaster('1', 'activo = 1 and fecha like "%' . $mes . '%" and idMoneda = ' . $moneda . ' and  1', 'sum(comision)', 'gastos'));
          $egresos += $comisiones;

          $ingresos_data[] = $ingresos;
          $egresos_data[] = $egresos;
          $comisiones_data[] = $comisiones;
          $categories[] = $mes . 'T00:00:00.000Z'; // Formato datetime para ApexCharts
        }

        // // traer datos de los ultimos 30 dias
        // $dias = array();
        // $ingresos_data = array();
        // $egresos_data = array();
        // $categories = array();

        // for ($i = 29; $i >= 0; $i--) { // Invertir el orden para que sea cronológico
        //   $dia = date('Y-m-d', strtotime('-' . $i . ' days'));
        //   $ingresos = floatval(funcionMaster('1', 'activo = 1 and fecha like "%' . $dia . '%" and idMoneda = ' . $moneda . ' and  1', 'sum(monto)', 'ingresos'));
        //   $egresos = 0;
        //   $egresos += floatval(funcionMaster('1', 'activo = 1 and fecha like "%' . $dia . '%" and idMoneda = ' . $moneda . ' and  1', 'sum(monto)', 'gastos'));
        //   $egresos += floatval(funcionMaster('1', 'activo = 1 and fecha like "%' . $dia . '%" and idMoneda = ' . $moneda . ' and  1', 'sum(monto)', 'pagos_conserjeria'));

        //   $ingresos_data[] = $ingresos;
        //   $egresos_data[] = $egresos;
        //   $categories[] = $dia . 'T00:00:00.000Z'; // Formato datetime para ApexCharts
        // }
      ?>


        // Convertir arrays PHP a JavaScript
        var ingresosData = <?= json_encode($ingresos_data); ?>;
        var egresosData = <?= json_encode($egresos_data); ?>;
        var comisionesData = <?= json_encode($comisiones_data); ?>;
        var categoriesData = <?= json_encode($categories); ?>;

        var options = {
          series: [{
            name: 'Ingresos',
            data: ingresosData
          }, {
            name: 'Gastos',
            data: egresosData
          }, {
            name: 'Comisiones',
            data: comisionesData
          }
        ],
          chart: {
            height: 350,
            type: 'area'
          },
          colors: ['#28a745', '#dc3545', '#ffc107'],
          dataLabels: {
            enabled: false
          },
          stroke: {
            curve: 'smooth'
          },
          xaxis: {
            type: 'datetime',
            categories: categoriesData
          },
          tooltip: {
            x: {
              format: 'dd/MM/yy'
            },
          },
          yaxis: {
            labels: {
              formatter: function(value) {
                return value.toFixed(2); // Formatear a 2 decimales
              }
            }
          }
        };

        var chart = new ApexCharts(document.querySelector("#chart-<?= $moneda ?>"), options);
        chart.render();

      <?php } ?>

    }, 500);
  })
</script>