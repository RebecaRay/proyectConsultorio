<?php
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: bienvenida.php'); 
    exit();
}

$email = $_SESSION['email'];

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "consultorio";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La conexión ha fallado: " . $conn->connect_error);
}

$query = "SELECT medicos.nombre AS nombre_medico, citas.fecha_hora, tipo_consulta.consulta AS tipo_consulta
          FROM citas 
          JOIN medicos ON citas.medico_id = medicos.id_medico
          JOIN tipo_consulta ON citas.consulta_tipo = tipo_consulta.id_consulta
          JOIN pacientes ON citas.paciente_id = pacientes.id_paciente
          WHERE pacientes.email = ?";


$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->errno) {
        die("Error en la consulta SQL: " . $stmt->error);
    }
    $stmt->store_result();
    if (
        $stmt->num_rows > 0) {
        $stmt->bind_result($medico, $fecha_hora, $tipo_consulta);
        $stmt->fetch();
    } else {
        $medico = "No se encontraron datos de la cita";
        $fecha_hora = "";
        $tipo_consulta = "";
    }

    $stmt->close();
} else {
    die("Error en la preparación de la consulta: " . $conn->error);
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Detalles de la Cita</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4">Detalles de la Cita</h2>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Detalles de la Cita</h5>
                        <p class="card-text"><strong>Médico:</strong> <?php echo $medico; ?></p>
                        <p class="card-text"><strong>Fecha y Hora:</strong> <?php echo $fecha_hora; ?></p>
                        <p class="card-text"><strong>Tipo de Consulta:</strong> <?php echo $tipo_consulta; ?></p>
                    </div>
                </div>

                <div class="mt-3">
                    <button class="btn btn-primary" onclick="window.print()">Imprimir Detalles</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
