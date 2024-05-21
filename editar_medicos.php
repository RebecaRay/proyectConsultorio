<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Editar Medico</title>
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
                <h2 class="text-center mb-4">Médicos</h2>
                <form id="formulario-edicion" onsubmit="guardarEdicion(); return false;">
                    <div class="form-group">
                        <label for="nombre-edicion">Nombre</label>
                        <input type="text" class="form-control" id="nombre-edicion" name="nombre" placeholder="Nombre"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="apellido-edicion">Apellido</label>
                        <input type="text" class="form-control" id="apellido-edicion" name="apellido"
                            placeholder="Apellido" required>
                    </div>
                    <div class="form-group">
                        <label for="especialidad-edicion">Especialidad</label>
                        <input type="text" class="form-control" id="especialidad-edicion" name="especialidad"
                            placeholder="Especialidad" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono-edicion">Teléfono</label>
                        <input type="text" class="form-control" id="telefono-edicion" name="telefono"
                            placeholder="Teléfono" required>
                    </div>
                    <div class="form-group">
                        <label for="email-edicion">Email</label>
                        <input type="text" class="form-control" id="email-edicion" name="email" placeholder="Email"
                            required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Edición</button>

                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>
<script>

    function obtenerIdDeUrl() {
        const urlParams = new URLSearchParams(window.location.search);
        const idMedico = urlParams.get('id');
        console.log('ID del médico:', idMedico); 
        return idMedico;
    }

    function obtenerDatosDeMedico() {
        const medicoId = obtenerIdDeUrl();
        console.log('ID del médico:', medicoId);

        fetch('crud_medicos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ action: 'editar', id_medico: medicoId }),
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                console.log('Respuesta del servidor (obtenerDatosDeMedico):', data);

                if (data.success && data.medico) {
                    const medico = data.medico;  
                    console.log('Datos del médico:', medico);

                    document.getElementById('nombre-edicion').value = medico['nombre'];
                    document.getElementById('apellido-edicion').value = medico['apellido'];
                    document.getElementById('especialidad-edicion').value = medico['especialidad'];
                    document.getElementById('telefono-edicion').value = medico['telefono'];
                    document.getElementById('email-edicion').value = medico['email'];
                } else {
                    alert('Error al obtener información del médico.');
                }
            })
            .catch(error => {
                console.error('Error en la solicitud AJAX:', error);
            });
    }

    function guardarEdicion() {

        const medicoId = obtenerIdDeUrl();
        const nombre = document.getElementById('nombre-edicion').value;
        const apellido = document.getElementById('apellido-edicion').value;
        const especialidad = document.getElementById('especialidad-edicion').value;
        const telefono = document.getElementById('telefono-edicion').value;
        const email = document.getElementById('email-edicion').value;

        fetch('crud_medicos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json', 
            },
            body: JSON.stringify({
                id_medico: medicoId, 
                nombre: nombre,
                apellido: apellido,
                especialidad: especialidad,
                telefono: telefono,
                email: email,
            }),
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la solicitud AJAX: ' + response.statusText);
                }
                return response.text();
            })
            .then(data => {
                console.log('Respuesta del servidor (guardarEdicion):', data);

                if (data.includes("<br>")) {
                    console.error('Error en la respuesta del servidor (HTML):', data);
                    alert('Error en la respuesta del servidor (HTML): ' + data);
                } else {
                    const jsonData = JSON.parse(data);
                    console.log('Respuesta del servidor (JSON):', jsonData);

                    if (jsonData.success) {
                        console.log('Cambios guardados correctamente.');
                        alert('Cambios guardados correctamente.');
                        if (jsonData.reload) {
                            console.log('Recargando la página...');
                            window.location.reload();
                        } else {
                            console.log('Redirigiendo a otra página...');
                            window.location.href = 'medicos.php';
                        }
                    } else {
                        console.error('Error al guardar los cambios:', jsonData.message);
                        alert('Error al guardar los cambios: ' + jsonData.message);
                    }
                }
            })
            .catch(error => {
                console.error('Error en la solicitud AJAX:', error);
            });
    }
    obtenerDatosDeMedico();

    window.onload = function () {
        obtenerDatosDeMedico();
    };
</script>