<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';

class AuthController {

    public function login(string $email, string $password): array {
        $db = getDB();
        $stmt = $db->prepare(
            'SELECT id, nombre, apellido, email, password, rol FROM usuarios WHERE email = ? AND activo = 1 LIMIT 1'
        );
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user   = $result->fetch_assoc();
        $stmt->close();
        $db->close();

        if (!$user || !password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Credenciales incorrectas.'];
        }

        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['nombre'] . ' ' . $user['apellido'];
        $_SESSION['user_role'] = $user['rol'];

        return ['success' => true, 'role' => $user['rol']];
    }

    public function register(array $data): array {
        $required = ['nombre', 'apellido', 'email', 'password', 'rol'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return ['success' => false, 'message' => "El campo $field es obligatorio."];
            }
        }

        $rolesPermitidos = [ROLE_CLIENTE, ROLE_PROVEEDOR];
        if (!in_array($data['rol'], $rolesPermitidos, true)) {
            return ['success' => false, 'message' => 'Rol no permitido en el registro.'];
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Email no válido.'];
        }

        if (strlen($data['password']) < 8) {
            return ['success' => false, 'message' => 'La contraseña debe tener al menos 8 caracteres.'];
        }

        $db = getDB();

        // Verificar email único
        $check = $db->prepare('SELECT id FROM usuarios WHERE email = ? LIMIT 1');
        $check->bind_param('s', $data['email']);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $check->close();
            $db->close();
            return ['success' => false, 'message' => 'El email ya está registrado.'];
        }
        $check->close();

        $hash = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);

        $stmt = $db->prepare(
            'INSERT INTO usuarios (nombre, apellido, email, password, rol) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->bind_param(
            'sssss',
            $data['nombre'],
            $data['apellido'],
            $data['email'],
            $hash,
            $data['rol']
        );

        if ($stmt->execute()) {
            $stmt->close();
            $db->close();
            return ['success' => true, 'message' => 'Registro exitoso. Ya puedes iniciar sesión.'];
        }

        $stmt->close();
        $db->close();
        return ['success' => false, 'message' => 'Error al registrar. Inténtalo de nuevo.'];
    }

    public function logout(): void {
        session_destroy();
        header('Location: ' . APP_URL . '/public/login.php');
        exit;
    }

    public static function requireAuth(string $rol = ''): void {
        if (empty($_SESSION['user_id'])) {
            header('Location: ' . APP_URL . '/public/login.php');
            exit;
        }
        if ($rol && $_SESSION['user_role'] !== $rol) {
            header('Location: ' . APP_URL . '/public/acceso_denegado.php');
            exit;
        }
    }
}
