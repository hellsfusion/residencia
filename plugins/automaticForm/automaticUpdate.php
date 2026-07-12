<?php include '../../php/conn.php' ?>
<?php $camposQueNoSePuedenRepetir = [
    'porcentaje'
    ] ?>
<?php if (in_array($_POST['campo'], $camposQueNoSePuedenRepetir)) { // validación para campos que no se pueden repetir
    $queryEspecial = mysqli_query($conn, "SELECT * FROM {$_POST['tabla']} where {$_POST['campo']} = '{$_POST['valor']}'");
    $countEspecial = mysqli_num_rows($queryEspecial);
} else {
    $countEspecial = 0;
} ?>
<?php if (empty($countEspecial)): ?>
    <?php $queryshow = mysqli_query($conn, "SHOW COLUMNS FROM {$_POST['tabla']} where `Key` like '%PRI%'") ?>
    <?php $fetchshow = mysqli_fetch_array($queryshow) ?>
    <?php $validar = mysqli_query($conn, "UPDATE {$_POST['tabla']} set {$_POST['campo']} = '{$_POST['valor']}' where {$fetchshow['Field']} = '{$_POST['idUpdate']}'") ?>
<?php endif ?>
<?php if ($validar) { ?>
<?="true"?>
<?php } else { ?>
<?="false"?>
<?php } ?>