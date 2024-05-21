<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard del Administrador</title>
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
                <h2 class="text-center mb-4">Citas</h2>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Paciente</th>
                            <th>Médico</th>
                            <th>Fecha y Hora</th>
                            <th>Tipo de Consulta</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php include 'crud_citas.php'; ?>
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
    function eliminarCita(citaId) {
        console.log(citaId);
        if (confirm('¿Estás seguro de que deseas eliminar esta cita?')) {
            fetch('crud_citas.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id_cita=' + citaId,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('La cita se eliminó correctamente.');
                        location.reload(); 
                    } else {
                        alert('Error al eliminar la cita.');
                    }
                })
                .catch(error => {
                    console.error('Error en la solicitud AJAX:', error);
                });
        }
    }
</script>