<!DOCTYPE html>
<html lang="en">

<?php
// include './php/funciones.php';
include __DIR__ . '/funciones.php';
include __DIR__ . '/verify_session.php';

// if (isLoggedIn() == false) {
//     header("Location: index.php");
//     exit();
// }
?>

<script>
 // En el <head> de tu layout/template principal
 const BASE_URL = '<?= $Base ?>';
</script>

<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>Sistema Administración</title>

 <!-- Google Font: Source Sans Pro -->
 <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
 <!-- Font Awesome Icons -->
 <link rel="stylesheet" href="<?= $Base ?>/plugins/fontawesome-free/css/all.min.css">
 <!-- overlayScrollbars -->
 <link rel="stylesheet" href="<?= $Base ?>/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
 <!-- Theme style -->
 <link rel="stylesheet" href="<?= $Base ?>/dist/css/adminlte.min.css">

 <!-- DataTables -->
 <link rel="stylesheet" href="<?= $Base ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
 <link rel="stylesheet" href="<?= $Base ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
 <link rel="stylesheet" href="<?= $Base ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

 <!-- summernote -->
 <link rel="stylesheet" href="<?= $Base ?>/plugins/summernote/summernote-bs4.min.css">


 <!-- icon -->
 <link rel="icon" type="image/x-icon" href="<?= $Base ?>/img/ico.png">
 <!-- icono android -->
 <link rel="shortcut icon" type="image/x-icon" href="<?= $Base ?>/img/ico.png">

 <!-- Icono estándar -->
 <link rel="icon" type="image/x-icon" href="<?= $Base ?>/img/ico.png">
 <link rel="shortcut icon" type="image/x-icon" href="<?= $Base ?>/img/ico.png">

 <!-- Iconos para dispositivos Apple -->
 <link rel="apple-touch-icon" href="<?= $Base ?>/img/ico.png">
 <link rel="apple-touch-icon" sizes="57x57" href="<?= $Base ?>/img/ico.png">
 <link rel="apple-touch-icon" sizes="72x72" href="<?= $Base ?>/img/ico.png">
 <link rel="apple-touch-icon" sizes="114x114" href="<?= $Base ?>/img/ico.png">
 <link rel="apple-touch-icon" sizes="144x144" href="<?= $Base ?>/img/ico.png">

 <!-- Para Windows Metro -->
 <meta name="msapplication-TileImage" content="<?= $Base ?>/img/ico.png">

 <link rel="stylesheet" href="<?= $Base ?>/plugins/fullcalendar/main.css">


</head>
<style>
 .word-wrap {
  white-space: normal;
  /* permite el salto de línea */
  overflow-wrap: break-word;
  /* permite cortar palabras largas si es necesario */
  word-break: break-word;
  /* permite romper la palabra si no cabe */
 }
</style>

<body class="hold-transition light-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
 <div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
   <i class="fas fa-spinner fa-spin fa-3x"></i>
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-light">
   <!-- Left navbar links -->
   <ul class="navbar-nav">
    <li class="nav-item">
     <a class="nav-link" data-widget="pushmenu" href="<?= $Base ?>/#" role="button"><i class="fas fa-bars"></i></a>
    </li>
   </ul>

   <!-- Right navbar links -->
   <ul class="navbar-nav ml-auto">
    <li class="nav-item">
     <span class="nav-link">
      <strong>Tasa BCV: <?= number_format(getDolarBCV(), 2) ?></strong>
     </span>
    </li>
    <li class="nav-item">
     <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#calculadora">
      <i class="fas fa-calculator"></i>
      Calculadora
     </button>
    </li>
    <!-- <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li> -->
   </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-light-primary elevation-4">
   <!-- Brand Logo -->
   <a href="#" class="brand-link">
    <span class="brand-text font-weight-light">Sistema Administración</span>
   </a>

   <!-- Sidebar -->
   <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
     <div class="info">
      <span class="d-block word-wrap"><?= $_SESSION['name'] ?></span>
     </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
     <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <?php foreach ($menu as $menuOpcion) { ?>
       <li class="nav-item">
        <a href="<?= $Base ?><?= $menuOpcion['url'] ?>" class="nav-link">
         <i class="nav-icon <?= $menuOpcion['icon'] ?>"></i>
         <p><?= $menuOpcion['nombre'] ?></p>
        </a>
       </li>
      <?php } ?>
     </ul>
    </nav>
    <!-- /.sidebar-menu -->
   </div>
   <!-- /.sidebar -->
  </aside>