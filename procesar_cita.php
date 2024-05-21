<?php

error_reporting(E_ALL);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 

$response = []; // Crear un arreglo para almacenar la respuesta

session_start();

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "consultorio";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    $response['error'] = "La conexión ha fallado: " . $conn->connect_error;
    echo json_encode($response);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];

        $sqlMedicos = "SELECT id_medico, nombre, apellido, especialidad FROM medicos";
        $resultMedicos = $conn->query($sqlMedicos);

        if (!$resultMedicos) {
            $response['error'] = "Error en la consulta SQL para médicos: " . $conn->error;
        } else {
            $medicos = [];
            while ($row = $resultMedicos->fetch_assoc()) {
                $medicos[] = $row;
            }
            $response['medicos'] = $medicos;
        }

        $sqlTiposConsulta = "SELECT id_consulta, consulta FROM tipo_consulta";
        $resultTiposConsulta = $conn->query($sqlTiposConsulta);

        if (!$resultTiposConsulta) {
            $response['error'] = "Error en la consulta SQL para tipos de consulta: " . $conn->error;
        } else {
            $tiposConsulta = [];
            while ($row = $resultTiposConsulta->fetch_assoc()) {
                $tiposConsulta[] = $row;
            }
            $response['tipos_consulta'] = $tiposConsulta;
        }
    } else {
        $response['error'] = "El paciente no está autenticado.";
        echo json_encode($response);
        exit();    
    }
    echo json_encode($response);
    exit(); 
}
elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];

        $medico_id = $_POST["medico"];
        $fecha_hora = $_POST["fecha_hora"];
        $consulta_tipo = $_POST["consulta_tipo"];

        try {
            $sqlPacienteId = "SELECT id_paciente FROM pacientes WHERE email = '$email'";
            $resultPacienteId = $conn->query($sqlPacienteId);

            if ($resultPacienteId->num_rows > 0) {
                $rowPaciente = $resultPacienteId->fetch_assoc();
                $paciente_id = $rowPaciente['id_paciente'];

                $sqlInsertCita = "INSERT INTO citas (paciente_id, medico_id, fecha_hora, consulta_tipo) 
                                  VALUES ('$paciente_id', '$medico_id', '$fecha_hora', '$consulta_tipo')";

                if ($conn->query($sqlInsertCita) === TRUE) {
                    header("Location: detalle_cita.php");
                    exit();      
                } else {
                    $response['error'] = "Error al registrar la cita: " . $conn->error;
                }
            } else {
                $response['error'] = "Error al encontrar el paciente: " . $conn->error;
            }
        } catch (Exception $e) {
            $response['error'] = "Error: " . $e->getMessage();
            $response['error_details'] = $e;
        }
    } else {
        $response['error'] = "El paciente no está autenticado.";
    }
} else {
    $response['error'] = "Solicitud no válida. Se esperaba una solicitud GET o POST.";
}

if ($conn) {
    $conn->close();
}

echo json_encode($response);
exit(); 

?>