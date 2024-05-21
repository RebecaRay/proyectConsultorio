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
$eliminarMedicoSql = '';  


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true); // Lee datos del cuerpo de la solicitud JSON
    header('Content-Type: application/json');

    if (isset($data['action'])) {

        $action = $data['action'];

        if ($action === 'eliminar' && isset($data['id_medico'])) {
            // Eliminar médico
            $medicoId = $data['id_medico'];
            $eliminarMedicoSql = "DELETE FROM medicos WHERE id_medico = ?";
            $stmt = $conn->prepare($eliminarMedicoSql);

            if ($stmt) {
                $stmt->bind_param("i", $medicoId);
                
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Médico eliminado correctamente', 'reload' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => $stmt->error]);
                }
                $stmt->close();
                exit;
            } else {
                echo json_encode(['success' => false, 'error' => $conn->error]);
                exit;
            }

        } elseif ($action === 'editar' && isset($data['id_medico'])) {
            // Obtener información del médico para editar
            $medicoId = $data['id_medico'];
            $editarMedicoSql = "SELECT * FROM medicos WHERE id_medico = ?";
            $stmt = $conn->prepare($editarMedicoSql);

            if ($stmt) {
                $stmt->bind_param("i", $medicoId);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    $medico = $result->fetch_assoc();
                    if ($medico) {
                        echo json_encode(['success' => true, 'medico' => $medico]);
                    } else {
                        echo json_encode(['success' => false, 'error' => 'Médico no encontrado']);
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
            $especialidad = $data['especialidad'];
            $telefono = $data['telefono'];
            $email = $data['email'];

            $agregarMedicoSql = "INSERT INTO medicos (nombre, apellido, especialidad, telefono, email) 
                                 VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($agregarMedicoSql);

            if ($stmt) {
                $stmt->bind_param("sssss", $nombre, $apellido, $especialidad, $telefono, $email);

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

    if (isset($data['id_medico'])) {
        $id_medico = $data['id_medico'];
        $nombre = $data['nombre'];
        $apellido = $data['apellido'];
        $especialidad = $data['especialidad'];
        $telefono = $data['telefono'];
        $email = $data['email'];

        $actualizarMedicoSql = "UPDATE medicos SET nombre=?, apellido=?, especialidad=?, telefono=?, 
        email=? WHERE id_medico=?";
        $stmt = $conn->prepare($actualizarMedicoSql);

        if ($stmt) {
            $stmt->bind_param("sssssi", $nombre, $apellido, $especialidad, $telefono, $email, $id_medico);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'data']);
            } else {
                echo json_encode(['success' => false, 'error' => $stmt->error]);
            }

            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
    }
}


$query = "SELECT id_medico, nombre, apellido, especialidad, telefono, email FROM medicos ";
$result = mysqli_query($conn, $query);

$rows = array();


if ($result) {
    while ($row = $result->fetch_assoc()) {
        $rowData = array(
            'id_medico' => $row['id_medico'],
            'nombre' => $row['nombre'],
            'apellido' => $row['apellido'],
            'especialidad' => $row['especialidad'],
            'telefono' => $row['telefono'],
            'email' => $row['email'],
        );
        $rows[] = $rowData;
    }
     $result->free_result();
    $response['success'] = true;
    $response['data'] = $rows;
} else {
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
        echo '<td>' . $row['especialidad'] . '</td>';
        echo '<td>' . $row['telefono'] . '</td>';
        echo '<td>' . $row['email'] . '</td>';
        echo '<td><button class="btn btn-info" onclick="editarMedico(' . $row['id_medico'] . ')">Editar</button></td>';
        echo '<td><button class="btn btn-danger" onclick="eliminarMedico(' . $row['id_medico'] . ')">Eliminar</button></td>';
        echo '</tr>';
    }
}

$conn->close();
?>