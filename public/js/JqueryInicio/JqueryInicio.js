// Función para establecer la cookie
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

// Función para obtener el valor de la cookie
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(";");
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == " ") {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

// Función para mostrar SweetAlert si no se ha mostrado hoy
function showSweetAlert() {
    console.log(getCookie("lastShownDate"));
    console.log(new Date().toDateString());

    var lastShownDate = getCookie("lastShownDate");
    var currentDate = new Date().toDateString();

    if (lastShownDate !== currentDate) {
        Swal.fire({
            title: "Ingrese el valor del dólar",
            input: "text",
            inputPlaceholder: "Ingrese el valor",
            showCancelButton: true,
            confirmButtonText: "Guardar",
            cancelButtonText: "Cancelar",
            preConfirm: (value) => {
                // Aquí puedes hacer algo con el valor ingresado
                // Por ejemplo, enviarlo a un servidor
                console.log("Valor ingresado: " + value);
                setCookie("lastShownDate", currentDate, 1); // Establecer la cookie para hoy

                $.ajax({
                    type: "get",
                    url: "tipoCambio",
                    data: { valorDolar: value },
                    success: function (response) {
                        
        
                    },
                });

            },
        });
    } else {
        console.log("SweetAlert ya mostrado hoy");
    }
}

// // Llamar a la función al cargar la página
// $(document).ready(function () {
//     showSweetAlert();
// });



function pruebaApi(){
    $.get('api/ejemploApi', function(data) {
        // Una vez que se reciban los datos, manejar la respuesta
        // Por ejemplo, mostrar la lista de usuarios en el div 'users-list'
       console.log(data);
    });
}