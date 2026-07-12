// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Descipcion: Valida la session
// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function valSession() {
    $.ajax("ajax_valSession.php", {
        dataType: "JSON",
        type: "POST",
        data: {
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
        },
        success: function(response) {
            if (response.Estado == false) {
                response.Estado
                Swal.fire("Session finalizada", "", "info");
                // window.location.replace("exit.php");
                setTimeout(() => {
                    window.location.replace("../");
                }, 1500);
            }
        }
    });
}

// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Descipcion: Carga el opciones a un select con una consulta
// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function cargarOpciones(Tabla, Condicion, idValue, campoAMostrar, separarCon, cargarOpcionesEn, selectedOption, group, orden, DB = false, attrs = false) {
    $.ajax("/php/ajax_cargarOpciones.php", {
        type: "POST",
        data: {
            Tabla: Tabla,
            Condicion: Condicion,
            idValue: idValue,
            campoAMostrar: campoAMostrar,
            separarCon: separarCon,
            selectedOption: selectedOption,
            group: group,
            orden: orden,
            DB: DB,
            attrs: attrs,
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone // timezone para el pais,

        },
        success: function(response) {
            $(cargarOpcionesEn).html(response);
        }
    })
}
// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Descipcion: quita cualquier setInterval que se este ejecutando por si acaso
// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function destroyInterval() {
    var intervalActive = window.setInterval(function() {}, 0);
    while (intervalActive--) {
        window.clearInterval(intervalActive);
    }
}
// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Descipcion: Actualiza el contenido de un registro
// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function automaticUpdate(valor, campo, tabla, idUpdate, reload) {
    $.ajax("/plugins/automaticForm/automaticUpdate.php", {
        
        type: "POST",
        data: {
            valor: valor,
            campo: campo,
            tabla: tabla,
            idUpdate: idUpdate,
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone // timezone para el pais
        },
        success: function (response) {
            response = response.trim();
                if (response == "false") {
                    // Swal.fire("Ah ocurrido un error al actualizar los datos", "", "info");
                } else if (response == "true") {
                    if (reload !== undefined) {
                        // Swal.fire("Procesado", "", "success");
                        window.location.href = reload;
                    }
                } else {
                    // Swal.fire("Error de respuesta", "", "info");
                }
        },
    });
}


function alerts(config, sweetalert2 = false) {
    if (config.update !== undefined) {
        Swal.fire({
            title: config.title,
            text: config.text,
            icon: config.icon,
            buttons: true,
            showCancelButton: true,
            allowOutsideClick: false,
            cancelButtonColor: '#d33',
            confirmButtonColor: '#3085d6',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Confirmar',
          })
          .then((seConfirmo) => {
            if (seConfirmo.isConfirmed) {
                       let update = config.update.split("|/|");
                 console.log(update);
                automaticUpdate(update[0], update[1], update[2], update[3], update[4]);
            } else {
                e.preventDefault();
            }
          });
    } else {
        Swal.fire(config.title, config.text, config.icon);
        // window.alert(`${config.icon ? `${config.icon.toUpperCase()}: ` : ''} ${config.title ? config.title + '\n' : ''} ${config.text ? config.text : ''}`);    
    }

    if (config.page !== undefined) {
        window.alert('Sera redirigido ahora');
        window.location.href = config.page;
    }
    
}

function automaticUpdateAlert(mensaje, valor, campo, tabla, idUpdate, reload) {
    Swal.fire({
        title: '¿Desea ' + mensaje + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si',
        cancelButtonText: 'No, cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            automaticUpdate(valor, campo, tabla, idUpdate, reload);
        }
    });
}