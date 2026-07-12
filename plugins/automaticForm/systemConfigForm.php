<?php
include '../../php/conn.php';
include '../../php/funciones.php';

date_default_timezone_set($_POST['timezone']);


$validar = [
    "creadoroptions"
];


?>

<?php if ($_GET['accion'] == "create"):
    
    $datos = [];
    $queryC = mysqli_query($conn, "SELECT * from configElement where t = '{$_POST['t']}' order by id asc");
    $queryL = mysqli_query($conn, "SHOW columns from configElement where `Field` like '%label_%'");
    $queryE = mysqli_query($conn, "SHOW columns from configElement where `Field` like '%element_%'");
    $i = -1;
    foreach ($queryC as $datC) {
        $i += 1;
        $datos["col"][$i] = $datC['col'];
        $datos["ident"][$i] = $datC['id'];
        if (!empty(mysqli_num_rows($queryL))) {
            $datos["label"][$i] = [];
            $datos["label"][$i]["create"] = "label";
            foreach ($queryL as $datL) {
                $x = explode("_", $datL['Field']);
                if (!empty($datC[$datL['Field']])) {
                    $datos["label"][$i][$x[1]] = $datC[$datL['Field']];
                }
            }
        }
        if (!empty(mysqli_num_rows($queryE))) {
            $datos["element"][$i] = [];
            $datos["element"][$i]["create"] = $datC['element'];
            foreach ($queryE as $datE) {
                $x = explode("_", $datE['Field']);
                if (!empty($datC[$datE['Field']])) {
                    $datos["element"][$i][$x[1]] = $datC[$datE['Field']];
                }
            }
        }
    }
    echo json_encode($datos);

elseif ($_GET['accion'] == "modal"): ?>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="overlay" data-reload-modal="content"><i class="fas fa-2x fa-sync fa-spin"></i></div>
            <div class="modal-header">
                <h4 class="modal-title">Creador de Campos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="ribbon-wrapper"><div class="ribbon bg-primary">BETA</div></div>
            <div class="modal-body">
                <table class="table" data-table-ident="<?= $_POST['identificador'] ?>">
                    <thead>
                        <tr>
                            <th>tipo de campo</th>
                            <th>Label</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $query = mysqli_query($conn, "SELECT * FROM configElement where t = '{$_POST['table']}'") ?>
                        <?php foreach ($query as $dat): ?>
                            <tr>
                                <th><?= funcionMaster($dat['element_type'], "tag", "nom", "creadoroptions", ["returnConsult" => false, "like" => true]) ?></th>
                                <th><?= $dat['label_html'] ?></th>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>tipo de campo</th>
                            <th>Label</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="activeCreateElement('<?= $_POST['identificador'] ?>', '<?= $_POST['table'] ?>', '<?= $_POST['contentReferences'] ?>')">Crear Nuevo</button>
            </div>
        </div>
    </div>
    <script>
        $(`[data-table-ident="<?= $_POST['identificador'] ?>"]`).DataTable();
    </script>
<?php elseif ($_GET['accion'] == "creator"): ?>
    <script>
        cargarOpciones(`creadoroptions`, `estado = 1`, `tag`, `nom`, ``, `#elements`, '', '', 'tag asc', `denysbot_sistema`);
    </script>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="overlay" data-reload-modal="content"><i class="fas fa-2x fa-sync fa-spin"></i></div>
            <div class="modal-header">
                <h4 class="modal-title">Creador de Campos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="ribbon-wrapper"><div class="ribbon bg-primary">BETA</div></div>
            <div class="modal-body">
                <!-- <form action="javascript:formularioAutomatico(form_<?= $_POST["identificador"] ?>, 'configElement', '1')" id="form_<?= $_POST["identificador"] ?>"> -->
                <form id="form_<?= $_POST["identificador"] ?>">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex p-0"></div>
                                <div class="card-body">
                                    <label for="">campo a creaar</label>
                                    <select onchange='crearElemento(this, this.value, "<?= $_POST["identificador"] ?>")' class="form-control" id="elements">
                                        <?php $querycreadoroptions = mysqli_query($conn, "SELECT * FROM creadoroptions where estado = 1 order by tag asc") ?>
                                        <option value="">Seleccione</option>
                                        <?php foreach ($querycreadoroptions as $datcreadoroptions): ?>
                                            <option value="<?= $datcreadoroptions['tag'] ?>"><?= $datcreadoroptions['nom'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                        <div class="d-none" data-reload-modal="configElement"><i class="fas fa-2x fa-sync fa-spin"></i><b>Selecione una opción...</b></div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="card">
                                        <div class="card-header d-flex p-0">
                                            <h3 class="card-title p-3">Tabs</h3>
                                            <ul class="nav nav-tabs ml-auto p-2">
                                                <li class="nav-item"><a class="nav-link" href="#tab_1" data-toggle="tab">Basic</a></li>
                                                <li class="nav-item"><a class="nav-link disabled" href="#tab_2" data-toggle="tab">Tab 2</a></li>
                                                <li class="nav-item"><a class="nav-link disabled" href="#tab_3" data-toggle="tab">Tab 3</a></li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab-content">
                                                <div class="tab-pane" id="tab_1">
                                                    <?php // para agregar nuevos atributos fue como los facil que se ocurrio  ?>
                                                    <?php // label_atributo  ?>
                                                    <?php // element_atributo  ?>
                                                    <?php // todos los valores que estan en este apartado los tomo como un por defecto  ?>
                                                    <div class="form-group" data-config-position="0" data-config-status="true">
                                                        <!-- <label>Element</label> -->
                                                        <input class="form-control" type="hidden" name="datos[element]">
                                                    </div>
                                                    <div class="form-group" data-config-position="1" data-config-status="true">
                                                        <label>Label</label>
                                                        <input class="form-control" type="text" name="datos[label_html]" data-ident="label" data-config="html" oninput="editarElemento(this, 'label', '<?= $_POST['identificador'] ?>')" value="test">
                                                    </div>
                                                    <div class="form-group" data-config-position="2" data-config-status="true">
                                                        <label>Placeholder</label>
                                                        <input class="form-control" type="text" name="datos[element_placeholder]" data-ident="element" data-config="placeholder" oninput="editarElemento(this, 'element', '<?= $_POST['identificador'] ?>')" value="test">
                                                    </div>
                                                    <div class="form-group" data-config-position="3" data-config-status="true">
                                                        <!-- <label>Class</label> -->
                                                        <input class="form-control" type="hidden" name="datos[element_class]" data-ident="element" data-config="class" oninput="editarElemento(this, 'element', '<?= $_POST['identificador'] ?>')" value="form-control">
                                                    </div>
                                                    <div class="form-group" data-config-position="4" data-config-status="true">
                                                        <!-- <label>Tipo</label> -->
                                                        <input class="form-control" type="hidden" name="datos[element_type]" data-ident="element" data-config="type" oninput="editarElemento(this, 'element', '<?= $_POST['identificador'] ?>')" value="">
                                                    </div>
                                                    <div class="form-group" data-config-position="5" data-config-status="true">
                                                        <label>Ahora le pregunto a jose</label>
                                                        <?php $valido = [
                                                            4 => "Ocupar un tercio",
                                                            6 => "Ocupar la mitad",
                                                            12 => "Ocupar campo completo"
                                                        ] ?>
                                                        <select name="datos[col]" class="form-control">
                                                            <?php foreach ($valido as $key => $value): ?>
                                                                <option value="col-12 col-md-<?= $key ?>" <?= $key == 12 ? "selected" : "" ?>><?= $value ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="tab-pane active" id="tab_2">...</div> <!-- solo decoración no se que tanto tiene un creador -->
                                                <div class="tab-pane" id="tab_3">...</div> <!-- solo decoración no se que tanto tiene un creador -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h1>Resultado</h1>
                                    <div data-test="<?= $_POST['identificador'] ?>"></div>
                                </div>
                                <input type="hidden" name="datos[t]" value="<?= $_POST['table'] ?>">
                                <div class="col-12"><button class="btn btn-success">Agregar</button></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="activeCreateElement('<?= $_POST['identificador'] ?>')">Actualizar</button>
            </div>
        </div>
    </div>
    <script>
        $(`#form_<?= $_POST["identificador"] ?>`).automaticForm({
            type: 1,
            table: 'configElement',
            contentPage: [{
                "content": "portada.php",
                "title": ""
            }],
            sweetalert2: true
        });
    </script>
<?php endif ?>