<?php
/**
 * Classe para gerar imagem do código de barras.
 */
class CodigoBarras {
	/**
	 * Monta códigos de barras
	 * @param string $codigoBarras - Código de barras.
	 * <code>
	 * GeraImagemCodigoBarras('34196166700000123451101234567880057123457000');
	 * </code>
	 */
	public function GeraImagemCodigoBarras($codigoBarras) {
		$lw = 1;
		$hi = 50;
		
		$tabcodbarra [0] = "00110";
		$tabcodbarra [1] = "10001";
		$tabcodbarra [2] = "01001";
		$tabcodbarra [3] = "11000";
		$tabcodbarra [4] = "00101";
		$tabcodbarra [5] = "10100";
		$tabcodbarra [6] = "01100";
		$tabcodbarra [7] = "00011";
		$tabcodbarra [8] = "10010";
		$tabcodbarra [9] = "01010";
		
		for($f1 = 9; $f1 >= 0; $f1 --) {
			for($f2 = 9; $f2 >= 0; $f2 --) {
				$f = ($f1 * 10) + $f2;
				$texto = "";
				for($i = 1; $i < 6; $i ++) {
					$texto .= substr ( $tabcodbarra [$f1], ($i - 1), 1 ) . substr ( $tabcodbarra [$f2], ($i - 1), 1 );
				}
				$tabcodbarra [$f] = $texto;
			}
		}
		
		/* Gerando a Imagen do Código de Barras */

		//      $img = ImageCreate($lw*95+1000,$hi+30);
		$img = ImageCreate ( $lw * 95 + 330, $hi + 30 );
		$preto = ImageColorAllocate ( $img, 0, 0, 0 );
		$branco = ImageColorAllocate ( $img, 255, 255, 255 );
		
		/* Cria o retángulo principal onde a imagem será aplicada. */
		
		ImageFilledRectangle ( $img, 0, 0, $lw * 95 + 1000, $hi + 30, $branco );
		
		# Abertura do código de barras.
		ImageFilledRectangle ( $img, 1, 5, 1, 65, $preto );
		ImageFilledRectangle ( $img, 2, 5, 2, 65, $branco );
		ImageFilledRectangle ( $img, 3, 5, 3, 65, $preto );
		ImageFilledRectangle ( $img, 4, 5, 4, 65, $branco );
		
		# Aqui começa a varredura no valor enviado para confecção do código de barras.	

		$fino = 1;
		$largo = 3; // Para o windows funciona mas o tamanho ideal seria 3
		$pos = 5;
		$texto = $codigoBarras;
		if ((strlen ( $texto ) % 2) != 0) {
			$texto = "0" . $texto;
		}
		
		// Draw dos dados
		while ( strlen ( $texto ) > 0 ) {
			$i = round ( $this->Esquerda ( $texto, 2 ) );
			$texto = $this->Direita ( $texto, strlen ( $texto ) - 2 );
			
			$f = $tabcodbarra [$i];
			
			for($i = 1; $i < 11; $i += 2) {
				if (substr ( $f, ($i - 1), 1 ) == "0") {
					$f1 = $fino;
				} else {
					$f1 = $largo;
				}
				
				/* Imprimindo uma barra preta */
				ImageFilledRectangle ( $img, $pos, 5, $pos - 1 + $f1, 65, $preto );
				$pos = $pos + $f1;
				/* Fim da impressão da barra preta */
				
				if (substr ( $f, $i, 1 ) == "0") {
					$f2 = $fino;
				} else {
					$f2 = $largo;
				}
				
				/* Imprimindo uma barra branca */
				ImageFilledRectangle ( $img, $pos, 5, $pos - 1 + $f2, 65, $branco );
				$pos = $pos + $f2;
				/* Fim da impressão da barra branca */
			
			}
		}
		
		# Fechamento do código de barras.	
		ImageFilledRectangle ( $img, $pos, 5, $pos - 1 + $largo, 65, $preto );
		$pos = $pos + $largo;
		
		ImageFilledRectangle ( $img, $pos, 5, $pos - 1 + $fino, 65, $branco );
		$pos = $pos + $fino;
		
		ImageFilledRectangle ( $img, $pos, 5, $pos - 1 + $fino, 65, $preto );
		$pos = $pos + $fino;
		
		//imprimi imagem na tela
		header ( "Content-Type: image/png" );
		ImagePNG ( $img );
	}
	
	public function Esquerda($entra, $comp) {
		return substr ( $entra, 0, $comp );
	}
	
	public function Direita($entra, $comp) {
		return substr ( $entra, strlen ( $entra ) - $comp, $comp );
	}
}

?>