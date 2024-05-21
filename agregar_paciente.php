<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Agregar Paciente</title>
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
                <h2 class="text-center mb-4">Pacientes</h2>
                <form id="formulario-agregar" onsubmit="guardarEdicion(); return false;">
                    <div class="form-group">
                        <label for="nombre-agregar">Nombre</label>
                        <input type="text" class="form-control" id="nombre-agregar" name="nombre" placeholder="Nombre"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="apellido-agregar">Apellido</label>
                        <input type="text" class="form-control" id="apellido-agregar" name="apellido"
                            placeholder="Apellido" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha-nacimiento-agregar">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="fecha-nacimiento-agregar" name="fecha_nacimiento"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="genero-agregar">Género</label>
                        <select class="form-control" id="genero-agregar" name="genero" required>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="telefono-agregar">Teléfono</label>
                        <input type="text" class="form-control" id="telefono-agregar" name="telefono"
                            placeholder="Teléfono" required>
                    </div>
                    <div class="form-group">
                        <label for="email-agregar">Email</label>
                        <input type="text" class="form-control" id="email-agregar" name="email" placeholder="Email"
                            required>
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
        function guardarEdicion() {
            // Obtener los valores del formulario
            const nombre = document.getElementById('nombre-agregar').value;
            const apellido = document.getElementById('apellido-agregar').value;
            const fechaNacimiento = document.getElementById('fecha-nacimiento-agregar').value;
            const genero = document.getElementById('genero-agregar').value;
            const telefono = document.getElementById('telefono-agregar').value;
            const email = document.getElementById('email-agregar').value;

            // Verificar si es una edición o una adición
            const action = 'agregar';  // No hay pacienteId para verificar aquí

            // Enviar los datos al servidor para guardar los cambios o agregar un nuevo paciente
            fetch('crud_pacientes.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    action: action,
                    nombre: nombre,
                    apellido: apellido,
                    fecha_nacimiento: fechaNacimiento,
                    genero: genero,
                    telefono: telefono,
                    email: email,
                }),
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Respuesta del servidor (guardarEdicion):', data);

                    if (data.success) {
                        alert('Paciente agregado correctamente.', 'alert-success');
                        console.log('Recargando la página...');

                        // Recargar la página para reflejar los cambios
                        window.location.href = 'pacientes.php';
                    } else {
                        alert('Error al agregar el paciente: ' + data.message + '\nDetalles: ' + data.error_details, 'alert-danger');
                    }
                })
                .catch(error => {
                    console.error('Error en la solicitud AJAX:', error);
                });
        }
    </script>
</body>

</html>
