<?php
require_once __DIR__ . '/../../funcoesPHP/connection.php';

header('Content-Type: application/json; charset=utf-8');

$now = new DateTime();
$year = (int)$now->format('Y');
$monthNow = (int)$now->format('n');

$meses = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
$meses_ate_agora = array_slice($meses, 0, $monthNow);

try {
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
            $index = $mes - 1;
            $faturamento_por_mes[$index] = (float)$r['faturamento'];
        }
    }

    // Sem tabelas específicas para acessos/abandono no schema atual.
    $acessos_por_mes = array_fill(0, $monthNow, 0);
    $abandono_por_mes = array_fill(0, $monthNow, 0);

    // Gênero dos clientes (usuários)
    // Espera-se que a coluna usuario.genero exista com valores:
    // masculino, feminino, outros, prefiro_nao_dizer
    $generos = ['feminino','masculino','outros','prefiro_nao_dizer'];
    $genero_counts = array_fill_keys($generos, 0);

    try {
        $sqlGenero = "SELECT genero, COUNT(*) AS qtd FROM usuario GROUP BY genero";
        $stmtGenero = $pdo->query($sqlGenero);
        $rowsGenero = $stmtGenero->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rowsGenero as $row) {
            $g = $row['genero'] ?? null;
            $qtd = (int)($row['qtd'] ?? 0);
            if ($g && array_key_exists($g, $genero_counts)) {
                $genero_counts[$g] = $qtd;
            }
        }
    } catch (Throwable $e) {
        // Caso a coluna ainda não exista, mantém zeros.
    }

    $series_genero = [
        (int)($genero_counts['feminino'] ?? 0),
        (int)($genero_counts['masculino'] ?? 0),
        (int)($genero_counts['outros'] ?? 0),
        (int)($genero_counts['prefiro_nao_dizer'] ?? 0),
    ];

    echo json_encode([
        'meses' => $meses_ate_agora,
        'faturamento_mensal' => $faturamento_por_mes,
        'acessos' => $acessos_por_mes,
        'abandono' => $abandono_por_mes,
        'genero' => [
            'labels' => ['Feminino','Masculino','Outros','Prefiro não dizer'],
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


