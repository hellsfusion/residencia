<?php
include __DIR__ . '/../php/header.php';
include __DIR__ . '/../php/error_reporting.php';
$page = 'calendario';
$tabla = 'calendario';
// cargar datos

$rowCalendario = [];

// consulta calendario
$querySuspensiones = "SELECT * from $tabla
    where 1=1
    and activo = '1'
    ";
$resultSuspensiones = mysqli_query($conn, $querySuspensiones);
if ($resultSuspensiones) {
 while ($row = mysqli_fetch_assoc($resultSuspensiones)) {
  $rowCalendario[] = $row;
 }
}

// 10 eventos unicos (nombres distintos) para mostrar en el panel izquierdo
$ultimosEventos = [];
foreach ($rowCalendario as $evento) {
 if (!in_array($evento['motivo'], array_column($ultimosEventos, 'motivo'))) {
  $ultimosEventos[] = $evento;
 }
}
// var_dump($rowCalendario);
$ultimosEventos = array_slice($ultimosEventos, 0, 10);

// var_dump($ultimosEventos);



?>
<!-- fullCalendar -->
<!-- <link rel="stylesheet" href="<?= $Base ?>/plugins/fullcalendar/main.css"> -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
 <!-- Content Header (Page header) -->
 <section class="content-header">
 </section>

 <!-- Main content -->
 <section class="content">
  <div class="container-fluid">
   <div class="row">
    <div class="col-md-3">
     <div class="sticky-top mb-3">
      <!-- /.card -->
      <div class="card">
       <div class="card-header">
        <h3 class="card-title">Agregar Eventos</h3>
       </div>
       <div class="card-body">
        <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
         <ul class="fc-color-picker" id="color-chooser">
          <li><a class="text-primary" href="#"><i class="fas fa-square"></i></a></li>
          <li><a class="text-warning" href="#"><i class="fas fa-square"></i></a></li>
          <li><a class="text-success" href="#"><i class="fas fa-square"></i></a></li>
          <li><a class="text-danger" href="#"><i class="fas fa-square"></i></a></li>
          <li><a class="text-secondary" href="#"><i class="fas fa-square"></i></a></li>
         </ul>
        </div>
        <!-- /btn-group -->
        <div class="input-group">
         <input id="new-event" type="text" class="form-control" placeholder="">

         <div class="input-group-append">
          <button id="add-new-event" type="button" class="btn btn-primary">Agregar</button>
         </div>
         <!-- /btn-group -->
        </div>
        <!-- /input-group -->
       </div>
      </div>
      <div class="card">
       <div class="card-header">
        <h4 class="card-title">Eventos Comunes</h4>
       </div>
       <div class="card-body">
        <!-- the events -->
        <div id="external-events">
         <?php foreach ($ultimosEventos as $ue) { ?>
          <div class="external-event" style="background-color: <?= $ue['color'] ?>; color: white;"><?= $ue['motivo'] ?></div>
         <?php } ?>
        </div>
       </div>
       <!-- /.card-body -->
      </div>

     </div>
    </div>
    <!-- /.col -->
    <div class="col-md-9">
     <div class="card card-primary">
      <div class="card-body p-0">
       <!-- THE CALENDAR -->
       <div id="calendar"></div>
      </div>
      <!-- /.card-body -->
     </div>
     <!-- /.card -->
    </div>
    <!-- /.col -->
   </div>
   <!-- /.row -->
  </div><!-- /.container-fluid -->
 </section>
 <!-- /.content -->
</div>
<!-- Modal para crear/editar evento -->
<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-hidden="true">
 <div class="modal-dialog" role="document">
  <div class="modal-content">
   <div class="modal-header">
    <h5 class="modal-title">Evento</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
     <span aria-hidden="true">&times;</span>
    </button>
   </div>
   <div class="modal-body">
    <form id="eventForm">
     <div class="form-group">
      <label>Título</label>
      <input type="text" id="evtTitle" class="form-control" required>
     </div>
     <div class="form-group">
      <label>Inicio</label>
      <input type="datetime-local" id="fechaInicio" class="form-control" required>
     </div>
     <div class="form-group">
      <label>Fin</label>
      <input type="datetime-local" id="fechaFin" class="form-control" required>
     </div>
     <div class="form-group">
      <label>Color</label>
      <input type="color" id="evtColor" class="form-control" value="#3c8dbc">
     </div>
     <input type="hidden" id="evtId">
    </form>
   </div>
   <div class="modal-footer">
    <button type="button" id="deleteEventBtn" class="btn btn-danger">Eliminar</button>
    <button type="button" id="saveEventBtn" class="btn btn-primary">Guardar</button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
   </div>
  </div>
 </div>
</div>
<?php
// session_destroy();
// var_dump($_SESSION);
include __DIR__ . '/../php/footer.php';
?>
<!-- fullCalendar 2.2.5 -->
<!-- <script src="<?= $Base ?>/plugins/moment/moment.min.js"></script> -->
<!-- <script src="<?= $Base ?>/plugins/fullcalendar/main.js"></script> -->

<!-- Estilos: deshabilitar visualmente casillas anteriores -->
<style>
 /* FullCalendar agrega la clase .fc-past a los días anteriores */
 .fc-daygrid-day.fc-past {
  opacity: 0.6;
 }
</style>
</style>

<script>
 $(function() {

  // Fecha de hoy en formato YYYY-MM-DD para validaciones
  var todayStr = new Date().toISOString().slice(0, 10);

  // Utilidades para datetime-local (formato YYYY-MM-DDTHH:MM)
  function pad(n) {
   return n < 10 ? '0' + n : n
  }

  function formatLocal(dt) {
   return dt.getFullYear() + '-' + pad(dt.getMonth() + 1) + '-' + pad(dt.getDate()) + 'T' + pad(dt.getHours()) + ':' + pad(dt.getMinutes());
  }

  function setCreationDefaultsIfNeeded() {
   // Si estamos en modo creación (sin evtId) y no hay dropInfo, usar ahora y +1h
   if ($('#evtId').val()) return; // edición
   var dropInfo = $('#eventModal').data('dropInfo');
   if (dropInfo) return; // si viene de un drop, respetar los valores ya cargados
   var now = new Date();
   var anHour = new Date(now.getTime() + 60 * 60 * 1000);
   $('#fechaInicio').val(formatLocal(now));
   $('#fechaFin').val(formatLocal(anHour));
  }

  /* initialize the external events
  -----------------------------------------------------------------*/
  function ini_events(ele) {
   ele.each(function() {

    // create an Event Object (https://fullcalendar.io/docs/event-object)
    // it doesn't need to have a start or end
    var eventObject = {
     title: $.trim($(this).text()) // use the element's text as the event title
    }

    // store the Event Object in the DOM element so we can get to it later
    $(this).data('eventObject', eventObject)

    // make the event draggable using jQuery UI
    $(this).draggable({
     zIndex: 1070,
     revert: true, // will cause the event to go back to its
     revertDuration: 0 //  original position after the drag
    })

   })
  }

  ini_events($('#external-events div.external-event'))

  /* initialize the calendar
  -----------------------------------------------------------------*/
  //Date for the calendar events (dummy data)
  var date = new Date()
  var d = date.getDate(),
   m = date.getMonth(),
   y = date.getFullYear()

  var Calendar = FullCalendar.Calendar;
  var Draggable = FullCalendar.Draggable;

  var containerEl = document.getElementById('external-events');
  var checkbox = document.getElementById('drop-remove');
  var calendarEl = document.getElementById('calendar');

  // initialize the external events
  // -----------------------------------------------------------------

  new Draggable(containerEl, {
   itemSelector: '.external-event',
   eventData: function(eventEl) {
    var tempId = 'temp-' + Date.now() + '-' + Math.random().toString(36).slice(2);
    eventEl.dataset.tempId = tempId;
    return {
     title: eventEl.innerText,
     backgroundColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
     borderColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
     textColor: window.getComputedStyle(eventEl, null).getPropertyValue('color'),
     id: tempId
    };
   }
  });

  var calendar = new Calendar(calendarEl, {
   locale: 'es',
   buttonText: {
    today: 'Hoy',
    month: 'Mes',
    week: 'Semana',
    day: 'Dia',
    list: 'Agenda',
    prev: 'Anterior',
    next: 'Siguiente',
    allDay: 'Todo el dia',
   },
   headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,listWeek'
   },
   themeSystem: 'bootstrap',
   events: [
    <?php
    foreach ($rowCalendario as $evento) {
     echo "{ title: '" . $evento['motivo'] . "', start: '" . $evento['fechaInicio'] . "', end: '" . $evento['fechaFin'] . "', backgroundColor: '" . $evento['color'] . "', borderColor: '" . $evento['color'] . "', id: '" . $evento['id'] . "' },";
    }
    ?>
   ],
   eventClick: function(info) {
    // Aquí puedes acceder al id del evento
    var eventId = info.event.id;
    console.log(eventId);
    // Si es evento pasado, no permitir editar/eliminar
    var eventStart = info.event.startStr ? info.event.startStr.slice(0, 10) : null;
    if (eventStart && eventStart < todayStr) {
     Swal.fire('No permitido', 'No se puede modificar eventos pasados', 'warning');
     return;
    }
    // abrir modal para editar (preload motivo, fechaInicio, fechaFin, color)
    $('#evtId').val(info.event.id);
    $('#evtTitle').val(info.event.title);
    $('#fechaInicio').val(info.event.start ? formatLocal(info.event.start) : '');
    $('#fechaFin').val(info.event.end ? formatLocal(info.event.end) : '');
    $('#evtColor').val(info.event.backgroundColor || '#3c8dbc');
    $('#deleteEventBtn').show();
    $('#eventModal').modal('show');
   },
   editable: true,
   droppable: true,
   drop: function(info) {
    // prevenir drop en fechas anteriores a hoy
    var dropDate = info.dateStr || (info.date ? info.date.toISOString().slice(0, 10) : null);
    if (dropDate && dropDate < todayStr) {
     Swal.fire('No permitido', 'No se pueden crear eventos en fechas anteriores a hoy', 'warning').then(function() {
      // eliminar el evento temporal agregado al calendario
      try {
       var evt = calendar.getEventById('newEvent');
       if (evt) evt.remove();
      } catch (e) {
       console.error('No se pudo eliminar el evento temporal:', e);
      }
     });
     return;
    }
    // prevenir drop en fechas anteriores a hoy y abrir modal para pedir hora inicio/fin
    // prefill modal with drop date
    var title = info.draggedEl ? info.draggedEl.innerText : '';
    $('#evtId').val('');
    $('#evtTitle').val(title);
    // default times (usar hora local). Si info.date tiene hora 00:00 (all-day), usar la hora actual
    var startLocal, endLocal;
    if (info.date) {
     var startCandidate = new Date(info.date.getTime());
     // si la fecha viene sin hora (00:00) asignar la hora actual del usuario
     if (startCandidate.getHours() === 0 && startCandidate.getMinutes() === 0) {
      var now = new Date();
      startCandidate.setHours(now.getHours(), now.getMinutes(), 0, 0);
      startCandidate.setHours(startCandidate.getHours() + 1);
     }
     var endCandidate = new Date(startCandidate.getTime() + 60 * 60 * 1000);
     startLocal = formatLocal(startCandidate);
     endLocal = formatLocal(endCandidate);
     // sumale una hora a cada una

    } else {
     startLocal = dropDate + 'T09:00';
     endLocal = dropDate + 'T10:00';
    }

    $('#fechaInicio').val(startLocal);
    $('#fechaFin').val(endLocal);
    $('#evtColor').val(window.getComputedStyle(info.draggedEl, null).getPropertyValue('background-color') || '#3c8dbc');
    $('#deleteEventBtn').hide();
    $('#eventModal').data('dropInfo', info).modal('show');
   },
   eventDrop: function(info) {
    // prevenir mover eventos a fechas pasadas
    console.log('eventDrop');
    var newStart = info.event.startStr ? info.event.startStr.slice(0, 10) : null;
    if (newStart && newStart < todayStr) {
     Swal.fire('No permitido', 'No se pueden mover eventos a fechas pasadas', 'warning');
     if (typeof info.revert === 'function') info.revert();
     return;
    }
    // abrir modal para ajustar hora si se desea
    $('#evtId').val(info.event.id);
    $('#evtTitle').val(info.event.title);
    $('#fechaInicio').val(info.event.start ? formatLocal(info.event.start) : '');
    $('#fechaFin').val(info.event.end ? formatLocal(info.event.end) : '');
    $('#evtColor').val(info.event.backgroundColor || '#3c8dbc');
    $('#deleteEventBtn').hide();
    $('#eventModal').data('dropInfo', null).modal('show');
    // after save, actionEvent will be called; for now do not auto-send
    info.revert();
   },
   eventResize: function(info) {
    // prevenir redimensionar eventos en fechas pasadas
    console.log('eventResize');
    var newStart = info.event.startStr ? info.event.startStr.slice(0, 10) : null;
    var newEnd = info.event.endStr ? info.event.endStr.slice(0, 10) : null;
    if ((newStart && newStart < todayStr) || (newEnd && newEnd < todayStr)) {
     Swal.fire('No permitido', 'No se pueden modificar eventos en fechas pasadas', 'warning');
     if (typeof info.revert === 'function') info.revert();
     return;
    }
    // abrir modal para ajustar horas
    $('#evtId').val(info.event.id);
    $('#evtTitle').val(info.event.title);
    $('#fechaInicio').val(info.event.start ? formatLocal(info.event.start) : '');
    $('#fechaFin').val(info.event.end ? formatLocal(info.event.end) : '');
    $('#evtColor').val(info.event.backgroundColor || '#3c8dbc');
    $('#deleteEventBtn').hide();
    $('#eventModal').data('dropInfo', null).modal('show');
    info.revert();
   }
  });

  calendar.render();
  // expose calendar globally so handlers outside this scope can call methods like addEvent
  window.calendar = calendar;
  // cuando se muestre el modal, asegurarse que el modal de creación rellene ahora y +1h si corresponde
  $('#eventModal').on('show.bs.modal', function() {
   setCreationDefaultsIfNeeded();
   $(this).data('saved', false);
  });
  // si se cierra el modal sin guardar y venía de un drop, eliminar el evento temporal
  $('#eventModal').on('hidden.bs.modal', function() {
   var dropInfo = $(this).data('dropInfo');
   var saved = $(this).data('saved');
   if (dropInfo && !saved) {
    try {
     var evt = calendar.getEventById('newEvent');
     if (evt) evt.remove();
    } catch (e) {
     console.error('No se pudo eliminar evento temporal al cancelar:', e);
    }
   }
   $(this).removeData('dropInfo');
   $(this).removeData('saved');
   $('#evtId').val('');
  });
  // $('#calendar').fullCalendar()

  /* ADDING EVENTS */
  var currColor = '#3c8dbc' //Red by default
  // Color chooser button
  $('#color-chooser > li > a').click(function(e) {
   e.preventDefault()
   // Save color
   currColor = $(this).css('color')
   // Add color effect to button
   $('#add-new-event').css({
    'background-color': currColor,
    'border-color': currColor
   })
  })
  $('#add-new-event').click(function(e) {
   e.preventDefault()
   // Get value and make sure it is not null
   var val = $('#new-event').val()
   if (val.length == 0) {
    return
   }

   // Create events
   var event = $('<div />')
   event.css({
    'background-color': currColor,
    'border-color': currColor,
    'color': '#fff'
   }).addClass('external-event')
   event.text(val)
   $('#external-events').prepend(event)

   // Add draggable funtionality
   ini_events(event)

   // Remove event from text input
   $('#new-event').val('')
  })

  // Función actionEvent
  // Función actionEvent
  function actionEvent(info, type) {
   console.log(info);
   let idUpdate = 0;
   let pasa = 0;
   let formData;

   // type = 1: Nuevo evento
   // type = 2: Modificar evento
   // Obtener valores de inicio/fin desde el modal (si están presentes)
   var startStr = $('#fechaInicio').val();
   var endStr = $('#fechaFin').val();
   var startDate = startStr ? new Date(startStr) : (info && info.date ? new Date(info.date) : null);
   var endDate = endStr ? new Date(endStr) : (info && info.date ? new Date(info.date) : null);

   if (type == 1) {
    formData = new FormData();
    formData.append('datos[motivo]', info && info.draggedEl ? info.draggedEl.innerText : ($('#evtTitle').val() || '')); // título
    formData.append('datos[fechaInicio]', startStr || '');
    formData.append('datos[fechaFin]', endStr || '');
    formData.append('datos[color]', info && info.backgroundColor ? info.backgroundColor : ($('#evtColor').val() || '#3c8dbc'));
    formData.append('datos[activo]', 1);
    pasa = 1;

    // validaciones
    if (!startDate || !endDate) {
     alert('Fechas inválidas');
     pasa = 0;
    }
    if (pasa && startDate > endDate) {
     alert('La fecha de inicio no puede ser mayor a la fecha de fin');
     pasa = 0;
    }
    if (pasa && startDate < new Date()) {
     alert('La fecha no puede ser menor a la fecha actual');
     pasa = 0;
    }
   }

   if (type == 2) {
    formData = new FormData();
    formData.append('datos[motivo]', info && info.event ? info.event.title : $('#evtTitle').val());
    formData.append('datos[fechaInicio]', startStr || (info && info.event && info.event.startStr ? info.event.startStr : ''));
    formData.append('datos[fechaFin]', endStr || (info && info.event && info.event.endStr ? info.event.endStr : ''));
    formData.append('datos[color]', info && info.event ? info.event.backgroundColor : ($('#evtColor').val() || '#3c8dbc'));
    formData.append('datos[activo]', 1);
    idUpdate = info && info.event ? info.event.id : 0;
    info.id = idUpdate;
    pasa = 1;

    // validaciones para editar
    if (!startDate || !endDate) {
     alert('Fechas inválidas');
     pasa = 0;
    }
    if (pasa && startDate > endDate) {
     alert('La fecha de inicio no puede ser mayor a la fecha de fin');
     pasa = 0;
    }
    if (pasa && startDate < new Date()) {
     alert('La fecha no puede ser menor a la fecha actual');
     pasa = 0;
    }
   }

   if (pasa == 1) {
    $.ajax({
     url: `../../plugins/automaticForm/automaticForm.php?table=<?= $tabla ?>&type=${type}&idUpdate=${idUpdate}`,
     type: "POST",
     dataType: "JSON",
     processData: false,
     contentType: false,
     cache: false,
     data: formData,
     success: function(data) {
      console.log('actionEvent success', type, data, info);
      if (data.status == true && data.FAID != undefined) {
        var event = calendar.getEventById(info.id);
        var newId = atob(data.FAID);
        var title = formData.get('datos[motivo]');
        var startVal = formData.get('datos[fechaInicio]');
        var endVal = formData.get('datos[fechaFin]');
        var colorVal = formData.get('datos[color]');
        if (event && type == 1) {
         try { event.remove(); } catch (e) { console.warn('remove temp failed', e); }
         calendar.addEvent({
          id: newId,
          title: title,
          start: startVal,
          end: endVal,
          backgroundColor: colorVal,
          borderColor: colorVal
         });
        } else if (event && type == 2) {
         event.setProp('title', title);
         try { event.setStart(startVal); } catch (e) { console.warn('setStart failed', e); }
         try { event.setEnd(endVal); } catch (e) { console.warn('setEnd failed', e); }
         event.setProp('backgroundColor', colorVal);
         event.setProp('borderColor', colorVal);
        } else {
         calendar.addEvent({
          id: newId,
          title: title,
          start: startVal,
          end: endVal,
          backgroundColor: colorVal,
          borderColor: colorVal
         });
        }
        // for safety, request a render and rerender events
        try { calendar.render(); } catch (e) {}
        try { calendar.rerenderEvents(); } catch (e) {}
        $('#eventModal').removeData('tempEventId');
             // recargar pagina / solucion temporal
             // setTimeout(() => {
             //  location.reload();
             // }, 500);
      }
     },
     error: function(xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
     }
    });
   } else {
    // no pasa / no crear o mover el evento
    // si es un evento nuevo, eliminarlo del calendario
    if (type == 1) {
     var event = calendar.getEventById(info.id);
     event.remove();
    }
    // si es un evento modificado, revertirlo a su posición original
    if (type == 2) {
     info.revert();
    }
   }
  }
  // make actionEvent available globally for handlers defined outside this scope
  window.actionEvent = actionEvent;
 })

 // Guardar evento desde modal (crear o editar)
 $('#saveEventBtn').on('click', function() {
  var id = $('#evtId').val();
  var title = $('#evtTitle').val();
  var start = $('#fechaInicio').val();
  var end = $('#fechaFin').val();
  var color = $('#evtColor').val() || '#3c8dbc';
  if (!title || !start || !end) {
   Swal.fire('Faltan datos', 'Completa título, inicio y fin', 'warning');
   return;
  }
  // if editing
  if (id) {
   // find event and update
   var ev = calendar.getEventById(id);
   if (ev) {
    ev.setProp('title', title);
    ev.setStart(start);
    ev.setEnd(end);
    ev.setProp('backgroundColor', color);
    ev.setProp('borderColor', color);
   }
   // preparar info para actionEvent tipo 2
   var fakeInfo = {
    event: ev
   };
   actionEvent(fakeInfo, '2');
  } else {
   // crear evento en calendario (temporal) y llamar actionEvent tipo 1
   var dropInfo = $('#eventModal').data('dropInfo');
   if (dropInfo) {
    // FullCalendar ya creó un evento al hacer drop; actualizarlo en lugar de crear otro
    var existing = calendar.getEventById('newEvent');
    if (existing) {
     existing.setProp('title', title);
     existing.setStart(start);
     existing.setEnd(end);
     existing.setProp('backgroundColor', color);
     existing.setProp('borderColor', color);
    } else {
     // fallback: si no existe, crearlo
     calendar.addEvent({
      title: title,
      start: start,
      end: end,
      backgroundColor: color,
      borderColor: color,
      id: 'newEvent'
     });
    }
    var fakeInfo = dropInfo;
    fakeInfo.id = 'newEvent';
    fakeInfo.backgroundColor = color;
    actionEvent(fakeInfo, '1');
    $('#eventModal').data('saved', true);
   } else {
    // no viene de drop: crear evento manualmente con id temporal para mostrarlo inmediatamente
    var tempId = 'temp-' + Date.now();
    var newEv = calendar.addEvent({
     title: title,
     start: start,
     end: end,
     backgroundColor: color,
     borderColor: color,
     id: tempId
    });
    console.log('manual temp event created', tempId, newEv);
    try { calendar.render(); } catch (e) { console.warn('render failed', e); }
    try { calendar.rerenderEvents(); } catch (e) { console.warn('rerender failed', e); }
    var fakeInfo = {
     draggedEl: {
      innerText: title
     },
     dateStr: start,
     date: new Date(start),
     id: tempId,
     backgroundColor: color
    };
    actionEvent(fakeInfo, '1');
   }
   // limpiar dropInfo después de procesar
   $('#eventModal').removeData('dropInfo');
  }
  $('#eventModal').modal('hide');
 });

 // Eliminar evento desde modal
 $('#deleteEventBtn').on('click', function() {
  var id = $('#evtId').val();
  if (!id) return;
  automaticUpdateAlert('Eliminar este registro', '0', 'activo', '<?= $tabla ?>', id, '');
 });
</script>