<?php
include __DIR__ .'/../php/conn.php';
// include 'error_reporting.php';
// mysqli_set_charset($conn, "utf8");
$rand = rand(0, 99999);
$bootstrap = 4;
?>

<?php if ($_GET['accion'] == 'configOptions') : ?>
    <?php
    $tableName = $_POST['tabla'];
    $nombre = $_POST['nombre'];

    // Verificar si la tabla existe
    $queryTableExists = mysqli_query($conn, "SHOW TABLES LIKE '$tableName'");
    if (mysqli_num_rows($queryTableExists) > 0) {
        // La tabla existe, obtener la clave primaria
        $queryTable = mysqli_query($conn, "SHOW columns FROM `$tableName` WHERE `Key` = 'PRI'");
        if (mysqli_num_rows($queryTable) > 0) {
            $fetchTable = mysqli_fetch_array($queryTable);
            $primaryKey = $fetchTable['Field'];
        } else {
            $primaryKey = "id"; // Si no hay clave primaria, usar 'id' por defecto
        }
    } else {
        // La tabla no existe
        $primaryKey = "id"; // Usar 'id' como valor predeterminado
    }


    $campos = explode("|/|", $_POST['campos']);
    ?>
    <div class="modal fade" id="modalConfigOption_<?= $_POST['tabla'] ?>" aria-labelledby="modalConfigOption_<?= $_POST['tabla'] ?>Label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalConfigOption_<?= $_POST['tabla'] ?>Label"><b><?=$nombre?></b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="accordion" id="accordionConfigOptions<?= $rand ?>">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h6 class="mb-0" data-toggle="collapse" data-target="#collapseConfigOptionOne<?= $rand ?>" aria-expanded="true" aria-controls="collapseConfigOptionOne<?= $rand ?>">
                                    Agregar Opciones
                                </h6>
                            </div>
                            <div id="collapseConfigOptionOne<?= $rand ?>" class="collapse show">
                                <form action="javascript:addOptions(form_<?= $rand ?>, '<?= $_POST['tabla'] ?>', 1, 'table-<?= $rand ?>---<?= $_POST['tabla'] . "|*|" . $_POST['campos'] ?>')" id="form_<?= $rand ?>">
                                    <div class="card-body">
                                        <?php for ($i = 0; $i < count($campos); $i++) : ?>

                                            <?php $array1 = explode(":", $campos[$i]) ?>
                                            <?php $array2 = explode(">", $array1[0]) ?>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= $array2[0] ?></label>
                                                    <?php if ($array1[1] == "textarea") : ?>
                                                        <textarea class="form-control" name="datos[<?= $array2[1] ?>]"></textarea>
                                                    <?php else : ?>
                                                        <input class="form-control" name="datos[<?= $array2[1] ?>]" type="<?= $array1[1] ?>" <?= $array1[1] == 'radio' ? 'value="'.$array1[2].'"' : '' ?> <?= ($array1[1] == 'number' ? 'step="0.01"' : '') ?>>
                                                    <?php endif ?>
                                                </div>
                                            </div>
                                        <?php endfor ?>
                                    </div>
                                    <div class="card-footer">
                                        <input type="hidden" name="datos[activo]" value="1">
                                        <button type="submit" class="btn btn-info ">
                                            <i class="fas fa-save"></i>
                                            Agregar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h6 class="mb-0" data-toggle="collapse" data-target="#collapseConfigOptionTwo<?= $rand ?>" aria-expanded="true" aria-controls="collapseConfigOptionTwo<?= $rand ?>">
                                    Lista de Opciones
                                </h6>
                            </div>
                            <div id="collapseConfigOptionTwo<?= $rand ?>" class="collapsed" aria-labelledby="headingTwo" data-parent="#accordionConfigOptions<?= $rand ?>">
                                <div class="card-body">
                                    <table class="table table-striped" id="table-<?= $rand ?>" style="width: 100% !important">
                                        <thead>
                                            <tr>
                                                <?php for ($i = 0; $i < count($campos); $i++) : ?>
                                                    <td><?= explode(">", $campos[$i])[0]  ?></td>
                                                <?php endfor ?>
                                                <td>Acción</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $queryTableExists = mysqli_query($conn, "SHOW TABLES LIKE '$tableName'"); ?>
                                            <?php if (mysqli_num_rows($queryTableExists) > 0) : ?>
                                                <?php $query = mysqli_query($conn, "SELECT * from {$_POST['tabla']} where 1=1 and activo = 1") ?>
                                                <?php if (!empty(mysqli_num_rows($query))) : ?>
                                                    <?php $contador = 0 ?>
                                                    <?php foreach ($query as $dat) : ?>
                                                        <?php $contador += 1 ?>
                                                        <tr>
                                                            <?php for ($i = 0; $i < count($campos); $i++) { ?>
                                                                <?php $array1 = explode(":", $campos[$i]) ?>
                                                                <?php $array2 = explode(">", $array1[0]) ?>
                                                                <td>
                                                                    <div class="cont<?= $contador ?>" id="cont_<?= str_replace(' ', '', $array2[1]) . $contador . substr($_POST['tabla'], 0, 5) ?>"><?= $dat[$array2[1]] ?></div>
                                                                    <div class="edit<?= $contador ?>" id="edit_<?= str_replace(' ', '', $array2[1]) . $contador . substr($_POST['tabla'], 0, 5) ?>" style="display: none">
                                                                        <input class="form-control" type="<?= ($array1[1] == 'textarea' ? 'text' : $array1[1]) ?>" oninput="let value = this.type === 'checkbox' ? (this.checked ? 'on' : null) : this.value; automaticUpdate(value, '<?= $array2[1] ?>', '<?= $_POST['tabla'] ?>', '<?= $dat[$primaryKey] ?>'); $('#cont_<?= str_replace(' ', '', $array2[1]) . $contador . substr($_POST['tabla'], 0, 5) ?>').html(value)" value="<?= $dat[$array2[1]] ?>" <?= ($array1[1] == 'checkbox' ? ($dat[$array2[1]] == 'on' ? 'checked' : '') : '') ?> <?= ($array1[1] == 'number' ? 'step="0.01"' : '') ?>>
                                                                    </div>
                                                                </td>
                                                            <?php } ?>
                                                            <td style="width:20%;">
                                                                <button class="btn btn-danger" onclick="automaticUpdate(0, 'activo','<?= $_POST['tabla'] ?>','<?= $dat[$primaryKey] ?>'); $(this.parentNode.parentNode).remove();updateTableConfigOptions('table-<?= $rand ?>', '<?= $_POST['tabla'] . '|*|' . $_POST['campos'] ?>')"><i class="fa fa-trash"></i></button>
                                                                <button class="btn btn-primary" onclick="
                                                                if (this.edit == true) {
                                                                    this.edit = false;
                                                                    this.className = 'btn btn-primary';
                                                                    document.querySelectorAll('[class=edit<?= $contador ?>]').forEach(element => {
                                                                        element.style.display = 'none';
                                                                    });
                                                                    document.querySelectorAll('[class=cont<?= $contador ?>').forEach(element => {
                                                                        element.style.display = 'block';
                                                                    });
                                                                } else {
                                                                    this.edit = false;
                                                                    this.className = 'btn btn-info';
                                                                    document.querySelectorAll('[class=edit<?= $contador ?>]').forEach(element => {
                                                                        element.style.display = 'block';
                                                                    });
                                                                    document.querySelectorAll('[class=cont<?= $contador ?>').forEach(element => {
                                                                        element.style.display = 'none';
                                                                    });
                                                                }
                                                                ">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach ?>
                                                <?php endif ?>
                                            <?php endif ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $("#table-<?= $rand ?>").DataTable();
        <?php if (!empty($_POST['referencesSelect'])) : ?>
            new MutationObserver(function(e) { // si hay un cambio en el modal pasa por esta funcion
                cargarOpciones('<?= $_POST['tabla'] ?>', '', '<?= $primaryKey ?>', 'nombre', '-', '<?= $_POST['referencesSelect'] ?>', $('<?= $_POST['referencesSelect'] ?>').val(), '', '<?= $primaryKey ?>');
            }).observe(document.getElementById('modalConfigOption_<?= $_POST['tabla'] ?>'), {
                attributes: true,
                childList: true
            })
        <?php endif ?>
    </script>

<?php elseif ($_GET['accion'] == 'updateTableConfigOptions') : ?>
    <?php $config = explode("|*|", $_POST['Rcampos']) ?>
    <?php
    $queryTable = mysqli_query($conn, "SHOW columns from {$config[0]} where `Key` = 'PRI'");
    if (!empty(mysqli_num_rows($queryTable))) {
        $fetchTable = mysqli_fetch_array($queryTable);
        $primaryKey = $fetchTable['Field'];
    } else {
        $primaryKey = "id";
    }
    ?>
    <?php $query = mysqli_query($conn, "SELECT * from {$config[0]} where 1=1 and activo = 1") ?>
    <?php $campos = explode("|/|", $config[1]) ?>

    <?php if (!empty(mysqli_num_rows($query))) : ?>
        <?php $contador = 0 ?>
        <?php foreach ($query as $dat) : ?>
            <?php $contador += 1 ?>
            <tr>
                <?php for ($i = 0; $i < count($campos); $i++) { ?>
                    <?php $array1 = explode(":", $campos[$i]) ?>
                    <?php $array2 = explode(">", $array1[0]) ?>
                    <td>
                        <div class="cont<?= $contador ?>" id="cont_<?= str_replace(' ', '', $array2[1]) . $contador . substr($config[0], 0, 5) ?>"><?= $dat[$array2[1]] ?></div>
                        <div class="edit<?= $contador ?>" id="edit_<?= str_replace(' ', '', $array2[1]) . $contador . substr($config[0], 0, 5) ?>" style="display: none">
                            <input class="form-control" type="<?= ($array1[1] == 'textarea' ? 'text' : $array1[1]) ?>" oninput="let value = this.type === 'checkbox' ? (this.checked ? 'on' : null) : this.value; automaticUpdate(value, '<?= $array2[1] ?>', '<?= $config[0] ?>', '<?= $dat[$primaryKey] ?>'); $('#cont_<?= str_replace(' ', '', $array2[1]) . $contador . substr($config[0], 0, 5) ?>').html(value)" value="<?= $dat[$array2[1]] ?>" <?= ($array1[1] == 'checkbox' ? ($dat[$array2[1]] == 'on' ? 'checked' : '') : '') ?> >
                        </div>
                    </td>
                <?php } ?>
                <td class="row">
                    <button class="btn btn-danger" onclick="automaticUpdate(0, 'activo', '<?= $config[0] ?>', '<?= $dat[$primaryKey] ?>'); $(this.parentNode.parentNode).remove(); updateTableConfigOptions('<?= $_POST['id_table'] ?>', '<?= $_POST['Rcampos'] ?>')"><i class="fa fa-trash"></i></button>
                    <button class="btn btn-primary" onclick="
                        if (this.edit == true) {
                            this.edit = false;
                            this.className = 'btn btn-primary';
                            document.querySelectorAll('[class=edit<?= $contador ?>]').forEach(element => {
                                element.style.display = 'none';
                            });
                            document.querySelectorAll('[class=cont<?= $contador ?>').forEach(element => {
                                element.style.display = 'block';
                            });
                        } else {
                            this.edit = true;
                            this.className = 'btn btn-info';
                            document.querySelectorAll('[class=edit<?= $contador ?>]').forEach(element => {
                                element.style.display = 'block';
                            });
                            document.querySelectorAll('[class=cont<?= $contador ?>').forEach(element => {
                                element.style.display = 'none';
                            });
                        }
                        "><i class="fas fa-edit"></i></button>
                </td>
            </tr>
        <?php endforeach ?>
    <?php endif ?>
<?php endif ?>