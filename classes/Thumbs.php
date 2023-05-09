<?php
/**
 * @author squall
 * @copyright 2009
 */
class Thumbs
{
	//variaveis necessárias para gerar thumbs
	public $caminho;
	public $nome ;
	public $largura_max;
	public $altura_max;
	public $arquivo;
	public $ext;
	public $tipo ;
	public $ImagemGerada;
	public function Thumbs()
	{
		// definindo valores padrão de variaveis
		$this->tipo = 1;
		$this->nome = date('YmdHis');
		$this->altura_max = 80;
		$this->largura_max = 250;
	}
	//preparando imagem
	public function Prepare()
	{
		// verifica imagem se existe
		if(file_exists($this->arquivo))
		{
			// gerando nome da nova imagem
			$this->NomeImagem();
            if($this->arquivo != $this->ImagemGerada)
            {
                $img_tamanho = @getimagesize($this->ImagemGerada);

                if(!file_exists($this->ImagemGerada) || $img_tamanho[0] > $this->largura_max || $img_tamanho[1] > $this->altura_max)
    			{
    					// pegando extensão da imagem
    					$this->ext = substr($this->arquivo,-3);
    					// verificando se o caminho e existente/ se não cria diretório
    					$this->CriarDir();
    					// gerando imagem

    					$this->Gerar();

    					return $this->ImagemGerada;
    			}
    			else
    				return $this->ImagemGerada; // retorno da imagem existente
            }
            else
            {
    			// pegando tamanho da imagem
    			$img_tamanho = @getimagesize($this->arquivo);
    			if(!file_exists($this->ImagemGerada) || $img_tamanho[0] > $this->largura_max || $img_tamanho[1] > $this->altura_max)
    			{
    					// pegando extensão da imagem
    					$this->ext = substr($this->arquivo,-3);
    					// verificando se o caminho e existente/ se não cria diretório
    					$this->CriarDir();
    					// gerando imagem

    					$this->Gerar();

    					return $this->ImagemGerada;
    			}
    			else
    				return $this->ImagemGerada; // retorno da imagem existente
            }
		}
		else
			return false; //retorna false se o arquivo nao for encontrado
	}
	// Gerador de Thumbs
	public function Gerar()
	{
		// se extesão for jpg
		if(strtolower($this->ext == "jpg") || $this->ext == "JPG")
		{
			$imagem_orig = @imagecreatefromjpeg($this->arquivo);
		}
		elseif(strtolower($this->ext=="png") || $this->ext == "PNG")
		{
			$imagem_orig = @ImageCreateFromPNG($this->arquivo);
		}
		elseif(strtolower($this->ext=="gif")  || $this->ext == "GIF")
		{
			$imagem_orig = @ImageCreateFromGIF($this->arquivo);
		}
		// pegando tamanhos da imagem
		$largura_original = @ImagesX($imagem_orig);
		$altura_original = @ImagesY($imagem_orig);

		$altura = $altura_original;
		$largura = $largura_original;

        if( $largura > $this->largura_max)
        {
            $largura = $this->largura_max;
            $altura = $altura * ($largura / $largura_original);

        }
        if($altura > $this->altura_max)
        {
            $largura = $largura * ($this->altura_max/$altura);
            $altura  = $this->altura_max;
        }

		//gerando imagem final
		$imagem_final=@imagecreatetruecolor($largura,$altura);
        @imagealphablending($imagem_final, false);
        @imagesavealpha($imagem_final,true);
        $transparent = @imagecolorallocatealpha($imagem_final, 255, 255, 255, 127);
        @imagefilledrectangle($imagem_final, 0, 0, $largura, $altura, $transparent);
        @imagecopyresampled($imagem_final, $imagem_orig, 0, 0, 0, 0, $largura, $altura, $largura_original, $altura_original);
		$imagem_ext=@substr($this->arquivo,-3);
		// gerando arquivo
		if(strtolower($this->ext=="jpg")  || $this->ext == "JPG")
			@imagejpeg($imagem_final,$this->ImagemGerada,100);
		if(strtolower($this->ext == "png")  || $this->ext == "PNG")
			@imagepng($imagem_final,$this->ImagemGerada);
		if(strtolower($this->ext == "gif")  || $this->ext == "GIF")
			@imagegif($imagem_final,$this->ImagemGerada);
		// destruindo informações desnecessárias
		@imageDestroy($imagem_orig);
		@imageDestroy($imagem_final);
		//retornando caminho da nova imagem
	}
	public function CriarDir()
	{
		if(!is_dir($this->caminho))
		{
			mkdir($this->caminho,0777);
		}
	}
	public function NomeImagem()
	{
		//pegando nome original da imagem
		$imagemLimpando = @explode("/",$this->arquivo);
		$imagemNome = $imagemLimpando[(count($imagemLimpando) - 1)];
		// se o tipo for  igual a 1 ele usa o nome original da imagem
		// se não ele usa o timestamp para gerar um nome unico
		if($this->tipo == 1) $imagem_gerada=$this->caminho . $imagemNome;
		else $imagem_gerada = $this->caminho . $this->nome . "." .  $this->ext;
		$this->ImagemGerada = $imagem_gerada;

       // echo "ESTA E A IMAGEM GERARAD" . $this->ImagemGerada . "<BR>";
	}
}
?>