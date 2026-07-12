<?php
include __DIR__ . '/../php/header.php';
$name = 'Contraseñas';
$page = 'claves';
$table = 'claves';
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
                                <h3 class="card-title"><?= ($rowConfig != null ? 'Editar' : 'Registrar') ?></h3>
                                <div class="card-tools mt-2">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas <?= ($rowConfig == null ? 'fa-plus' : 'fa-minus') ?>"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Nombre</label>
                                            <input type="text" class="form-control" id="nombre" name="datos[nombre]" required value="<?= ($rowConfig != null ? $rowConfig['nombre'] : null) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Usuario</label>
                                            <input type="text" class="form-control" id="usuario" name="datos[usuario]" required value="<?= ($rowConfig != null ? $rowConfig['usuario'] : null) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Contraseña</label>
                                            <input type="text" class="form-control" id="clave" name="datos[clave]" required value="<?= ($rowConfig != null ? $rowConfig['clave'] : null) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Notas</label>
                                            <textarea class="form-control" id="notas" name="datos[notas]"><?= ($rowConfig != null ? $rowConfig['notas'] : null) ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Privada</label>
                                            <select name="datos[privada]" class="form-control" id="privada">
                                                <option value="0" <?= ($rowConfig != null && $rowConfig['privada'] == '0' ? 'selected' : null) ?>>No</option>
                                                <option value="1" <?= ($rowConfig != null && $rowConfig['privada'] == '1' ? 'selected' : null) ?>>Si</option>
                                            </select>
                                            <small>Al marcar privada solo podrá ser visto por este usuario</small>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="hidden" name="datos[activo]" value="1">
                                <input type="hidden" name="datos[idUsuario]" value="<?= $_SESSION['id'] ?>">
                                <input type="hidden" name="datos[random]" value="<?= rand(0, 99999) ?>">
                                <button type="submit" class="btn btn-primary" onclick="$('#configForm').automaticForm({type:'<?= ($rowConfig != null ? 2 : 1) ?>', idUpdate:'<?= ($rowConfig != null ? $rowConfig['id'] : null) ?>', table:'<?= $table ?>', reload:'', page:'<?= $page ?>?FAID='});">
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


            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <div class="col-md-12 row m-0 p-0 mb-5">

                    <?php
                    $queryClaves = "SELECT * from $table 
                where 1=1
                and activo = '1'
                and privada = '1'
                and idUsuario = '{$_SESSION['id']}'
                union 
                SELECT * from $table 
                where 1=1
                and activo = '1'
                and privada = '0'
                ";
                    $resultClaves = mysqli_query($conn, $queryClaves);
                    $rowClaves = [];
                    if (mysqli_num_rows($resultClaves) > 0) {
                        while ($row = mysqli_fetch_assoc($resultClaves)) {
                            $rowClaves[] = $row;
                        }
                    }
                    ?>
                    <?php foreach ($rowClaves as $clave) { ?>
                        <div class="col-md-6" id="clave_<?= $clave['id'] ?>">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title"><?= $clave['nombre'] ?></h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Usuario</label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" value="<?= $clave['usuario'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">Clave</label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" value="<?= $clave['clave'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">Notas</label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" value="<?= $clave['notas'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="button" class="btn btn-info" onclick="verClave('<?= $clave['id'] ?>')">Ver</button>
                                    <a href="<?= $Base. $page ?>?FAID=<?= base64_encode($clave['id']) ?>" class="btn btn-default disabled btn-editar">Editar</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>




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

<script>
    function verClave(id) {

        // solicitar contraseña del usuario logueado para mostrar clave
        let clave = prompt('Ingrese la contraseña del usuario logueado para ver la clave');
        if (clave != '<?= $_SESSION['pass'] ?>') {
            alert('Clave incorrecta');
            return;
        }

        let contenedor = $('#clave_' + id);
        // todos los inputs cambiarlos de password a text
        contenedor.find('input').each(function() {
            $(this).attr('type', 'text');
        });

        // habilitar boton de editar btn-editar
        contenedor.find('.btn-editar').removeClass('disabled');
    }
</script>
