<?php
include __DIR__ . '/../php/header.php';


$p = base64_decode($_GET['p']);
$n = base64_decode($_GET['n']);
$v = base64_decode($_GET['v']);
$vA = base64_decode($_GET['vA']);
$aGet = base64_decode($_GET['aGet']);
// get adicionales vienen en string ej get1=1,get2=2
$aGet = explode(',', $aGet);
// separarlo de la igualdad
?>

<div class="content-wrapper p-3">
 <!-- Main content -->
 <section class="content">
  <div class="">
   <div class="col-xs-12">

    <div class="row">
     <div class="col-md-12">
      <div class="card card-secondary">
       <div class="card-header">
        <div class="float-left">
         <h4>Seleccione <strong><?= $n ?></strong></h4>
        </div>
       </div>
       <form method="get" action="<?= $p ?>">
        <div class="card-body">
         <div class="form-group">
          <select name="<?= $vA ?>" class="form-control" id="<?= $vA ?>">
           <option value="0"> - </option>
           <?= eval("return ". $v .";") ?>
          </select>
         </div>
         <div class="form-group">
          <button type="submit" class="btn btn-outline-secondary rounded-pill">
           <i class="fas fa-chevron-right"></i>
           Continuar
          </button>
         </div>
        </div>
        <?php
        foreach ($aGet as $get) {
         ?>
         <input type="hidden" name="<?= explode('=', $get)[0] ?>" value="<?= explode('=', $get)[1] ?>">
         <?php
        }
        ?>
       </form>
      </div>
     </div>
    </div>

   </div>
   <!-- /.col -->
  </div>
  <!-- /.row -->
 </section>
 <!-- /.content -->

</div>
<!-- /.content-wrapper -->
<?php include __DIR__ . '/../php/footer.php'; ?>