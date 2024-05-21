<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Medicos</title>
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
                <h2 class="text-center mb-4">Médicos</h2>

                <a href="agregar_medico.php" class="btn btn-primary ml-auto mb-2">Agregar Nuevo Médico</a>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Especialidad</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Editar</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php include 'crud_medicos.php'; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    
</body>

</html>
<script>
        function editarMedico(medicoId) {
            window.location.href = 'editar_medicos.php?id=' + medicoId;
        }

        function cargarMedicos() {
            console.log('Cargando médicos...'); 
            fetch('crud_medicos.php')
                .then(response => response.json()) 
                .then(data => {
                    if (data.success) {

                    const tablaMedicos = document.querySelector('.table tbody');
                    tablaMedicos.innerHTML = '';

                    data.data.forEach(medico => {
                        const fila = document.createElement('tr');
                        fila.innerHTML = `
                            <td>${medico.nombre}</td>
                            <td>${medico.apellido}</td>
                            <td>${medico.especialidad}</td>
                            <td>${medico.telefono}</td>
                            <td>${medico.email}</td>
                            <td><button class='btn btn-info' onclick='editarMedico(${medico.id_medico})'>Editar</button></td>
                            <td><button class='btn btn-danger' onclick='eliminarMedico(${medico.id_medico})'>Eliminar</button></td>
                            `;
                        tablaMedicos.appendChild(fila);
                    });
                } else {
                    console.error('Error al cargar los médicos:', data.error);
                }
                })
                .catch(error => {
                    console.error('Error en la solicitud AJAX:', error);
                });
        }

        cargarMedicos();

        function eliminarMedico(medicoId) {
            console.log('ID del médico a eliminar:', medicoId);

            if (confirm('¿Estás seguro de que deseas eliminar el médico?')) {
                fetch('crud_medicos.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json', 
                    },
                    body: JSON.stringify({ action: 'eliminar', id_medico: medicoId }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log(data);
                            alert('El médico se eliminó correctamente.');
                            console.log('regargando pagina');
                            location.reload();
                            console.log('pagina recargada');
                        } else {
                            // Muestra la alerta de error
                            alert('Error al eliminar el médico.');
                        }
                    })
                    .catch(error => {
                        console.error('Error en la solicitud AJAX:', error);
                        console.log(error)
                    });
            }
        }
    </script>