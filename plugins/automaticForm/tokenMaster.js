// JRodriguez - 19 05 2023
// función para generar token aleatorios de forma segura
// los token serán guardados en una base de datos en el servidor
function tokenMaster(
  text,
  user,
  phone,
  type,
  table,
  idUpdate,
  reload,
  page,
  formulario
) {
  // --------------------------------------------------------------------------------------------------------
  // texto - mensaje de la alerta
  // user - usuario que solicita
  // phone - numero a enviar token
  // -------------------------------- opcionales - para automaticForm ---------------------------------------
  // type - 1 insert 2 update
  // table - tabla de la base de datos
  // idUpdate - id del registro si es tipo 2
  // reload - para refrescar la pagina
  // page - pagina a cargar
  // formulario - id del formulario en cuestión
  // --------------------------------------------------------------------------------------------------------

  // primero generamos un código aleatorio de 6 dígitos numéricos
  let token = Math.random().toString(36).substr(2, 6);

  return new Promise(function (resolve, reject) {
    // guardamos el token en la base de datos
    // creamos un formdata para enviarlo
    formData = new FormData();
    formData.append("datos[mensaje]", text);
    formData.append("datos[idUsuario]", user);
    formData.append("datos[token]", token);
    formData.append("datos[estado]", 0);
    formData.append("datos[validado]", "0000-00-00 00:00:00");
    $.ajax({
      url: "/plugins/automaticForm/automaticForm.php?table=tokenAuditor&type=1",
      type: "POST",
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      success: function (data) {
        data = JSON.parse(data);
        let FAID = atob(data["FAID"]); // id del registro
        let body = `*Solicitud de token Recibida*\n\nUsuario: ${atob(
          atob(user)
        )}\nMotivo: ${atob(atob(text))}\n\nToken: ${token}`;
        // enviamos el mensaje
        formData = new FormData();
        formData.append("phone", phone);
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
          text: decodeURIComponent(escape(atob(atob(text)))),
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
              $.ajax({
                url:
                  "/plugins/automaticForm/automaticForm.php?table=tokenAuditor&type=2&idUpdate=" +
                  FAID,
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
              }).done(function () {
                // validamos los datos opcionales
                if (formulario) {
                  console.log(type, table, idUpdate, reload, page, formulario);
                  $("#" + formulario).automaticForm({
                    type: type,
                    table: table,
                    idUpdate: idUpdate,
                    reload: reload,
                    page: page,
                  });
                }
                resolve(true);
              });
            });
          } else {
            Swal.fire({
              title: "Token Incorrecto!",
            }).then(() => {
              // se re intenta el proceso
              // tokenMaster(text, user, phone);
            });
            resolve(false);
          }
        });
      },
    });
  });
}
