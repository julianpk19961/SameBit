<?php
include 'config.php';
date_default_timezone_set('America/Bogota');

$today = date('Y-m-d');

$sql = "SELECT
    TIME_FORMAT(pr.checkin_time, '%H:%i')      AS hora,
    CONCAT(p.first_name, ' ', p.last_name)     AS paciente,
    p.document_number                          AS documento,
    COALESCE(e_eps.name, '—')                 AS eps,
    COALESCE(e_ips.name, '—')                 AS ips,
    COALESCE(d.code, '—')                     AS diagnostico,
    pr.contact_type                            AS tipo_contacto,
    pr.approved                                AS aprobado,
    pr.created_by                              AS registrado_por
FROM priorities pr
LEFT JOIN patients  p     ON pr.patient_id     = p.id
LEFT JOIN entities e_eps  ON pr.eps_id         = e_eps.id
LEFT JOIN entities e_ips  ON pr.ips_id         = e_ips.id
LEFT JOIN diagnoses d     ON pr.diagnosis_id   = d.id
WHERE DATE(pr.created_at) = '$today'
ORDER BY pr.checkin_time DESC";

$result = mysqli_query($conn, $sql);

$data = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

header('Content-Type: application/json; charset=UTF-8');
echo json_encode(['data' => $data], JSON_OUT);
mysqli_close($conn);
