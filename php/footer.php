<!-- Modal -->
<div class="modal fade" id="calculadora" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
 <div class="modal-dialog" role="document">
  <div class="modal-content">
   <div class="modal-header">
    <h5 class="modal-title">Calculadora</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
     <span aria-hidden="true">&times;</span>
    </button>
   </div>
   <div class="modal-body center text-center">
    <?php include __DIR__ . '/../pages/calculadora.php' ?>
   </div>
  </div>
 </div>
</div>
<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
 <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->

<!-- Main Footer -->
<!-- <footer class="main-footer">
    <strong>Administración Florida | @Hellsfusion &copy; <?= date('Y') ?>
</footer> -->
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="<?= $Base ?>/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI (needed for .draggable) -->
<script src="<?= $Base ?>/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap -->
<script src="<?= $Base ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?= $Base ?>/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= $Base ?>/dist/js/adminlte.js"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="<?= $Base ?>/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="<?= $Base ?>/plugins/raphael/raphael.min.js"></script>
<script src="<?= $Base ?>/plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="<?= $Base ?>/plugins/jquery-mapael/maps/usa_states.min.js"></script>
<!-- ChartJS -->
<script src="<?= $Base ?>/plugins/chart.js/Chart.min.js"></script>

<!-- automaticForm -->
<!-- agrego plugins automaticForm para cruds automáticos -->
<script src="<?= $Base ?>/plugins/automaticForm/personalizado.js"></script>
<script src="<?= $Base ?>/plugins/automaticForm/automaticForm.js"></script>
<script src="<?= $Base ?>/plugins/automaticForm/tokenMaster.js"></script>
<script src="<?= $Base ?>/plugins/automaticForm/systemConfigForm.js"></script>
<!-- 20 06 2026 - JRodriguez -->

<!-- DataTables  & Plugins -->
<script src="<?= $Base ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= $Base ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= $Base ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= $Base ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= $Base ?>/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= $Base ?>/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= $Base ?>/plugins/jszip/jszip.min.js"></script>
<script src="<?= $Base ?>/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?= $Base ?>/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?= $Base ?>/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?= $Base ?>/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?= $Base ?>/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- fullCalendar 2.2.5 -->
<script src="<?= $Base ?>/plugins/moment/moment.min.js"></script>
<script src="<?= $Base ?>/plugins/fullcalendar/main.js"></script>
<script src="<?= $Base ?>/plugins/jquery-ui/jquery-ui.min.js"></script>

<!-- Summernote -->
<script src="<?= $Base ?>/plugins/summernote/summernote-bs4.min.js"></script>

<!-- editor enriquecido -->
<script src="<?= $Base ?>/plugins/editor/editorNuevo.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!-- apex chart -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<!-- Full CryptoJS library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js"></script>

<script>
 $(function() {
  let tables = document.querySelectorAll('.table');
  tables.forEach(function(table) {
   $(table).DataTable({
    "responsive": true,
    "lengthChange": false,
    "autoWidth": false,
    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
    "language": {
     "lengthMenu": "Mostrar _MENU_ registros por página",
     "zeroRecords": "No se encontraron resultados",
     "info": "Mostrando página _PAGE_ de _PAGES_",
     "infoEmpty": "No hay registros disponibles",
     "infoFiltered": "(filtrado de _MAX_ registros totales)",
     "search": "Buscar:",
     "paginate": {
      "first": "Primero",
      "last": "Último",
      "next": "Siguiente",
      "previous": "Anterior"
     }
    }
   }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
 });
</script>

<script>
 $(function() {
  let summernote = document.querySelectorAll('.summernote');
  summernote.forEach(function(summernote) {
   $(summernote).summernote();
  });
 })
</script>

<!-- funciones de maestro de opciones -->
<script>
 function configOptions(tabla, campos, referencesSelect, nombre) {
  // // // console.log(tabla, campos, referencesSelect);
  $.ajax("<?= $Base ?>php/ajax_configOptions.php?accion=configOptions", {
   type: "POST",
   data: {
    tabla: (tabla !== undefined && tabla !== null ? tabla : 0),
    campos: (campos !== undefined && campos !== null ? campos : 0),
    nombre: (nombre !== undefined && nombre !== null ? nombre : 0),
    referencesSelect: (referencesSelect !== undefined && referencesSelect !== null ? referencesSelect :
     0)
   },
   success: function(response) {
    if (!document.getElementById(`modalConfigOption_${tabla}`)) {
     $('body').append(response);
    } else {
     $(`#modalConfigOption_${tabla}`).replaceWith(response);
    }
   },
   error: function(error) {},
   complete: function() {
    // REMOVEMOS EL DISABLES DEL BOTÓN QUE CORRESPONDA
    // INDICANDO QUE SU MODAL SE HA CARGADO
    // EVITAREMOS QUE INTENTE ABRIR CUANDO SU MODAL NO EXISTE
    if ($(`[data-target="#modalConfigOption_${tabla}"]`)) {
     $(`[data-target="#modalConfigOption_${tabla}"]`).attr("disabled", false);
    }
   }
  })
 }
</script>
<script>
 function addOptions(form, tabla, tipo, table) {
  $.ajax("<?= $Base ?>./plugins/automaticForm/automaticForm.php?table=" + tabla + "&type=" + tipo, {
   type: "POST",
   dataType: "JSON",
   processData: false,
   contentType: false,
   cache: false,
   data: new FormData(form),
   success: function(response) {
    // // console.log(response);
    if (response['Error'] === undefined) {
     if (response['status'] == true) {
      let campos = table.split("---");
      let a = campos[1].split(">");
      for (let index = 0; index < a.length; index++) {
       ncampos = a[index].split(":");
       $(`[name='datos[${ncampos[0]}]']`).val("");
      }
      setTimeout(() => {
       updateTableConfigOptions(campos[0], campos[1]);
      }, 100);
     }
    }
   }
  })
 }
</script>
<script>
 function updateTableConfigOptions(id_table, Rcampos) {
  setTimeout(() => {
   $.ajax("<?= $Base ?>php/ajax_configOptions.php?accion=updateTableConfigOptions", {
    type: "POST",
    data: {
     id_table: id_table,
     Rcampos: Rcampos
    },
    success: function(response) {
     $(`#${id_table}`).DataTable().clear().draw(); // Limpio la tabla ya existente
     $(`#${id_table}`).dataTable().fnDestroy(); // Destruyo
     $(`#${id_table} tbody`).html(response); // cargo los datos
     $(`#${id_table}`).DataTable(); // cargo el DataTable  
    }
   })
  }, 200);
 }
</script>
<!-- funciones de maestro de opciones -->


</body>

</html>