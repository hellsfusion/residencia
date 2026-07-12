<?php
include __DIR__ . '/../php/funciones.php';
include __DIR__ . '/../php/error_reporting.php';

if (isset($_POST['datos']['idEdificio']) && isset($_POST['datos']['pass']) && isset($_POST['datos']['idApartamento'])) {
 loginApartamento($_POST['datos']['idEdificio'], $_POST['datos']['pass'], $_POST['datos']['idApartamento']);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>Log in</title>

 <!-- Google Font: Source Sans Pro -->
 <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
 <!-- Font Awesome -->
 <link rel="stylesheet" href="<?= $Base ?>plugins/fontawesome-free/css/all.min.css">
 <!-- icheck bootstrap -->
 <link rel="stylesheet" href="<?= $Base ?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
 <!-- Theme style -->
 <link rel="stylesheet" href="<?= $Base ?>dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
 <img src="./img/bg.jpg" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: -1; opacity: 0.5;">
 <div class="login-box">
  <!-- /.login-logo -->
  <div class="card">
   <div class="card-header">
    <h3 class="card-title"><b>Administración</b> Florida</a></h3>
   </div>
   <div class="card-body login-card-body">

    <form method="post">
     <div class="form-group">
      <label for="nombre">Edificio</label>
      <select name="datos[idEdificio]" class="form-control" id="idEdificio" required>
       <?= selectMaster('where 1=1 and activo = 1', 'id', 'nombre', 'edificios') ?>
      </select>
     </div>

     <div class="form-group">
      <label for="nombre">Apartamento</label>
      <select name="datos[idApartamento]" class="form-control" id="idApartamento" required>
       <option value="" disabled selected> - </option>
       <?= selectMaster('where 1=1 and activo = 1 order by apartamento', 'id', 'apartamento', 'apartamentos') ?>
      </select>
     </div>

     <div class="form-group">
      <label for="nombre">Contraseña</label>
      <div class="input-group mb-3">
       <input type="password" class="form-control" placeholder="Contraseña" name="datos[pass]" required>
       <div class="input-group-append">
        <div class="input-group-text">
         <span class="fas fa-lock"></span>
        </div>
       </div>
      </div>

     </div>


     <div class="row">
      <!-- /.col -->
      <div class="col-4">
       <button type="submit" class="btn btn-primary btn-block">Entrar</button>
      </div>
      <!-- /.col -->
     </div>
    </form>
    <!-- /.social-auth-links -->
   </div>
   <!-- /.login-card-body -->
  </div>
 </div>
 <!-- /.login-box -->

 <!-- jQuery -->
 <script src="<?= $Base ?>plugins/jquery/jquery.min.js"></script>
 <!-- Bootstrap 4 -->
 <script src="<?= $Base ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
 <!-- AdminLTE App -->
 <script src="<?= $Base ?>dist/js/adminlte.min.js"></script>
</body>

</html>