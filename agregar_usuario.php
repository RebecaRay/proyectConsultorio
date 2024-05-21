<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Agregar Usuario</title>
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
                <form id="formulario-agregar" onsubmit="agregarUsuario(); return false;">
                    <div class="form-group">
                        <label for="email-agregar">Email</label>
                        <input type="text" class="form-control" id="email-agregar" name="email" placeholder="Email"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="password-agregar">Contraseña</label>
                        <input type="password" class="form-control" id="password-agregar" name="password"
                            placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <label for="role-agregar">Rol</label>
                        <select class="form-control" id="role-agregar" name="role" required>
                            <option value="Usuario">usuario</option>
                            <option value="Administrador">administrador</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Agregar</button>

                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        function agregarUsuario() {
            // Obtener los valores del formulario
            const email = document.getElementById('email-agregar').value;
            const password = document.getElementById('password-agregar').value;
            const role = document.getElementById('role-agregar').value;

            // Enviar los datos al servidor para agregar un nuevo usuario
            fetch('crud_usuarios.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    action: 'agregar',
                    email: email,
                    password: password,
                    role: role,
                }),
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Respuesta del servidor (agregarUsuario):', data);

                    if (data.success) {
                        alert('Usuario agregado correctamente.');
                        console.log('Recargando la página...');

                        // Recargar la página para reflejar los cambios
                        window.location.href = 'usuarios.php';
                    } else {
                        alert('Error al agregar el usuario: ' + data.message + '\nDetalles: ' + data.error_details);
                    }
                })
                .catch(error => {
                    console.error('Error en la solicitud AJAX:', error);
                });
        }
    </script>
</body>

</html>
