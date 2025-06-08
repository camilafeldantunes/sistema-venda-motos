<?php
include_once('conexao.php');
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT mt.codigo, md.nome AS modelo_nome, c.nome AS cor_nome, mt.data_fabricacao, CASE mt.status WHEN 1 THEN 'Disponível' WHEN 2 THEN 'Vendida' END AS status_nome,
  CASE mt.tipo WHEN 1 THEN 'Nova' WHEN 2 THEN 'Usada' END AS tipo_nome, mt.placa  
    FROM cor c 
    JOIN moto mt ON mt.cod_cor = c.codigo 
    JOIN modelo md ON mt.cod_modelo = md.codigo ORDER BY md.nome");
    $motos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($motos);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao buscar motos: ' . $e->getMessage()]);
}
?>