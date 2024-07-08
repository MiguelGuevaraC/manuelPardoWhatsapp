// Función para obtener los datos del usuario
function getUserData(id) {
    return $.ajax({
        url: "user/" + id,
        type: "GET",
        dataType: "json",
    });
}

function getTypeUserData() {
    return $.ajax({
        url: "accessAll",
        type: "GET",
        dataType: "json",
    });
}

function editRol(id) {

    $.when(getUserData(id), getTypeUserData())
        .done(function (userDataResponse, typeUserDataResponse) {
            var userData = userDataResponse[0];
            var typeUserData = typeUserDataResponse[0];

            var typeSelect = $("#typeuserE");
            typeSelect.empty();
            typeSelect.append(
                '<option value="">Selecciona un tipo de usuario</option>'
            );

            $.each(typeUserData.data, function (index, type) {
                console.log(type.id);
                var selected =
                    userData.typeofUser_id === type.id ? "selected" : "";
                typeSelect.append(
                    '<option value="' +
                        type.id +
                        '" ' +
                        selected +
                        ">" +
                        type.name +
                        "</option>"
                );
            });

            $("#usernameE").val(userData.username);
            $("#idE").val(userData.id);
            $("#typeuserE").val(userData.typeofUser_id);

            $("#modalNuevoUsuarioE").modal("show");
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.log("Error al cargar datos:", errorThrown);
            console.log("Respuesta del servidor:", jqXHR.responseText);
        });
}

// Cierra el modal al hacer clic en el botón de cerrar
$(document).on("click", "#cerrarModalUsuarioE", function () {
    $("#modalNuevoUsuarioE").modal("hide");
});
