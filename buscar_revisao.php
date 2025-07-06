<?php
include_once('conexao.php');
header('Content-Type: application/json; charset=utf-8');

try {
    $stmt = $pdo->query("SELECT  r.codigo, r.cod_moto, md.nome AS modelo_nome, c.nome AS cor_nome, m.tipo, r.data_revisao AS data_revisao, 
    CASE r.situacao WHEN 1 THEN 'Pendente' WHEN 2 THEN 'Em andamento' WHEN 3 THEN 'Concluída' END AS situacao
  FROM moto m
  JOIN modelo md ON m.cod_modelo = md.codigo
  JOIN cor c ON m.cod_cor = c.codigo
  JOIN revisao r ON r.cod_moto = m.codigo
  ORDER BY r.data_revisao");
    $revisoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($revisoes);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao buscar revisões: ' . $e->getMessage()]);
}
?>