<?php
require_once __DIR__ . '/../../funcoesPHP/connection.php';

header('Content-Type: application/json; charset=utf-8');

$now = new DateTime();
$year = (int)$now->format('Y');
$monthNow = (int)$now->format('n');

$meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
$meses_ate_agora = array_slice($meses, 0, $monthNow);

try {
    // 1. Consulta Faturamento Mensal
    $sql = "
        SELECT MONTH(data_pedido) AS mes,
               SUM(total) AS faturamento
        FROM pedidos
        WHERE YEAR(data_pedido) = :ano
        GROUP BY MONTH(data_pedido)
        ORDER BY mes ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':ano' => $year]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $faturamento_por_mes = array_fill(0, $monthNow, 0);

    foreach ($rows as $r) {
        $mes = (int)$r['mes'];
        if ($mes >= 1 && $mes <= $monthNow) {
            $faturamento_por_mes[$mes - 1] = (float)$r['faturamento'];
        }
    }

    $acessos_por_mes = array_fill(0, $monthNow, 0);
    $abandono_por_mes = array_fill(0, $monthNow, 0);

    // 2. Consulta Gênero dos Clientes
    $generos = ['feminino', 'masculino', 'outros', 'prefiro_nao_dizer'];
    $genero_counts = array_fill_keys($generos, 0);

    try {
        $sqlGenero = "SELECT genero, COUNT(*) AS qtd FROM usuario GROUP BY genero";
        $stmtGenero = $pdo->query($sqlGenero);
        $rowsGenero = $stmtGenero->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($rowsGenero as $row) {
            $g = strtolower($row['genero'] ?? '');
            $qtd = (int)($row['qtd'] ?? 0);
            if (array_key_exists($g, $genero_counts)) {
                $genero_counts[$g] = $qtd;
            }
        }
    } catch (Throwable $e) {
        // Mantém os zeros caso a coluna ou tabela não existam
    }

    $series_genero = [
        $genero_counts['feminino'],
        $genero_counts['masculino'],
        $genero_counts['outros'],
        $genero_counts['prefiro_nao_dizer'],
    ];

    echo json_encode([
        'meses' => $meses_ate_agora,
        'faturamento_mensal' => $faturamento_por_mes,
        'acessos' => $acessos_por_mes,
        'abandono' => $abandono_por_mes,
        'genero' => [
            'labels' => ['Feminino', 'Masculino', 'Outros', 'Prefiro não dizer'],
            'series' => $series_genero
        ],
        'ano' => $year
    ]);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erro ao montar dados dos gráficos',
        'details' => $e->getMessage()
    ]);
}