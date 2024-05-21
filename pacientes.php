<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Pacientes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand"> Bienvenid@,&nbsp;
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

                <a href="agregar_paciente.php" class="btn btn-primary ml-auto mb-2">Agregar Nuevo Paciente</a>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Fecha de Nacimiento</th>
                            <th>Género</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Editar</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php include 'crud_pacientes.php'; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        function editarPaciente(pacienteId) {
            window.location.href = 'editar_pacientes.php?id=' + pacienteId;
        }

        function cargarPacientes() {
            console.log('Cargando pacientes...'); 
            fetch('crud_pacientes.php')
                .then(response => response.json()) 
                .then(data => {
                    if (data.success) {

                        const tablaPacientes = document.querySelector('.table tbody');
                        tablaPacientes.innerHTML = '';

                        data.data.forEach(paciente => {
                            const fila = document.createElement('tr');
                            fila.innerHTML = `
                                <td>${paciente.nombre}</td>
                                <td>${paciente.apellido}</td>
                                <td>${paciente.fecha_nacimiento}</td>
                                <td>${paciente.genero}</td>
                                <td>${paciente.telefono}</td>
                                <td>${paciente.email}</td>
                                <td><button class='btn btn-info' onclick='editarPaciente(${paciente.id_paciente})'>Editar</button></td>
                                <td><button class='btn btn-danger' onclick='eliminarPaciente(${paciente.id_paciente})'>Eliminar</button></td>
                                `;
                            tablaPacientes.appendChild(fila);
                        });
                    } else {
                        console.error('Error al cargar los pacientes:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Error en la solicitud AJAX:', error);
                });
        }

        cargarPacientes();

        function eliminarPaciente(pacienteId) {
            console.log('ID del paciente a eliminar:', pacienteId);

            if (confirm('¿Estás seguro de que deseas eliminar el paciente?')) {
                fetch('crud_pacientes.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json', 
                    },
                    body: JSON.stringify({ action: 'eliminar', id_paciente: pacienteId }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log(data);
                            alert('El paciente se eliminó correctamente.');
                            console.log('recargando pagina');
                            location.reload();
                            console.log('pagina recargada');
                        } else {
                            alert('Error al eliminar el paciente.');
                        }
                    })
                    .catch(error => {
                        console.error('Error en la solicitud AJAX:', error);
                        console.log(error)
                    });
            }
        }
    </script>
</body>

</html>
