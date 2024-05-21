<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "consultorio";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La conexión ha fallado: " . $conn->connect_error);
}

$response = ['success' => false];
$eliminarUsuarioSql = '';  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true); 
    header('Content-Type: application/json');

    if (isset($data['action'])) {
        $action = $data['action'];

        if ($action === 'eliminar' && isset($data['id'])) {
            $usuarioId = $data['id'];
            $eliminarUsuarioSql = "DELETE FROM usuarios WHERE id = ?";
            $stmt = $conn->prepare($eliminarUsuarioSql);

            if ($stmt) {
                $stmt->bind_param("i", $usuarioId);

                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Usuario eliminado correctamente', 
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

        } elseif ($action === 'editar' && isset($data['id'])) {
            $usuarioId = $data['id'];
            $editarUsuarioSql = "SELECT * FROM usuarios WHERE id = ?";
            $stmt = $conn->prepare($editarUsuarioSql);

            if ($stmt) {
                $stmt->bind_param("i", $usuarioId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result) {
                    $usuario = $result->fetch_assoc();

                    if ($usuario) {
                        echo json_encode(['success' => true, 'usuario' => $usuario]);
                    } else {
                        echo json_encode(['success' => false, 'error' => 'Usuario no encontrado']);
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
            $email = $data['email'];
            $password = $data['password'];
            $role = $data['role'];
            $hashedPassword = md5($password);

            $agregarUsuarioSql = "INSERT INTO usuarios (email, password, role) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($agregarUsuarioSql);

            if ($stmt) {
                $stmt->bind_param("sss", $email, $hashedPassword, $role);

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

    if (isset($data['id'])) {
        $id_usuario = $data['id'];
        $email = $data['email'];
        $password = $data['password'];
        $role = $data['role'];
        $hashedPassword = md5($password);

        $actualizarUsuarioSql = "UPDATE usuarios SET email=?, password=?, role=? WHERE id=?";
        $stmt = $conn->prepare($actualizarUsuarioSql);

        if ($stmt) {
            $stmt->bind_param("sssi", $email, $hashedPassword, $role, $id_usuario);

            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => $stmt->error]);
            }

            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
    }
}

// Si no se recibe una solicitud de eliminación, edición o actualización, continúa con la recuperación 
//de datos de usuarios

$query = "SELECT id, email, password, role FROM usuarios ";
$result = mysqli_query($conn, $query);

$rows = array();

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $rowData = array(
            'id' => $row['id'],
            'email' => $row['email'],
            'password' => $row['password'],
            'role' => $row['role'],
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
        echo '<td>' . $row['email'] . '</td>';
        echo '<td>' . $row['password'] . '</td>';
        echo '<td>' . $row['role'] . '</td>';
        echo '<td><button class="btn btn-info" onclick="editarUsuario(' . $row['id'] . ')">Editar</button></td>';
        echo '<td><button class="btn btn-danger" onclick="eliminarUsuario(' . $row['id'] . ')">Eliminar</button></td>';
        echo '</tr>';
    }
}

$conn->close();
?>
