document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('input');
    const registerForm = document.getElementById('register-form');

    const expresiones = {
        correo: /^[a-zA-Z0-9_.+-]{1,40}$/,
        contrasena: /^.{4,12}$/,
        nombre: /^[a-zA-ZÀ-ÿ\s]{1,40}$/,
        apellido_paterno: /^[a-zA-ZÀ-ÿ\s]{1,40}$/,
        apellido_materno: /^[a-zA-ZÀ-ÿ\s]{1,40}$/,
        telefono: /^\d{10}$/
    };

    const campos = {
        correo: false,
        contrasena: false,
        nombre: false,
        apellido_paterno: false,
        apellido_materno: false,
        telefono: false
    };

    const mensajesError = {
        correo: "El correo solo debe tener carácteres alfanúmericos.",
        contrasena: "La contraseña debe tener entre 4 y 12 caracteres.",
        nombre: "El nombre solo puede contener letras y espacios.",
        apellido_paterno: "El apellido paterno solo puede contener letras y espacios.",
        apellido_materno: "El apellido materno solo puede contener letras y espacios.",
        telefono: "El teléfono debe contener 10 dígitos."
    };

    const validarFormulario = (e) => {
        switch(e.target.name){
            case "correo":
                validarCampo(expresiones.correo, e.target, "correo");
                break;
            case "contrasena":
                validarCampo(expresiones.contrasena, e.target, "contrasena");
                break;
            case "nombre":
                validarCampo(expresiones.nombre, e.target, "nombre");
                break;
            case "primerAp":
                validarCampo(expresiones.apellido_paterno, e.target, "apellido_paterno");
                break;
            case "segundoAp":
                validarCampo(expresiones.apellido_materno, e.target, "apellido_materno");
                break;
            case "telefono":
                validarCampo(expresiones.telefono, e.target, "telefono");
                break;
        }
    };

    const validarCampo = (expresion, input, campo) => {
        const mensajeError = document.querySelector(`#error-${campo}`);
        if (expresion.test(input.value.trim())) {
            input.classList.remove("is-invalid");
            input.classList.add("is-valid");
            campos[campo] = true;
            mensajeError.innerText = "";
        } else {
            input.classList.add("is-invalid");
            input.classList.remove("is-valid");
            campos[campo] = false;
            mensajeError.innerText = mensajesError[campo];
        }
    };

    inputs.forEach((input) => {
        input.addEventListener("keyup", validarFormulario);
        input.addEventListener("blur", validarFormulario);
    });

    registerForm.addEventListener("submit", (e) => {
        e.preventDefault();

        if (
            campos.correo &&
            campos.contrasena &&
            campos.nombre &&
            campos.apellido_paterno &&
            campos.apellido_materno &&
            campos.telefono
        ) {
            registerForm.submit();
        } else {
            alert("Por favor, rellena el formulario correctamente.");
        }
    });

    document.getElementById('restoreButton').addEventListener('click', function() {
        if (confirm('Are you sure you want to restore the products? This action is irreversible.')) {
            fetch('../DProductos/datosJSON.php', {
                method: 'POST'
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    });
});
