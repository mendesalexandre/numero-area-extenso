<?php

require_once __DIR__ . '/../vendor/autoload.php';

use NumeroAreaExtenso\NumeroAreaPorExtenso;

/**
 * Exemplo prático: Geração de texto para documento imobiliário
 */

// Dados do lote
$lote = 7;
$quadra = 3;
$area = '450,25';
$loteamento = 'BAIRRO VILLAGIO';
$municipio = 'Sinop';
$estado = 'Mato Grosso';

// Confrontações
$confrontacoes = [
  'NORTE' => ['descricao' => 'Lote 06', 'medida' => '15,000m'],
  'SUL' => ['descricao' => 'Lote 08', 'medida' => '15,000m'],
  'LESTE' => ['descricao' => 'Rua das Palmeiras', 'medida' => '30,000m'],
  'OESTE' => ['descricao' => 'Área Institucional', 'medida' => '30,000m']
];

// Gerando o texto do documento
$numeroLote = str_pad($lote, 2, '0', STR_PAD_LEFT);
$numeroQuadra = str_pad($quadra, 2, '0', STR_PAD_LEFT);

$texto = sprintf(
  'LOTE nº %s (%s), da QUADRA nº %s (%s), com a área de %sm² (%s), situado no Loteamento denominado "%s", no Município de %s, Estado de %s',
  $numeroLote,
  NumeroAreaPorExtenso::converteNumero($lote),
  $numeroQuadra,
  NumeroAreaPorExtenso::converteNumero($quadra),
  $area,
  NumeroAreaPorExtenso::converteAreaCompleta($area),
  $loteamento,
  $municipio,
  $estado
);

// Adicionando confrontações
$texto .= ', dentro dos seguintes limites e confrontações: ';

$confrontacoesTexto = [];
foreach ($confrontacoes as $direcao => $dados) {
  $confrontacoesTexto[] = "{$direcao} - com {$dados['descricao']}, medindo {$dados['medida']}";
}

$texto .= implode('; ', $confrontacoesTexto) . '.';

echo "=== DOCUMENTO IMOBILIÁRIO ===\n\n";
echo $texto . "\n\n";

echo "=== EXEMPLOS INDIVIDUAIS ===\n\n";

// Exemplos de uso individual
echo "Conversões de números:\n";
echo "Lote 7: " . NumeroAreaPorExtenso::converteNumero(7) . "\n";
echo "Quadra 15: " . NumeroAreaPorExtenso::converteNumero(15) . "\n";
echo "Com palavra feminina (1): " . NumeroAreaPorExtenso::converteNumero(1, true) . "\n";
echo "Com palavra feminina (2): " . NumeroAreaPorExtenso::converteNumero(2, true) . "\n\n";

echo "Conversões de áreas:\n";
echo "360,00m²: " . NumeroAreaPorExtenso::converteAreaCompleta('360,00') . "\n";
echo "1.207,35m²: " . NumeroAreaPorExtenso::converteAreaCompleta('1.207,35') . "\n";
echo "25,50m²: " . NumeroAreaPorExtenso::converteAreaCompleta('25,50') . "\n\n";

echo "Conversões específicas:\n";
echo "250 metros quadrados: " . NumeroAreaPorExtenso::converteMetrosQuadrados(250) . "\n";
echo "1500 centímetros quadrados: " . NumeroAreaPorExtenso::converteCentimetrosQuadrados(1500) . "\n\n";

echo "=== CASOS ESPECIAIS ===\n\n";

// Testando diferentes formatos
$areasExemplo = ['1.500,00', '850,75', '12.000,99', '100,05'];

foreach ($areasExemplo as $areaExemplo) {
  echo "Área {$areaExemplo}m²: " . NumeroAreaPorExtenso::converteAreaCompleta($areaExemplo) . "\n";
}

echo "\n=== PERFORMANCE TEST ===\n\n";

// Teste de performance com números grandes
$inicio = microtime(true);

for ($i = 1; $i <= 1000; $i++) {
  $area = rand(100, 10000) . ',' . str_pad(rand(1, 99), 2, '0', STR_PAD_LEFT);
  NumeroAreaPorExtenso::converteAreaCompleta($area);
}

$fim = microtime(true);
$tempo = round(($fim - $inicio) * 1000, 2);

echo "Conversão de 1000 áreas aleatórias: {$tempo}ms\n";
echo "Média por conversão: " . round($tempo / 1000, 2) . "ms\n\n";

echo "=== COMPARAÇÃO COM NÚMEROS GRANDES ===\n\n";

$numerosGrandes = [1000000, 5500000, 12345678];

foreach ($numerosGrandes as $numero) {
  echo "Número {$numero}: " . NumeroAreaPorExtenso::converteNumero($numero) . "\n";
}
