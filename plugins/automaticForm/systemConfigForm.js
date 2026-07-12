function isHTML(str) {
    // instanceof valida si el elemento en un arreglo es valido devuelve true o false
    return str instanceof Element || str instanceof HTMLDocument;
}

function createElem(tag, attrs = null) {
    // esta función sirve tanto para crear nuevos elementos como para editarlos
    // ejemplo: crear
    // createElem(`div`, {"id": "pollito"})
    // output: <div id="pollito"></div>
    // ejemplo: editar
    // createElem(document.getElementById('pollito'), {"id": "pollito"}) -- javascript
    // createElem($("#pollito")[0], {"html": "Hola wenas"}) -- jQuery
    // output: <div id="pollito">Hola wenas</div>
    let newElement = (!isHTML(tag) ? document.createElement(tag) : tag); // valido si es un elemento html, si no es creo lo que envie y si no solo se asigna a la variable
    if (null !== attrs) {
        for (data in attrs) {
            if (["html", "innerHTML"].includes(data)) {
                newElement.textContent = attrs[data]; // html
            } else {
                newElement.setAttribute(data, attrs[data]); // atributos
            }
        }
    }
    return newElement;
}

function activeCreateElement(e, a) {
    $.ajax(`/plugins/automaticForm/systemConfigForm.php?accion=creator`, {
        type: `POST`,
        data: {
            identificador: e,
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
            table: a
        },
        beforeSend: function() {
            $(`[data-reload-modal="content"]`).attr(`class`, `overlay`);
            $(`[data-reload-modal="configElement"]`).attr(`class`, `d-none`);
        },
        success: function(response) {
            $(`#modal-${e}`).html(response);
        },
        complete: function() {
            setTimeout(() => {
                $(`[data-reload-modal="content"]`).attr(`class`, `d-none`);
            }, 1500);
            setTimeout(() => {
                $(`[data-reload-modal="configElement"]`).attr(`class`, `overlay`);
            }, 1500);
        }
    });
}

// function peticiones (url, construct) {
//     return $.ajax(`${url}`, { construct });
// }

function crearElemento(e, a, x) {
    $(`[data-reload-modal="configElement"]`).attr(`class`, `${e.value != "" ? `d-none` : `overlay`}`); // animacion

    let xxx = a.split(":");
    $(`[name="datos[element]"]`).val(`${xxx[0]}`);

    if (xxx.length > 1) {
        $(`[data-ident="element"][data-config="type"]`).val(`${xxx[1]}`);
    } else {
        $(`[data-ident="element"][data-config="type"]`).val(``);
    }

    div = $(`[data-test]`).html(createElem(`div`, { "class": "form-group" }));

    $(div).append(createElem(`label`, { "data-test-label": x }));
    $(`[data-config-status="true"] [data-ident="label"]`).each(function() {
        config = {}
        config[$(this).attr('data-config')] = $(this).val();
        createElem($(`[data-test-label="${x}"]`)[0], config);
    });

    $(div).append(createElem(xxx[0], { "data-test-element": x }));
    $(`[data-config-status="true"] [data-ident="element"]`).each(function() {
        config = {}
        config[$(this).attr('data-config')] = $(this).val();
        createElem($(`[data-test-element="${x}"]`)[0], config);
    });
}

function editarElemento (e, a, x) {
    let arrayAttr = {};
    let config = $(e).attr('data-config');
    let value = $(e).val();
    if (config != "html") {
        // // console.log("atributo");
        arrayAttr[config] = `${value}`;
        createElem($(`[data-test-${a}="${x}"]`)[0], arrayAttr);
    } else {
        // // console.log("html");
        $(`[data-test-${a}]`).html(`${value}`);
    }
}

function loadElementsClient(t, c) {
    $.ajax(`/plugins/automaticForm/systemConfigForm.php?accion=create`, {
        dataType: `JSON`,
        type: `POST`,
        data: {
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
            t: t
        },
        success: function(response) {
            // console.log(response);
            if (undefined !== response.ident) {
                [...response.ident].forEach((sort, ident) => {
                    $(c).append(createElem("div", {
                        "data-client-d": sort,
                        "class": response.col[ident]
                    }));
                    // console.log(c);
                    if (!document.querySelector(`[data-client-l="${sort}"]`)) {
                        // console.log("alo");
                        $(`[data-client-d="${sort}"]`).append(
                            createElem(response.label[ident]["create"], {
                                "data-client-l": sort
                            })
                        );
                    }
                    if (undefined !== response.label[ident]) {
                        // console.log("alo");
                        delete(response.label[ident]["create"]);
                        for (data in response.label[ident]) {
                            arrayl = {};
                            arrayl[data] = response.label[ident][data];
                            createElem($(`[data-client-l="${sort}"]`)[0], arrayl);
                        }
                    }
                    if (!document.querySelector(`[data-client-e="${sort}"]`)) {
                        // console.log("alo");
                        $(`[data-client-d="${sort}"]`).append(
                            createElem(response.element[ident]["create"], {
                                "data-client-e": sort
                            })
                        );
                    }
                    if (undefined !== response.element[ident]) {
                        // console.log("alo");
                        delete(response.element[ident]["create"]);
                        for (data in response.element[ident]) {
                            arraye = {};
                            arraye[data] = response.element[ident][data];
                            arraye["name"] = `datos[client${sort}]`;
                            createElem($(`[data-client-e="${sort}"]`)[0], arraye);
                        }
                    }
                });
            }
        }
    })
}

(function( $ ) { // hice la configuración muy justa, pero cuando tenga ""tiempo"" corrijo eso
    $.fn.creatorForm = function(config = null) {
        if (0 !== this.length) {
            let form = this
            // Y-m-d H:i:s
            let hoy = new Date(),
            fecha = `${hoy.getFullYear()}-${hoy.getMonth()}-${hoy.getDay()} ${hoy.getHours()}:${hoy.getMinutes()}:${hoy.getSeconds()}`,
            // identificador = window.btoa(fecha).toUpperCase().replaceAll("=", "").slice(0, 10),
            identificador = Math.floor(Math.random() * (999999 - 100000) + 100000),
            table = window.btoa(config[`table`]).toUpperCase().replaceAll("=", "");
            $(this).html($(this).html()).attr(`createForm`, `${identificador}-${table}`);
            $(config.btnReferences).attr(`type`, `button`).attr(`data-toggle`, `modal`).attr(`data-target`, `#modal-${identificador}`);
            let modal = createElem('div', { "class": "modal fade", "id": "modal-" + identificador});
            $(this).after(modal);
            $.ajax(`/plugins/automaticForm/systemConfigForm.php?accion=modal`, {
                type: `POST`,
                data: {
                    identificador: identificador,
                    timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
                    table: table
                },
                beforeSend: function() {
                    loadElementsClient(table, undefined !== config.contentReferences && '' !== config.contentReferences ? config.contentReferences : `[createForm='${identificador}-${table}']`);
                    $(`[data-reload-modal="content"]`).attr(`class`, `overlay`);
                },
                success: function (response) {
                    $(`#modal-${identificador}`).html(response);
                },
                complete: function() {
                    setTimeout(() => {
                        $(`[data-reload-modal="content"]`).attr(`class`, `d-none`);
                    }, 2000);
                    if (config.automaticForm) {
                        // console.log('automaticform');
                        let configAutomaticForm = {};
                        for (data in config.automaticForm) {
                            configAutomaticForm[`${data}`] = `${config.automaticForm[data]}`;
                            // para los editores de texto
                            // if (config.automaticForm[data] == "textarea") {
                            //     document.querySelector('[data-id=""]').children[0].innerHTML='asi';
                            // }
                        }
                        configAutomaticForm[`sweetalert2`] = config.sweetalert2;
                        configAutomaticForm[`table`] = config.table;
                        configAutomaticForm[`reload`] = config.reload;
                        configAutomaticForm[`page`] = config.page;
                        configAutomaticForm[`db`] = config.db;
                        configAutomaticForm[`dataType`] = config.dataType;
                        // console.log(configAutomaticForm);
                        $(form).automaticForm(configAutomaticForm);
                    } else {
                        // console.log(config);
                    }
                }
            });
        } else {
            alerts({
                title: 'Error',
                text: 'Ruta no encontrada',
                icon: 'info'
            }, config.sweetalert2);
        }
    }; 
})(jQuery);