<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "consultorio";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión     
if ($conn->connect_error) {
    die("La conexión ha fallado: " . $conn->connect_error);
}

$response = ['success' => false];
$eliminarPacienteSql = '';  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true); 
    header('Content-Type: application/json');

    if (isset($data['action'])) {

        $action = $data['action'];

        if ($action === 'eliminar' && isset($data['id_paciente'])) {
            $pacienteId = $data['id_paciente'];
            $eliminarPacienteSql = "DELETE FROM pacientes WHERE id_paciente = ?";
            $stmt = $conn->prepare($eliminarPacienteSql);

            if ($stmt) {
                $stmt->bind_param("i", $pacienteId);
                
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Paciente eliminado correctamente', 
                    'reload' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => $stmt->error]);
                }
                $stmt->close();
                exit;
            } else {
                echo json_encode(['success' => false, 'error' => $conn->error]);
                exit;
            }

        } elseif ($action === 'editar' && isset($data['id_paciente'])) {
            $pacienteId = $data['id_paciente'];
            $editarPacienteSql = "SELECT * FROM pacientes WHERE id_paciente = ?";
            $stmt = $conn->prepare($editarPacienteSql);

            if ($stmt) {
                $stmt->bind_param("i", $pacienteId);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    $paciente = $result->fetch_assoc();
                    if ($paciente) {
                        echo json_encode(['success' => true, 'paciente' => $paciente]);
                    } else {
                        echo json_encode(['success' => false, 'error' => 'Paciente no encontrado']);
                    }
                    exit;
                } else {
                    echo json_encode(['success' => false, 'error' => $conn->error]);
                    exit;
                }
            } else {
                echo json_encode(['success' => false, 'error' => $conn->error]);
                exit;
            }

        } elseif ($action === 'agregar') {
            $nombre = $data['nombre'];
            $apellido = $data['apellido'];
            $fechaNacimiento = $data['fecha_nacimiento'];
            $genero = $data['genero'];
            $telefono = $data['telefono'];
            $email = $data['email'];

            $agregarPacienteSql = "INSERT INTO pacientes (nombre, apellido, fecha_nacimiento, genero, telefono, email) 
                                   VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($agregarPacienteSql);

            if ($stmt) {
                $stmt->bind_param("ssssss", $nombre, $apellido, $fechaNacimiento, $genero, $telefono, $email);

                if ($stmt->execute()) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => $stmt->error]);
                }
                $stmt->close();
                exit;
            } else {
                echo json_encode(['success' => false, 'error' => $conn->error]);
                exit;
            }
        }
    }

    if (isset($data['id_paciente'])) {
        $id_paciente = $data['id_paciente'];
        $nombre = $data['nombre'];
        $apellido = $data['apellido'];
        $fechaNacimiento = $data['fecha_nacimiento'];
        $genero = $data['genero'];
        $telefono = $data['telefono'];
        $email = $data['email'];

        $actualizarPacienteSql = "UPDATE pacientes SET nombre=?, apellido=?, fecha_nacimiento=?, 
        genero=?, telefono=?, email=? WHERE id_paciente=?";
        $stmt = $conn->prepare($actualizarPacienteSql);

        if ($stmt) {
            // Vincula los parámetros
            $stmt->bind_param("ssssssi", $nombre, $apellido, $fechaNacimiento, $genero, $telefono, $email, $id_paciente);

            // Ejecuta la consulta
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => $stmt->error]);
            }

            // Cierra el statement
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
    }
}

// Si no se recibe una solicitud de eliminación, edición o actualización, continúa con la recuperación de datos 
//de pacientes
$query = "SELECT id_paciente, nombre, apellido, fecha_nacimiento, genero, telefono, email FROM pacientes ";
$result = mysqli_query($conn, $query);

$rows = array();

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $rowData = array(
            'id_paciente' => $row['id_paciente'],
            'nombre' => $row['nombre'],
            'apellido' => $row['apellido'],
            'fecha_nacimiento' => $row['fecha_nacimiento'],
            'genero' => $row['genero'],
            'telefono' => $row['telefono'],
            'email' => $row['email'],
        );
        $rows[] = $rowData;
    }
    $result->free_result();
    $response['success'] = true;
    $response['data'] = $rows;
} else {
    // Manejar el error si la consulta falla
    $response['success'] = false;
    $response['error'] = 'Error al ejecutar la consulta SQL: ' . mysqli_error($conn); // Obtener detalles del error
}

$jsonResponse = json_encode($response);

$isAjaxRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

if ($isAjaxRequest) {
    echo $jsonResponse;
} else {
    $rows = $response['data'];
    foreach ($rows as $row) {
        echo '<tr>';
        echo '<td>' . $row['nombre'] . '</td>';
        echo '<td>' . $row['apellido'] . '</td>';
        echo '<td>' . $row['fecha_nacimiento'] . '</td>';
        echo '<td>' . $row['genero'] . '</td>';
        echo '<td>' . $row['telefono'] . '</td>';
        echo '<td>' . $row['email'] . '</td>';
        echo '<td><button class="btn btn-info" 
        onclick="editarPaciente(' . $row['id_paciente'] . ')">Editar</button></td>';
        echo '<td><button class="btn btn-danger" 
        onclick="eliminarPaciente(' . $row['id_paciente'] . ')">Eliminar</button></td>';
        echo '</tr>';
    }
}

$conn->close();
?>
