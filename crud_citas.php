<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "consultorio";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La conexión ha fallado: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_cita'])) {
    header('Content-Type: application/json');

    $citaId = $conn->real_escape_string($_POST['id_cita']);

    // Uso de consultas preparadas para prevenir inyecciones SQL
    $stmt = $conn->prepare("DELETE FROM citas WHERE id_cita = ?");
    $stmt->bind_param("i", $citaId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    $stmt->close();
    exit;
}

$query = "SELECT citas.*, pacientes.nombre AS nombre_paciente, medicos.nombre AS nombre_medico, 
tipo_consulta.consulta AS tipo_consulta
          FROM citas 
          JOIN pacientes ON citas.paciente_id = pacientes.id_paciente 
          JOIN medicos ON citas.medico_id = medicos.id_medico
          JOIN tipo_consulta ON citas.consulta_tipo = tipo_consulta.id_consulta";

$result = $conn->query($query);
if (!$result) {
    die("Error en la consulta SQL: " . $conn->error);
}

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$row['nombre_paciente']}</td>";
    echo "<td>{$row['nombre_medico']}</td>";
    echo "<td>{$row['fecha_hora']}</td>";
    echo "<td>{$row['tipo_consulta']}</td>";
    echo "<td><button class='btn btn-danger' onclick='eliminarCita({$row['id_cita']})'>Eliminar</button></td>";
    echo "</tr>";
}

$result->free();
$conn->close();
?>
