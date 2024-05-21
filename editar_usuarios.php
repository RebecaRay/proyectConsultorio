<?php
session_start();
// Antes de imprimir la respuesta JSON, crea un array asociativo
$responseJson = ['success' => true];

// Convierte el array en JSON
$jsonResponse = json_encode($responseJson);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand"><a> Bienvenid@,&nbsp;</a>
            <?php echo isset($_SESSION['email']) ? $_SESSION['email'] : 'email'; ?>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Citas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="medicos.php">Médicos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pacientes.php">Pacientes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="usuarios.php">Usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cerrarSesion.php">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-center mb-4">Usuarios</h2>
                <form id="formulario-edicion" onsubmit="guardarEdicion(); return false;">
                    <div class="form-group">
                        <label for="email-edicion">Email</label>
                        <input type="text" class="form-control" id="email-edicion" name="email" placeholder="Email"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="password-edicion">contraseña</label>
                        <input type="password" class="form-control" id="password-edicion" name="password"
                            placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <label for="role-edicion">Rol</label>
                        <select class="form-control" id="role-edicion" name="role" required>
                            <option value="usuario">Usuario</option>
                            <option value="administrador">Administrador</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Edición</button>

                </form>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>
<script>
    function md5_php(str) {
    const hash = CryptoJS.MD5(str).toString();
    return hash.toLowerCase(); 
}

    function obtenerIdDeUrl() {
        const urlParams = new URLSearchParams(window.location.search);
        const usuarioId = urlParams.get('id');
        console.log('ID del usuario:', usuarioId);
        return usuarioId;
    }
    function obtenerDatosDeUsuario() {
        const usuarioId = obtenerIdDeUrl();
        console.log('ID del usuario:', usuarioId);

        fetch('crud_usuarios.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ action: 'editar', id: usuarioId }),
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                console.log('Respuesta del servidor (obtenerDatosDeUsuario):', data);

                if (data.success && data.usuario) {
                    const usuario = data.usuario;
                    console.log('Datos del usuario:', usuario);

                    document.getElementById('email-edicion').value = usuario['email'];
                    document.getElementById('password-edicion').value = ''; 
                    const roleSelect = document.getElementById('role-edicion');
                    for (let i = 0; i < roleSelect.options.length; i++) {
                        if (roleSelect.options[i].value === usuario['role']) {
                            roleSelect.value = usuario['role'];
                            break;
                        }
                    }
                } else {
                    alert('Error al obtener información del usuario.');
                }
            })
            .catch(error => {
                console.error('Error en la solicitud AJAX:', error);
            });
    }

    function guardarEdicion() {
        const usuarioId = obtenerIdDeUrl();

        const email = document.getElementById('email-edicion').value;
        const password = md5_php(document.getElementById('password-edicion').value); 
        const role = document.getElementById('role-edicion').value;

        fetch('crud_usuarios.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                id: usuarioId,
                email: email,
                password: password,
                role: role,
            }),
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la solicitud AJAX: ' + response.statusText);
                }
                return response.text();
            })
            .then(data => {
                console.log('Respuesta del servidor (guardarEdicion):', <?php echo $jsonResponse; ?>);

                if (data.includes("<br>")) {
                    console.error('Error en la respuesta del servidor (HTML):', data);
                    alert('Error en la respuesta del servidor (HTML): ' + data);
                } else {
                    const jsonData = JSON.parse(data);
                    console.log('Respuesta del servidor (JSON):', jsonData);

                    if (data.success) {
                        console.log('Cambios guardados correctamente.');
                        alert('Cambios guardados correctamente.');
                        if (data.reload) {
                            console.log('Recargando la página...');
                            window.location.reload();
                        } else {
                            console.log('Redirigiendo a otra página...');
                            window.location.href = 'usuarios.php';
                        }
                    } else {
                        console.error('Error al guardar los cambios:', data.message);
                        alert('Error al guardar los cambios: ' + data.message);
                    }
                }
            })
            .catch(error => {
                console.error('Error en la solicitud AJAX:', error);
            });
    }

    obtenerDatosDeUsuario();

    window.onload = function () {
        obtenerDatosDeUsuario();
    };
</script>