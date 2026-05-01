<?php
/**
 * Endpoint: Crear o actualizar paciente
 * POST /config/save_patient.php
 * Body: { id?, document_type, document_number, first_name, last_name,
 *         gender?, birth_date?, phone?, mobile?, email?, address?,
 *         eps_id?, ips_id?, range_level?, active }
 * Response: { success, message, data: { id } }
 */

require_once 'setup.php';

header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Detect which columns exist in the patients table
function _patientsHasExtendedCols($conn) {
    static $checked = null;
    if ($checked !== null) return $checked;
    $r = $conn->query("SHOW COLUMNS FROM patients LIKE 'active'");
    $checked = ($r && $r->num_rows > 0);
    return $checked;
}

try {
    require_auth();

    // ── Required fields ───────────────────────────────────────────────────
    $id             = trim($_POST['id']             ?? '');
    $documentType   = (int)($_POST['document_type']  ?? 13);
    $documentNumber = trim($_POST['document_number'] ?? '');
    $firstName      = trim($_POST['first_name']      ?? '');
    $lastName       = trim($_POST['last_name']        ?? '');
    $active         = (int)($_POST['active']          ?? 1);

    if (!$documentNumber) throw new Exception('El número de identificación es requerido');
    if (!$firstName)      throw new Exception('El nombre es requerido');
    if (!$lastName)       throw new Exception('El apellido es requerido');

    // ── Optional / extended fields ────────────────────────────────────────
    $gender     = trim($_POST['gender']      ?? '') ?: null;
    $birthDate  = trim($_POST['birth_date']  ?? '') ?: null;
    $phone      = trim($_POST['phone']       ?? '') ?: null;
    $mobile     = trim($_POST['mobile']      ?? '') ?: null;
    $email      = trim($_POST['email']       ?? '') ?: null;
    $address    = trim($_POST['address']     ?? '') ?: null;
    $epsId      = trim($_POST['eps_id']      ?? '') ?: null;
    $ipsId      = trim($_POST['ips_id']      ?? '') ?: null;
    $rangeLevel = isset($_POST['range_level']) && $_POST['range_level'] !== ''
                    ? (int)$_POST['range_level'] : null;

    $extended = _patientsHasExtendedCols($conn);

    if ($id) {
        // ── UPDATE ────────────────────────────────────────────────────────
        if ($extended) {
            $stmt = $conn->prepare("
                UPDATE patients SET
                    document_type   = ?,
                    document_number = ?,
                    first_name      = ?,
                    last_name       = ?,
                    gender          = ?,
                    birth_date      = ?,
                    phone           = ?,
                    mobile          = ?,
                    email           = ?,
                    address         = ?,
                    eps_id          = ?,
                    ips_id          = ?,
                    range_level     = ?,
                    active          = ?
                WHERE id = ?
            ");
            if (!$stmt) throw new Exception('Error preparando actualización: ' . $conn->error);
            $stmt->bind_param(
                'isssssssssssiis',
                $documentType, $documentNumber,
                $firstName, $lastName,
                $gender, $birthDate,
                $phone, $mobile, $email, $address,
                $epsId, $ipsId, $rangeLevel,
                $active, $id
            );
        } else {
            $stmt = $conn->prepare("
                UPDATE patients SET
                    document_type   = ?,
                    document_number = ?,
                    first_name      = ?,
                    last_name       = ?,
                    eps_id          = ?,
                    ips_id          = ?,
                    range_level     = ?
                WHERE id = ?
            ");
            if (!$stmt) throw new Exception('Error preparando actualización: ' . $conn->error);
            $stmt->bind_param(
                'isssssss',
                $documentType, $documentNumber,
                $firstName, $lastName,
                $epsId, $ipsId, $rangeLevel,
                $id
            );
        }
        $stmt->execute();
        $stmt->close();

        echo json_encode([
            'success' => true,
            'message' => 'Paciente actualizado correctamente',
            'data'    => ['id' => $id]
        ], JSON_UNESCAPED_UNICODE);

    } else {
        // ── INSERT ────────────────────────────────────────────────────────
        $chk = $conn->prepare("SELECT id FROM patients WHERE document_number = ? AND document_type = ? LIMIT 1");
        if ($chk) {
            $chk->bind_param('si', $documentNumber, $documentType);
            $chk->execute();
            $chk->store_result();
            if ($chk->num_rows > 0) {
                $chk->close();
                throw new Exception('Ya existe un paciente con ese número de identificación');
            }
            $chk->close();
        }

        $newId = sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );

        if ($extended) {
            $stmt = $conn->prepare("
                INSERT INTO patients
                    (id, document_type, document_number, first_name, last_name,
                     gender, birth_date, phone, mobile, email, address,
                     eps_id, ips_id, range_level, active)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            if (!$stmt) throw new Exception('Error preparando inserción: ' . $conn->error);
            $stmt->bind_param(
                'sissssssssssiii',
                $newId, $documentType, $documentNumber,
                $firstName, $lastName,
                $gender, $birthDate,
                $phone, $mobile, $email, $address,
                $epsId, $ipsId, $rangeLevel,
                $active
            );
        } else {
            $stmt = $conn->prepare("
                INSERT INTO patients
                    (id, document_type, document_number, first_name, last_name,
                     eps_id, ips_id, range_level)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            if (!$stmt) throw new Exception('Error preparando inserción: ' . $conn->error);
            $stmt->bind_param(
                'sisssssi',
                $newId, $documentType, $documentNumber,
                $firstName, $lastName,
                $epsId, $ipsId, $rangeLevel
            );
        }
        $stmt->execute();
        $stmt->close();

        echo json_encode([
            'success' => true,
            'message' => 'Paciente registrado correctamente',
            'data'    => ['id' => $newId]
        ], JSON_UNESCAPED_UNICODE);
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
