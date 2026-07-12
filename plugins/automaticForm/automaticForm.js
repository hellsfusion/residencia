function salt() {
  // funcion para devolver un string de 10 caracteres aleatorios
  let caracteres = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
  let longitud = caracteres.length;
  let cadena = "";
  for (let i = 0; i < 10; i++) {
    cadena += caracteres.charAt(Math.floor(Math.random() * longitud));
  }
  return cadena;
}

function getsURI(contentPage = false) {
  if ("" != location.search) {
    let gets = {};
    let getsURI = location.search.replace("?", "").split("&");
    let c = Number(0);
    for (let index = 0; index < getsURI.length; index++) {
      [key, value] = explode("=", getsURI[index]);
      if ("FAID" != key && undefined !== key) {
        gets[key] = value;
        c += 1;
      }
    }
    if (c > 0) {
      return gets;
    } else {
      return false;
    }
  } else {
    return false;
  }
}

function explode(exp, str) {
  return [
    str.slice(0, str.search(exp)),
    str.slice(str.search(exp)).replace(exp, ""),
  ];
}

function cargarDatosFAID(t, c, x = null, db, dataType) {
  $.ajax("/plugins/automaticForm/ajax_datos.php", {
    type: "POST",
    dataType: "JSON",
    data: {
      timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
      tabla: t,
      condicion: c,
      db: db,
      dataType: dataType,
    },
    beforeSend: function () {
      if (null != x) {
        $(x).append(
          `<div class="overlay" data-reload-cargarDatosFAID style="position: fixed; top: 50%; left: 50%;"><i class="fas fa-2x fa-sync fa-spin"></i></div>`
        );
      }
    },
    success: function (response) {
      //   console.log("si paso");
      for (data in response) {
        // console.log(data);
        identForm = null != x ? `${x}` : "";

        key = window.atob(window.atob(data));
        // console.log(key);
        value = window.atob(window.atob(response[data]));
        // console.log(value);

        type1 = $(`${identForm} [name="datos[${key}]"]`).attr("type");
        campo1 = $(`${identForm} [name="datos[${key}]"]`);
        tag1 = jQuery(campo1).tagName();
        // console.log(`${identForm} [name="datos[${key}]"]`);
        select12 = undefined;
        if ("SELECT" == tag1) {
          select12 = campo1[0].classList.contains("select2");
          if (undefined == select12) {
            select12 = campo1[0].classList.contains("select2bs4");
          }
        }

        type2 = $(`${identForm} [name="datos[${key}][]"]`).attr("type");
        campo2 = $(`${identForm} [name="datos[${key}][]"]`);
        tag2 = jQuery(campo2).tagName();
        select22 = undefined;
        if ("SELECT" == tag2) {
          select22 = campo2[0].classList.contains("select2");
          if (undefined == select22) {
            select22 = campo2[0].classList.contains("select2bs4");
          }
        }

        // console.log('datos['+key+'}]'+select12, select22);

        if (undefined !== campo1 && undefined !== tag1) {
          // campos normales
          if ("SELECT" == tag1) {
            if (false !== select12 && undefined !== select12) {
              // console.log(`${identForm} [name="datos[${key}]"]`);
              $(`${identForm} [name="datos[${key}]"]`).select2("destroy"); // pa cuando sea select2
            }
            // console.log(campo1);
            // console.log(value);
            campo1.val(value);
            if (false !== select12 && undefined !== select12) {
              $(`${identForm} [name="datos[${key}]"]`).select2({
                width: "100%",
              }); // pa cuando sea select2
            }
          } else if ("TEXTAREA" == tag1) {
            campo1.html(value);
            // para editores
            let editores = document.querySelectorAll(
              '[class="tox tox-tinymce"]'
            );
            for (let i = 0; i < editores.length; i++) {
              if (
                document.querySelectorAll('[class="tox tox-tinymce"]')[i]
                  .parentNode.children[1].id == campo1[0].id
              ) {
                document
                  .querySelectorAll('[class="tox tox-tinymce"]')
                  [
                    i
                  ].children[0].children[1].children[0].children[0].contentWindow.document.getElementsByTagName(
                    "body"
                  )[0].innerHTML = value;
              }
            }
          } else if ("radio" === type1) {
            campo1.prop("checked", true);
          } else if ("checkbox" === type1 && value == "on") {
            campo1.prop("checked", true);
          } else {
            campo1.val(value);
          }
          // campos normales
        } else if (undefined !== campo2 && undefined !== tag2) {
          // campos multiples
          if ("SELECT" == tag2) {
            // jose ere e mejor ;)
            // me dio pereza ver si el select2 es igual a select2bs4 asi que pendiente
            if (false !== select22 && undefined !== select22)
              $(`${identForm} [name="datos[${key}][]"]`).select2("destroy"); // pa cuando sea select2
            $(`${identForm} [name="datos[${key}][]"] option`).removeAttr(
              "selected"
            );
            value.split("|/|").forEach((element) => {
              $(
                `${identForm} [name="datos[${key}][]"] [value='${element}']`
              ).attr("selected", true);
            });
            if (false !== select22 && undefined !== select22)
              $(`${identForm} [name="datos[${key}][]"]`).select2({
                width: "100%",
              }); // pa cuando sea select2
            // jose ere e mejor ;)
          }
          // campos multiples
        }
      }
    },
    complete: function () {
      if (null != x) {
        $(`[data-reload-cargarDatosFAID]`).remove();
      }
    },
  });
}

jQuery.fn.tagName = function () {
  return this.prop("tagName");
};

(function ($) {
  $.fn.afDestroy = function () {
    $.fn.automaticForm = {};
    Swal.fire("Cerrando formulario", "", "info");
  };
  $.fn.afRebuild = function () {
    $.fn.automaticForm = function (config = null) {
      Swal.fire("Rebuild", "", "info");
    };
  };


  
  // spinner para cargar
  // Crear el div del loader
  var loader = document.createElement("div");
  loader.id = "loader";
  document.body.appendChild(loader);

  // Establecer los estilos del loader
  loader.style.position = "fixed";
  loader.style.top = "0";
  loader.style.left = "0";
  loader.style.width = "100%";
  loader.style.height = "100%";
  loader.style.backgroundColor = "rgba(0, 0, 0, 0.5)";
  loader.style.zIndex = "9999";
  loader.style.display = "none";
  loader.style.justifyContent = "center";
  loader.style.alignItems = "center";

  // Crear el contenido del loader
  var spinner = document.createElement("div");
  spinner.style.width = "40px";
  spinner.style.height = "40px";
  spinner.style.borderRadius = "50%";
  spinner.style.border = "4px solid #f3f3f3";
  spinner.style.borderTop = "4px solid #3498db";
  spinner.style.animation = "spin 1s linear infinite";
  loader.appendChild(spinner);

  // Función para mostrar el loader
function mostrarLoader() {
  console.log("mostrar loader");
  loader.style.display = "flex";
}

// Función para ocultar el loader
function ocultarLoader() {
  loader.style.display = "none";
}

  // Definir la animación del spinner
  var animationKeyframes =
    "@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }";
  var styleSheet = document.createElement("style");
  styleSheet.innerHTML = animationKeyframes;
  document.head.appendChild(styleSheet);

  $.fn.automaticForm = function (config = null) {
    mostrarLoader();
    console.log(this);
    let formulario = this; // solo lo uso cuando pasa token
    // console.log(formulario);
    let hoy = new Date(),
      fecha = `${hoy.getFullYear()}-${hoy.getMonth()}-${hoy.getDay()} ${hoy.getHours()}:${hoy.getMinutes()}:${hoy.getSeconds()}`,
      // identificador = window.btoa(fecha).toUpperCase().replaceAll("=", "").slice(0, 5);
      identificador = Math.floor(Math.random() * (999999 - 100000) + 100000);
    $llave = "fldsmdfr";

    // default
    var config = $.extend(
      {
        type: 1, // 1 insertar, 2 actualizar
        table: undefined, // tabla en la base de datos
        idUpdate: undefined, // id principal para actualizar
        // -------------------------------------------------
        contentPage: undefined, // ------------
        reload: false, // 1 ''
        page: undefined, // 'menu_invetario' 'facturas.php'
        sweetalert2: false, // ------------
        token: undefined, // si - no
        tokenText: undefined, // mensaje de la alerta
        tokenNumbers: undefined, // numeros a enviar xxxxxxxxxxxx|/|xxxxxxxxxxxx
        tokenUser: undefined, // usuario a enviar
        // -------------------------------------------------
        dbHost: undefined, // host para la conexion ej localhost
        dbUser: undefined, // usuario para la conexion
        dbPass: undefined, // contraseña para la conexion
        db: undefined, // nombre de la base de datos
        // -------------------------------------------------
        dataType: undefined, // tipo de datos en la tabla 0 natural 1 encriptado
        keepData: undefined, // 1 no blanquear datos
        // -------------------------------------------------
        post: undefined,
      },
      config
    );
    // default +

    if (0 != this.length) {
      if (2 === config.type && undefined === config.idUpdate) {
        alerts(
          {
            title: "Error de configuración",
            text: "Campo de referencia es obligatorio para actualizar",
            icon: "error",
          },
          config.sweetalert2
        );
      } else if (undefined !== config.table) {
        // console.log("paso automaticform");
        // console.log(config.db);

        $(this).attr(`data-automatic-form`, identificador);
        if (2 == config.type && undefined !== config.idUpdate) {
          cargarDatosFAID(
            config.table,
            `@primary = '${config.idUpdate}'`,
            `[data-automatic-form="${$(this).attr(`data-automatic-form`)}"]`,
            config.db,
            config.dataType
          );
        }

        if (
          undefined !== config.token &&
          undefined !== config.tokenText &&
          undefined !== config.tokenNumbers &&
          undefined !== config.tokenUser
        ) {
          // eltoken yatusabe 🧐
          // primero generamos un código aleatorio de 6 dígitos numéricos
          let token = Math.random().toString(36).substr(2, 6);
          // guardamos el token en la base de datos
          // creamos un formdata para enviarlo
          formData = new FormData();
          formData.append("datos[mensaje]", config.tokenText);
          formData.append("datos[idUsuario]", config.tokenUser);
          formData.append("datos[token]", token);
          formData.append("datos[estado]", 0);
          formData.append("datos[validado]", "0000-00-00 00:00:00");
          formData.append("datos[db]", config.db);
          $.ajax({
            url: `/plugins/automaticForm/automaticForm.php?table=tokenAuditor&type=1&db=${config.db}`,
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
              data = JSON.parse(data);
              let FAID = atob(data["FAID"]); // id del registro
              let body = `*Solicitud de token Recibida*\n\nUsuario: ${atob(
                atob(config.tokenUser)
              )}\nMotivo: ${atob(atob(config.tokenText))}\n\nToken: ${token}`;
              // enviamos el mensaje
              formData = new FormData();
              formData.append("phone", config.tokenNumbers);
              formData.append("body", btoa(btoa(body)));
              $.ajax({
                url: "/plugins/automaticForm/whatsappSend.php",
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
              });
              Swal.fire({
                title: "Atención requerida!",
                text: decodeURIComponent(escape(atob(atob(config.tokenText)))),
                type: "warning",
                input: "text",
                inputAttributes: {
                  autocapitalize: "off",
                },
                showCancelButton: false,
                confirmButtonText: "Enviar",
              }).then((result) => {
                if (result.value == token) {
                  Swal.fire({
                    title: "Token confirmado!",
                  }).then(() => {
                    // se actualiza el estado del token
                    date = new Date();
                    // date yyyy-mm-dd hh:mm:ss
                    date =
                      date.getFullYear() +
                      "-" +
                      (date.getMonth() + 1) +
                      "-" +
                      date.getDate() +
                      " " +
                      date.getHours() +
                      ":" +
                      date.getMinutes() +
                      ":" +
                      date.getSeconds();

                    formData = new FormData();
                    formData.append("datos[estado]", 1);
                    formData.append("datos[validado]", date);
                    formData.append("datos[db]", config.db);
                    $.ajax({
                      url:
                        `/plugins/automaticForm/automaticForm.php?table=tokenAuditor&db=${config.db}&type=2&idUpdate=` +
                        FAID,
                      type: "POST",
                      data: formData,
                      cache: false,
                      contentType: false,
                      processData: false,
                    }).done(function () {
                      // validamos los datos opcionales
                      // submit this form
                      $(formulario[0]).removeAttr("data-automatic-form");
                      $.ajax(
                        `/plugins/automaticForm/automaticForm.php?table=${config.table}&type=${config.type}&idUpdate=${config.idUpdate}&db=${config.db}`,
                        {
                          type: "POST",
                          dataType: "JSON",
                          processData: false,
                          contentType: false,
                          cache: false,
                          data: new FormData(formulario[0]),
                          success: function (response) {
                            if (undefined === response.Error) {
                              if (true === response.status) {
                                let r;
                                let c = Number(0);
                                if (false !== getsURI()) {
                                  $.each(getsURI(), function (key, value) {
                                    r += `${key}=${value}&`;
                                    c += 1;
                                  });
                                  r = `${r.slice(0, -1)}`;
                                }
                                if (false !== config.reload) {
                                  if (config.page != undefined) {
                                    let page = config.page.split("||");
                                    // si page[0] contiene FAID
                                    if (page[0].includes("FAID")) {
                                      window.location.href = `${page[0]}`;
                                    } else {
                                      if (config.post !== undefined) {
                                        // Crear un formulario dinámicamente
                                        const form =
                                          document.createElement("form");
                                        form.method = "POST"; // Utilizar el método POST
                                        form.action = page[0]; // Especificar la URL de la página destino
                                        // todos los campos estan en el formulario[0]
                                        for (let i = 0; i < formulario[0].length; i++) {
                                          const input = document.createElement("input");
                                          input.type = "hidden";
                                          input.name = formulario[0][i].name;
                                          input.value = formulario[0][i].value;
                                          form.appendChild(input);
                                        }
                                        document.body.appendChild(form);
                                        form.submit();                                        
                                      } else {
                                        window.location.href = `${page[0]}?FAID=${response.FAID}`;
                                      }
                                    }
                                  } else {
                                    // window.location.href = `${config.reload}${c > 0 ? `&${r}` : `?${r}`}&FAID=${response.FAID}`;
                                  }
                                } else if (undefined !== config.contentPage) {
                                  // no sabia como validarun arreglo de objetos
                                  title = config.contentPage[0]["title"]
                                    ? config.contentPage[0]["title"]
                                    : "";
                                  route = config.contentPage[0]["route"]
                                    ? config.contentPage[0]["route"]
                                    : "";
                                  // contentPageResult(`${config.contentPage[0]["content"]}${c > 0 ? `&${r}` : "?"}`, title, route);
                                  window.location.href = page[0];
                                }

                                alerts(
                                  {
                                    title: "Registrado con éxito",
                                    text: "Se han guardado tus datos",
                                    icon: "success",
                                  },
                                  config.sweetalert2
                                );
                              }
                            } else {
                              alerts(
                                {
                                  title: "respuesta MySQL",
                                  text: response.Error,
                                  icon: "error",
                                },
                                config.sweetalert2
                              );
                            }
                          },
                        }
                      );
                      // blanquear formulario
                      if (config.keepData == undefined) {
                        $(formulario[0])
                          .find("input, textarea, select")
                          .each(function () {
                            // si no es hidden
                            if (!$(formulario[0]).is(":hidden")) {
                              $(formulario[0]).val("");
                            }
                          });
                      }
                      //   });
                    });
                  });
                } else {
                  Swal.fire({
                    title: "Token Incorrecto!",
                  }).then(() => {
                    // se re intenta el proceso
                    // nanatsu
                    // console.log("token incorrecto");
                  });
                }
              });
            },
          });
        } else {
          $(this).on("submit", function (e) {
            $(this).removeAttr("data-automatic-form");

            if (config.dataType !== undefined && config.dataType == 1) {
              // todos los value se formatean en base64
              $(this)
                .find("[name]")
                .each(function () {
                  console.log(
                    "" + $(this).val() + " - " + salt() + btoa($(this).val())
                  );

                  // saber si el input es un select
                  if ($(this).is("select")) {
                    // crear una nueva opcion
                    $(this).append(
                      $("<option></option>")
                        .text($(this).val())
                        .attr("value", salt() + btoa($(this).val()))
                        .attr("selected", "selected")
                    );
                  } else if (
                    $(this).is("input") &&
                    $(this).attr("type") === "date"
                  ) {
                    $(this).attr("type", "text");
                    $(this).val(salt() + btoa($(this).val()));
                  } else {
                    $(this).val(salt() + btoa($(this).val()));
                  }

                  console.log("" + $(this).val());
                });
            }

            e.preventDefault();
            $.ajax(
              `/plugins/automaticForm/automaticForm.php?table=${
                config.table
              }&type=${config.type}&idUpdate=${config.idUpdate}&db=${
                config.db != undefined ? btoa(config.db) : ""
              }&dbHost=${
                config.dbHost != undefined ? btoa(config.dbHost) : ""
              }&dbUser=${
                config.dbUser != undefined ? btoa(config.dbUser) : ""
              }&dbPass=${
                config.dbPass != undefined ? btoa(config.dbPass) : ""
              }`,
              {
                type: "POST",
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                data: new FormData(this),
                success: function (response) {
                  if (undefined === response.Error) {
                    if (true === response.status) {
                      let r;
                      let c = Number(0);
                      if (false !== getsURI()) {
                        $.each(getsURI(), function (key, value) {
                          r += `${key}=${value}&`;
                          c += 1;
                        });
                        r = `${r.slice(0, -1)}`;
                      }
                      if (false !== config.reload) {
                        if (config.page != undefined) {
                          let page = config.page.split("||");
                          // si page[0] contiene FAID
                          if (page[0].includes("FAID")) {
                            window.location.href = `${page[0]}`;
                          } else {
                            // window.location.href = `${page[0]}?FAID=${response.FAID}`;
                            if (config.post !== undefined) {
                              // Crear un formulario dinámicamente
                              const form =
                                document.createElement("form");
                              form.method = "POST"; // Utilizar el método POST
                              form.action = page[0]; // Especificar la URL de la página destino
                              // todos los campos estan en el formulario[0]
                              for (let i = 0; i < formulario[0].length; i++) {
                                const input = document.createElement("input");
                                input.type = "hidden";
                                input.name = formulario[0][i].name;
                                input.value = formulario[0][i].value;
                                form.appendChild(input);
                              }
                              document.body.appendChild(form);
                              form.submit();                                        
                            } else {
                              window.location.href = `${page[0]}?FAID=${response.FAID}`;
                            }
                          }
                        } else {
                          // window.location.href = `${config.reload}${c > 0 ? `&${r}` : `?${r}`}&FAID=${response.FAID}`;
                        }
                      } else if (undefined !== config.contentPage) {
                        // no sabia como validarun arreglo de objetos
                        title = config.contentPage[0]["title"]
                          ? config.contentPage[0]["title"]
                          : "";
                        route = config.contentPage[0]["route"]
                          ? config.contentPage[0]["route"]
                          : "";
                        // contentPageResult(`${config.contentPage[0]["content"]}${c > 0 ? `&${r}` : "?"}`, title, route);
                        window.location.href = page[0];
                      }

                      alerts(
                        {
                          title: "Registrado con éxito",
                          text: "Se han guardado tus datos",
                          icon: "success",
                        },
                        config.sweetalert2
                      );
                    }
                  } else {
                    alerts(
                      {
                        title: "respuesta MySQL",
                        text: response.Error,
                        icon: "error",
                      },
                      config.sweetalert2
                    );
                  }
                },
              }
            );
            // blanquear formulario
            if (config.keepData == undefined) {
              $(this)
                .find("input, textarea, select")
                .each(function () {
                  // si no es hidden
                  if (!$(this).is(":hidden")) {
                    $(this).val("");
                  }
                });
            }
          });
        }
      } else {
        alerts(
          {
            title: "Error de configuración",
            text: "La tabla es obligatoria",
            icon: "error",
          },
          config.sweetalert2
        );
      }
    } else {
      alerts(
        {
          title: "Error",
          text: "Ruta no encontrada",
          icon: "info",
        },
        config.sweetalert2
      );
    }
    ocultarLoader();
  };
})(jQuery);
