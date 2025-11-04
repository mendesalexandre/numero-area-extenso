<?php

declare(strict_types=1);

namespace NumeroAreaExtenso;

/**
 * Classe para conversão de números e áreas para formato por extenso
 *
 * Especializada para documentos imobiliários, escrituras e registros.
 * Converte áreas em metros quadrados e centímetros quadrados.
 *
 * @package NumeroAreaExtenso
 * @author Alexandre Teixeira Mendes <alex.fnvip@gmail.com>
 * @version 1.0.0
 * @license MIT
 */
class NumeroAreaPorExtenso
{
  /**
   * Remove formatação de números (pontos, R$, etc.)
   *
   * @param string $strNumero Número com formatação
   * @return string Número limpo
   */
  public static function removerFormatacaoNumero(string $strNumero): string
  {
    $strNumero = trim(str_replace("R$", "", $strNumero));
    $vetVirgula = explode(",", $strNumero);

    if (count($vetVirgula) == 1) {
      $acentos = array(".");
      $resultado = str_replace($acentos, "", $strNumero);
      return $resultado;
    } else if (count($vetVirgula) != 2) {
      return $strNumero;
    }

    $strNumero = $vetVirgula[0];
    $strDecimal = mb_substr($vetVirgula[1], 0, 2);

    $acentos = array(".");
    $resultado = str_replace($acentos, "", $strNumero);
    $resultado = $resultado . "." . $strDecimal;

    return $resultado;
  }

  /**
   * Converte área completa para extenso (metros quadrados + centímetros quadrados)
   *
   * @param string $area Área no formato "1207,35"
   * @return string Área por extenso
   *
   * @example converteAreaCompleta('1207,35') retorna "um mil e duzentos e sete metros quadrados, três mil e quinhentos centímetros quadrados"
   * @example converteAreaCompleta('360,00') retorna "trezentos e sessenta metros quadrados"
   */
  public static function converteAreaCompleta(string $area): string
  {
    $area = str_replace('.', '', $area); // Remove pontos de milhares
    $partes = explode(',', $area);

    $metrosQuadrados = intval($partes[0]);
    $centimetrosQuadrados = 0;

    if (isset($partes[1]) && intval($partes[1]) > 0) {
      // Converte centésimos para centímetros quadrados
      $decimal = intval($partes[1]);
      if (strlen($partes[1]) == 2) {
        $centimetrosQuadrados = $decimal * 100; // 35 -> 3500 cm²
      } else {
        $centimetrosQuadrados = $decimal * 1000; // 5 -> 5000 cm²
      }
    }

    $resultado = self::numeroParaExtenso($metrosQuadrados) . ' metros quadrados';

    if ($centimetrosQuadrados > 0) {
      $resultado .= ', ' . self::numeroParaExtenso($centimetrosQuadrados) . ' centímetros quadrados';
    }

    return $resultado;
  }

  /**
   * Converte apenas metros quadrados para extenso
   *
   * @param mixed $valor Valor numérico
   * @return string Metros quadrados por extenso
   *
   * @example converteMetrosQuadrados(250) retorna "duzentos e cinquenta metros quadrados"
   */
  public static function converteMetrosQuadrados($valor): string
  {
    if (!is_numeric($valor)) {
      $valor = 0;
    }

    return self::numeroParaExtenso($valor) . ' metros quadrados';
  }

  /**
   * Converte apenas centímetros quadrados para extenso
   *
   * @param mixed $valor Valor numérico
   * @return string Centímetros quadrados por extenso
   *
   * @example converteCentimetrosQuadrados(1500) retorna "um mil e quinhentos centímetros quadrados"
   */
  public static function converteCentimetrosQuadrados($valor): string
  {
    if (!is_numeric($valor)) {
      $valor = 0;
    }

    return self::numeroParaExtenso($valor) . ' centímetros quadrados';
  }

  /**
   * Converte número simples para extenso (para lotes, quadras, etc)
   *
   * @param mixed $valor Valor numérico
   * @param bool $bolPalavraFeminina Se true, usa forma feminina
   * @return string Número por extenso
   *
   * @example converteNumero(15) retorna "quinze"
   * @example converteNumero(1, true) retorna "uma"
   */
  public static function converteNumero($valor, bool $bolPalavraFeminina = false): string
  {
    // Trata valores vazios
    if ($valor === '' || $valor === null) {
      $valor = 0;
    }

    // Converte para numérico se for string numérica, senão usa 0
    $valor = is_numeric($valor) ? floatval($valor) : 0;

    return self::numeroParaExtenso($valor, $bolPalavraFeminina);
  }

  /**
   * Método principal para conversão de números para extenso
   *
   * @param mixed $valor Valor a ser convertido
   * @param bool $bolPalavraFeminina Se true, usa forma feminina
   * @return string Número por extenso
   */
  private static function numeroParaExtenso($valor, bool $bolPalavraFeminina = false): string
  {
    if (!is_numeric($valor)) {
      $valor = 0;
    }

    if ($valor == 0) {
      return "zero";
    }

    $c = array(
      "",
      "cem",
      "duzentos",
      "trezentos",
      "quatrocentos",
      "quinhentos",
      "seiscentos",
      "setecentos",
      "oitocentos",
      "novecentos"
    );
    $d = array(
      "",
      "dez",
      "vinte",
      "trinta",
      "quarenta",
      "cinquenta",
      "sessenta",
      "setenta",
      "oitenta",
      "noventa"
    );
    $d10 = array(
      "dez",
      "onze",
      "doze",
      "treze",
      "quatorze",
      "quinze",
      "dezesseis",
      "dezessete",
      "dezoito",
      "dezenove"
    );
    $u = array(
      "",
      "um",
      "dois",
      "três",
      "quatro",
      "cinco",
      "seis",
      "sete",
      "oito",
      "nove"
    );

    if ($bolPalavraFeminina) {
      if ($valor == 1) {
        $u = array(
          "",
          "uma",
          "duas",
          "três",
          "quatro",
          "cinco",
          "seis",
          "sete",
          "oito",
          "nove"
        );
      } else {
        $u = array(
          "",
          "um",
          "duas",
          "três",
          "quatro",
          "cinco",
          "seis",
          "sete",
          "oito",
          "nove"
        );
      }
      $c = array(
        "",
        "cem",
        "duzentas",
        "trezentas",
        "quatrocentas",
        "quinhentas",
        "seiscentas",
        "setecentas",
        "oitocentas",
        "novecentas"
      );
    }

    $escala = array("", "mil", "milhões", "bilhões", "trilhões");
    $escala_singular = array("", "mil", "milhão", "bilhão", "trilhão");

    $valor = number_format($valor, 0, "", "");
    $grupos = array();

    // Divide o número em grupos de 3 dígitos
    while (strlen($valor) > 0) {
      if (strlen($valor) >= 3) {
        array_unshift($grupos, substr($valor, -3));
        $valor = substr($valor, 0, -3);
      } else {
        array_unshift($grupos, $valor);
        $valor = "";
      }
    }

    $resultado = array();
    $totalGrupos = count($grupos);

    for ($i = 0; $i < $totalGrupos; $i++) {
      $grupo = intval($grupos[$i]);
      $posicaoEscala = $totalGrupos - $i - 1;

      if ($grupo > 0) {
        $centena = intval($grupo / 100);
        $dezena = intval(($grupo % 100) / 10);
        $unidade = $grupo % 10;

        $textoGrupo = "";

        // Centenas
        if ($centena > 0) {
          if ($grupo == 100) {
            $textoGrupo = "cem";
          } else {
            $textoGrupo = $c[$centena];
          }
        }

        // Dezenas e unidades
        if ($dezena == 1) {
          // Números de 10 a 19
          if (!empty($textoGrupo)) $textoGrupo .= " e ";
          $textoGrupo .= $d10[$unidade];
        } else {
          // Dezenas
          if ($dezena > 1) {
            if (!empty($textoGrupo)) $textoGrupo .= " e ";
            $textoGrupo .= $d[$dezena];
          }

          // Unidades
          if ($unidade > 0) {
            if (!empty($textoGrupo)) $textoGrupo .= " e ";
            $textoGrupo .= $u[$unidade];
          }
        }

        // Adiciona escala (mil, milhão, etc.)
        if ($posicaoEscala > 0) {
          if ($grupo == 1) {
            $textoGrupo .= " " . $escala_singular[$posicaoEscala];
          } else {
            $textoGrupo .= " " . $escala[$posicaoEscala];
          }
        }

        $resultado[] = $textoGrupo;
      }
    }

    // Junta os grupos com vírgulas e "e"
    if (count($resultado) == 1) {
      return $resultado[0];
    } elseif (count($resultado) == 2) {
      return implode(" e ", $resultado);
    } else {
      $ultimo = array_pop($resultado);
      return implode(", ", $resultado) . " e " . $ultimo;
    }
  }
}
