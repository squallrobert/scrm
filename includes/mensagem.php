<?php
	if($resposta != OPERACAO_SUCESSO)
	{
		$iconeImg = "alerta.png";
	}
	else
	{
		$iconeImg = "sucesso.png";
	}

	if($resposta !="")
	{

		echo"<div id=\"mensagem_operacao\"><table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"5\" cellpadding=\"5\">
			  ";
			  	echo"<tr><td class=\"resposta\" align=\"right\" width=\"40%\"><img src=\"assets/images/$iconeImg \" /></td>";
				echo"<td class=\"resposta\" height=\"22\" width=\"60%\">$resposta</td></tr>";

		echo"</table></div><br />";

	}

	if(@count($respostaArray) >0)
	{
		echo"<table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"5\" cellpadding=\"5\">
			  ";
		foreach($respostaArray as $mensagem)
		{
				$pos = strpos($mensagem,ROTULO_SUCESSO);
				if($pos === false)
				{
					$iconeImg = "alerta.png";
				}
				else
				{
					$iconeImg = "sucesso.png";
				}

				echo"<tr><td class=\"resposta\" height=\"22\" width=\"40%\" align=\"right\" ><img src=\"assets/images/$iconeImg\" /></td>";
				echo"<td class=\"resposta\" height=\"22\" width=\"60%\">$mensagem</td></tr>";

		}
		echo"
			</table><br />";
	}

?>