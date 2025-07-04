<?php

declare(strict_types=1);

namespace NumeroAreaExtenso\Tests;

use PHPUnit\Framework\TestCase;
use NumeroAreaExtenso\NumeroAreaPorExtenso;

/**
 * Testes para a classe NumeroAreaPorExtenso
 */
class NumeroAreaPorExtensoTest extends TestCase
{
  /**
   * Testa conversão de área completa com metros e centímetros quadrados
   */
  public function testConverteAreaCompleta(): void
  {
    // Teste com área que tem metros e centímetros
    $resultado = NumeroAreaPorExtenso::converteAreaCompleta('1207,35');
    $esperado = 'um mil e duzentos e sete metros quadrados, três mil e quinhentos centímetros quadrados';
    $this->assertEquals($esperado, $resultado);

    // Teste com área só com metros (sem centímetros)
    $resultado = NumeroAreaPorExtenso::converteAreaCompleta('360,00');
    $esperado = 'trezentos e sessenta metros quadrados';
    $this->assertEquals($esperado, $resultado);

    // Teste com área pequena
    $resultado = NumeroAreaPorExtenso::converteAreaCompleta('25,50');
    $esperado = 'vinte e cinco metros quadrados, cinco mil centímetros quadrados';
    $this->assertEquals($esperado, $resultado);
  }

  /**
   * Testa conversão de área com formatação (pontos de milhares)
   */
  public function testConverteAreaComFormatacao(): void
  {
    $resultado = NumeroAreaPorExtenso::converteAreaCompleta('1.500,75');
    $esperado = 'um mil e quinhentos metros quadrados, sete mil e quinhentos centímetros quadrados';
    $this->assertEquals($esperado, $resultado);
  }

  /**
   * Testa conversão de números simples
   */
  public function testConverteNumero(): void
  {
    // Números básicos
    $this->assertEquals('zero', NumeroAreaPorExtenso::converteNumero(0));
    $this->assertEquals('um', NumeroAreaPorExtenso::converteNumero(1));
    $this->assertEquals('quinze', NumeroAreaPorExtenso::converteNumero(15));
    $this->assertEquals('cem', NumeroAreaPorExtenso::converteNumero(100));
    $this->assertEquals('cento e vinte e três', NumeroAreaPorExtenso::converteNumero(123));

    // Números maiores
    $this->assertEquals('mil', NumeroAreaPorExtenso::converteNumero(1000));
    $this->assertEquals('dois mil e quinhentos', NumeroAreaPorExtenso::converteNumero(2500));
    $this->assertEquals('um milhão', NumeroAreaPorExtenso::converteNumero(1000000));
  }

  /**
   * Testa conversão com palavra feminina
   */
  public function testConverteNumeroFeminino(): void
  {
    $this->assertEquals('uma', NumeroAreaPorExtenso::converteNumero(1, true));
    $this->assertEquals('duas', NumeroAreaPorExtenso::converteNumero(2, true));
    $this->assertEquals('duzentas', NumeroAreaPorExtenso::converteNumero(200, true));
    $this->assertEquals('trezentas e uma', NumeroAreaPorExtenso::converteNumero(301, true));
  }

  /**
   * Testa conversão apenas de metros quadrados
   */
  public function testConverteMetrosQuadrados(): void
  {
    $resultado = NumeroAreaPorExtenso::converteMetrosQuadrados(250);
    $esperado = 'duzentos e cinquenta metros quadrados';
    $this->assertEquals($esperado, $resultado);

    $resultado = NumeroAreaPorExtenso::converteMetrosQuadrados(1);
    $esperado = 'um metros quadrados';
    $this->assertEquals($esperado, $resultado);
  }

  /**
   * Testa conversão apenas de centímetros quadrados
   */
  public function testConverteCentimetrosQuadrados(): void
  {
    $resultado = NumeroAreaPorExtenso::converteCentimetrosQuadrados(1500);
    $esperado = 'um mil e quinhentos centímetros quadrados';
    $this->assertEquals($esperado, $resultado);

    $resultado = NumeroAreaPorExtenso::converteCentimetrosQuadrados(50);
    $esperado = 'cinquenta centímetros quadrados';
    $this->assertEquals($esperado, $resultado);
  }

  /**
   * Testa casos extremos e edge cases
   */
  public function testCasosExtremos(): void
  {
    // Valores não numéricos
    $this->assertEquals('zero metros quadrados', NumeroAreaPorExtenso::converteMetrosQuadrados('abc'));
    $this->assertEquals('zero', NumeroAreaPorExtenso::converteNumero(''));

    // Área zerada
    $this->assertEquals('zero metros quadrados', NumeroAreaPorExtenso::converteAreaCompleta('0,00'));

    // Área só com centímetros
    $this->assertEquals(
      'zero metros quadrados, dois mil centímetros quadrados',
      NumeroAreaPorExtenso::converteAreaCompleta('0,20')
    );
  }

  /**
   * Testa remoção de formatação
   */
  public function testRemoverFormatacaoNumero(): void
  {
    $this->assertEquals('1500', NumeroAreaPorExtenso::removerFormatacaoNumero('1.500'));
    $this->assertEquals('1500.25', NumeroAreaPorExtenso::removerFormatacaoNumero('1.500,25'));
    $this->assertEquals('250', NumeroAreaPorExtenso::removerFormatacaoNumero('R$250'));
    $this->assertEquals('1000.50', NumeroAreaPorExtenso::removerFormatacaoNumero('R$ 1.000,50'));
  }

  /**
   * Testa cenários típicos de documentos imobiliários
   */
  public function testCenariosImobiliarios(): void
  {
    // Lotes típicos
    $lote7 = NumeroAreaPorExtenso::converteNumero(7);
    $this->assertEquals('sete', $lote7);

    $quadra12 = NumeroAreaPorExtenso::converteNumero(12);
    $this->assertEquals('doze', $quadra12);

    // Áreas típicas
    $area1 = NumeroAreaPorExtenso::converteAreaCompleta('450,00');
    $this->assertEquals('quatrocentos e cinquenta metros quadrados', $area1);

    $area2 = NumeroAreaPorExtenso::converteAreaCompleta('1200,50');
    $this->assertEquals('um mil e duzentos metros quadrados, cinco mil centímetros quadrados', $area2);
  }

  /**
   * Testa performance com números grandes
   */
  public function testNumerosGrandes(): void
  {
    $resultado = NumeroAreaPorExtenso::converteNumero(1234567);
    $esperado = 'um milhão e duzentos e trinta e quatro mil e quinhentos e sessenta e sete';
    $this->assertEquals($esperado, $resultado);

    $resultado = NumeroAreaPorExtenso::converteAreaCompleta('10000,99');
    $esperado = 'dez mil metros quadrados, nove mil e novecentos centímetros quadrados';
    $this->assertEquals($esperado, $resultado);
  }

  /**
   * Testa consistência com diferentes formatos de entrada
   */
  public function testConsistenciaFormatos(): void
  {
    // Mesmo valor, formatos diferentes
    $formato1 = NumeroAreaPorExtenso::converteAreaCompleta('1500,25');
    $formato2 = NumeroAreaPorExtenso::converteAreaCompleta('1.500,25');

    $this->assertEquals($formato1, $formato2);

    // Testando com zeros à esquerda na parte decimal
    $resultado1 = NumeroAreaPorExtenso::converteAreaCompleta('100,05');
    $resultado2 = NumeroAreaPorExtenso::converteAreaCompleta('100,5');

    // Ambos devem gerar o mesmo resultado para 500 cm²
    $this->assertStringContains('quinhentos centímetros quadrados', $resultado1);
    $this->assertStringContains('cinco mil centímetros quadrados', $resultado2);
  }
}
