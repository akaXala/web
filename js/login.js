document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('container');
    const registerBtn = document.getElementById('register');
    const loginBtn = document.getElementById('login');
    const inputs = document.querySelectorAll('input');
    const loginForm = document.getElementById('login-form');
    const registerForm = document.querySelector('.custom-form-container.custom-sign-up form');

    registerBtn.addEventListener('click', () => {
        container.classList.add("active");
    });

    loginBtn.addEventListener('click', () => {
        container.classList.remove("active");
    });

    const expresiones = {
        correo: /^[a-zA-Z0-9_.+-]+@(gmail\.com|hotmail\.com|yahoo\.com|yahoo\.com\.mx|outlook\.com)$/,
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
        correo: "El correo debe ser de un dominio válido (gmail.com, hotmail.com, yahoo.com, yahoo.com.mx, outlook.com).",
        contrasena: "La contraseña debe tener entre 4 y 12 caracteres.",
        nombre: "El nombre solo puede contener letras y espacios.",
        apellido_paterno: "El apellido paterno solo puede contener letras y espacios.",
        apellido_materno: "El apellido materno solo puede contener letras y espacios.",
        telefono: "El teléfono debe contener 10 dígitos."
    };

    const validarFormulario = (e) => {
        switch(e.target.name){
            case "email":
                validarCampo(expresiones.correo, e.target, "correo");
                break;
            case "pass":
                validarCampo(expresiones.contrasena, e.target, "contrasena");
                break;
            case "nombre":
                validarCampo(expresiones.nombre, e.target, "nombre");
                break;
            case "paterno":
                validarCampo(expresiones.apellido_paterno, e.target, "apellido_paterno");
                break;
            case "materno":
                validarCampo(expresiones.apellido_materno, e.target, "apellido_materno");
                break;
            case "tel":
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

    registerForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        if (
            campos.correo &&
            campos.contrasena &&
            campos.nombre &&
            campos.apellido_paterno &&
            campos.apellido_materno &&
            campos.telefono
        ) {
            const formData = new FormData(registerForm);
            const response = await fetch('../php/registro.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.status === 'error') {
                const errorField = document.querySelector(`#error-${result.field}`);
                if (errorField) {
                    errorField.innerText = result.message;
                }
            } else {
                const userID = result.userID;
                window.location.href = `../html/bienvenida.php?userID=${userID}`;
            }
        } else {
            alert("Por favor, rellena el formulario correctamente.");
        }
    });

    loginForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const formData = new FormData(loginForm);
        const response = await fetch('../php/login.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        const errorGeneral = document.getElementById('error-login-general');
        if (result.status === 'error') {
            errorGeneral.innerText = result.message;
        } else {
            errorGeneral.innerText = '';
            window.location.href = '../html/index.php';
        }
    });
});
