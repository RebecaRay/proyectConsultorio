<?php
session_start();

// Verifica si la sesión "email" está configurada
if (!isset($_SESSION['email'])) {
    // La sesión no está configurada, redirige al usuario a la página de inicio de sesión
    header("Location: login.html"); // Reemplaza "login.php" con la página de inicio de sesión real
    exit(); // Termina el script
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Agenda tu cita</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand"><a> Bienvenido,&nbsp;</a>
            <?php echo isset($_SESSION['email']) ? $_SESSION['email'] : 'email'; ?>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Mis Citas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cerrarSesion.php">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4">Bienvenido</h2>

                <!-- Formulario para registrar una cita -->
                <h3 class="text-center mb-3">Selecciona un médico y registra tu cita:</h3>


                <!-- Formulario para registrar una cita -->
                <form action="procesar_cita.php" method="post">
                    <div class="form-group">
                        <label for="medico">Selecciona un Médico:</label>
                        <select class="form-control" id="medico" name="medico" required>
                            <option value="">Selecciona un Médico</option>
                            <?php
                            foreach ($medicos as $medico): ?>
                                <option value="<?php echo $medico['id_medico']; ?>">
                                    <?php echo $medico['nombre'] . ' ' . $medico['apellido'] . ' - ' . 
                                    $medico['especialidad']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="fecha_hora">Selecciona Fecha y Hora:</label>
                        <input type="datetime-local" class="form-control" id="fecha_hora" name="fecha_hora" required>
                    </div>

                    <div class="form-group">
                        <label for="consulta_tipo">Tipo de Consulta:</label>
                        <select class="form-control" id="consulta_tipo" name="consulta_tipo">
                            <option value="">Selecciona un Tipo de Consulta</option>
                            <?php foreach ($tipos_consulta as $tipoConsulta): ?>
                                <option value="<?php echo $tipoConsulta['id_consulta']; ?>">
                                    <?php echo $tipoConsulta['consulta']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Registrar Cita</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            
            $.ajax({
                url: 'procesar_cita.php', 
                method: 'GET', 
                dataType: 'json',
                success: function (response) {
                    if (response.medicos) {
                        response.medicos.forEach(function (medico) {
                            $('#medico').append(new Option(medico.nombre + ' ' + medico.apellido + ' - ' + 
                            medico.especialidad, medico.id_medico));
                        });
                    }

                    if (response.tipos_consulta) {
                        response.tipos_consulta.forEach(function (tipoConsulta) {
                            $('#consulta_tipo').append(new Option(tipoConsulta.consulta, tipoConsulta.id_consulta));
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error en la solicitud AJAX:", status, error);
                }
            });
        });
    </script>
</body>
</html>