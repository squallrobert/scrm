<?php
class GerarEmails
{
    static public function EmailWelcome($param)
    {
        $html = '<div id="#kt_app_body_content" style="background-color:#D5D9E2; font-family:Arial,Helvetica,sans-serif; line-height: 1.5; min-height: 100%; font-weight: normal; font-size: 15px; color: #2F3044; margin:0; padding:0; width:100%;">
						<div style="background-color:#ffffff; padding: 45px 0 34px 0; border-radius: 24px; margin:40px auto; max-width: 600px;">';
        $html .= "<table style=\"border-collapse:collapse\" width=\"100%\" height=\"auto\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";
        $html .= "	<tbody>\n";
        $html .= "		<tr>\n";
        $html .= "			<td style=\" padding-bottom: 10px\" valign=\"center\" >\n";
        $html .= "				<!--begin:Email content-->\n";
        $html .= "				<div style=\" margin:0 15px 34px 15px\">\n";
        $html .= "					<!--begin:Logo-->\n";
        $html .= "					<div style=\"margin-bottom: 10px; text-align: center;\">\n";
        $html .= "						<a href=\"".BASE_URL."\" rel=\"noopener\" target=\"_blank\">\n";
        $html .= "							<img alt=\"Logo\" src=\"".BASE_URL."/assets/logo_full.png\" style=\"height: 35px\">\n";
        $html .= "						</a>\n";
        $html .= "					</div>\n";
        $html .= "					<!--end:Logo-->\n";
        $html .= "					<!--begin:Text-->\n";
        $html .= '
        									<div style="font-size: 14px; font-weight: 500; margin-bottom: 27px; font-family:Arial,Helvetica,sans-serif;">
												<p style="margin-bottom:9px; color:#181C32; font-size: 22px; font-weight:700">Olá '.$param['nome'].', obrigado por se cadastrar!</p>
												  <p>Seja muito bem-vindo(a) à plataforma Sheep  House, a solução mais completa para agendamento de captação de imagens para  imobiliárias. Nós estamos animados em tê-lo(a) conosco e queremos ajudá-lo(a) a  ter ainda mais sucesso na venda de imóveis.</p>
                                                    <p>Com a Sheep House, você tem acesso a uma série  de recursos que vão facilitar o seu dia a dia, tais como agendamento online de  fotos imobiliárias, acompanhamento dos agendamentos, notificações de status e  muito mais. Tudo isso, com a garantia de que as fotos serão entregues com  qualidade e rapidez.</p>
                                                    <p>Além disso, nossa equipe está à disposição  para ajudá-lo(a) em caso de dúvidas ou problemas. Nosso objetivo é oferecer uma  experiência incrível para você e seus clientes.</p>
                                                    <p>Não perca mais tempo com agendamentos manuais,  experimente a Sheep House e veja como podemos ajudá-lo(a) a ter ainda mais  sucesso.</p>
                                                    <p><strong>Link de acesso ao portal: <a href="https://app.sheephouse.com.br">https://app.sheephouse.com.br</a></strong><br>
                                                      <strong>Email de acesso: '.$param['email'].'</strong> <br>
                                                      <strong>Senha de acesso: '.$param['senha'].'</strong> <br>
                                                      Em Breve estaremos disponibilizando também  nosso aplicativo nas lojas Google e IOS<br>Seja bem-vindo(a) à plataforma Sheep House!</p>
                                                    <p style="margin-bottom:2px; color:#7E8299">&nbsp;</p>
                                                  </div>';
        $html .= "					<!--end:Text-->\n";
        $html .= "					<!--begin:Action-->\n";
        $html .= "					<a href=\"".BASE_URL."\" target=\"_blank\" style=\" text-align: center; background-color:#50cd89; border-radius:6px;display:inline-block; padding:11px 19px; color: #FFFFFF; font-size: 14px; font-weight:500;\">Acessar</a>\n";
        $html .= "					<!--begin:Action-->\n";
        $html .= "				</div>\n";
        $html .= "				<!--end:Email content-->\n";
        $html .= "			</td>\n";
        $html .= "		</tr>\n";
        $html .= "		<tr style=\"display: flex; justify-content: center; margin:0 60px 35px 60px\">\n";
        $html .= "			<td style=\"padding-bottom: 10px;\" valign=\"start\" align=\"start\">\n";
        $html .= "				<p style=\"color:#181C32; font-size: 18px; font-weight: 600; margin-bottom:13px\">O que fazer agora?</p>\n";
        $html .= "				<!--begin::Wrapper-->\n";
        $html .= "				<div style=\"background: #F9F9F9; border-radius: 12px; padding:35px 30px\">\n";
        $html .= "					<!--begin::Item-->\n";
        $html .= "					<div style=\"display:flex\">\n";
        $html .= "						<!--begin::Media-->\n";
        $html .= "						<div style=\"display: flex; justify-content: center; align-items: center; width:40px; height:40px; margin-right:13px\">\n";
        $html .= "							<img alt=\"Logo\" src=\"".BASE_URL."/assets/media/email/icon-polygon.svg\">\n";
        $html .= "							<span style=\"position: absolute; color:#50CD89; font-size: 16px; font-weight: 600;\">1</span>\n";
        $html .= "						</div>\n";
        $html .= "						<!--end::Media-->\n";
        $html .= "						<!--begin::Block-->\n";
        $html .= "						<div>\n";
        $html .= "							<!--begin::Content-->\n";
        $html .= "							<div>\n";
        $html .= "								<!--begin::Title-->\n";
        $html .= "								<a href=\"#\" style=\"color:#181C32; font-size: 14px; font-weight: 600;font-family:Arial,Helvetica,sans-serif\">Completar cadastro</a>\n";
        $html .= "								<!--end::Title-->\n";
        $html .= "								<!--begin::Desc-->\n";
        $html .= "								<p style=\"color:#5E6278; font-size: 13px; font-weight: 500; padding-top:3px; margin:0;font-family:Arial,Helvetica,sans-serif\">acesse a plataforma e atualize os dados para  utilizar nossos serviços</p>\n";
        $html .= "								<!--end::Desc-->\n";
        $html .= "							</div>\n";
        $html .= "							<!--end::Content-->\n";
        $html .= "							<!--begin::Separator-->\n";
        $html .= "							<div class=\"separator separator-dashed\" style=\"margin:17px 0 15px 0\"></div>\n";
        $html .= "							<!--end::Separator-->\n";
        $html .= "						</div>\n";
        $html .= "						<!--end::Block-->\n";
        $html .= "					</div>\n";
        $html .= "					<!--end::Item-->\n";
        $html .= "					<!--begin::Item-->\n";
        $html .= "					<div style=\"display:flex\">\n";
        $html .= "						<!--begin::Media-->\n";
        $html .= "						<div style=\"display: flex; justify-content: center; align-items: center; width:40px; height:40px; margin-right:13px\">\n";
        $html .= "							<img alt=\"Logo\" src=\"".BASE_URL."/assets/media/email/icon-polygon.svg\">\n";
        $html .= "							<span style=\"position: absolute; color:#50CD89; font-size: 16px; font-weight: 600;\">2</span>\n";
        $html .= "						</div>\n";
        $html .= "						<!--end::Media-->\n";
        $html .= "						<!--begin::Block-->\n";
        $html .= "						<div>\n";
        $html .= "							<!--begin::Content-->\n";
        $html .= "							<div>\n";
        $html .= "								<!--begin::Title-->\n";
        $html .= "								<a href=\"#\" style=\"color:#181C32; font-size: 14px; font-weight: 600;font-family:Arial,Helvetica,sans-serif\">Serviços</a>\n";
        $html .= "								<!--end::Title-->\n";
        $html .= "								<!--begin::Desc-->\n";
        $html .= "								<p style=\"color:#5E6278; font-size: 13px; font-weight: 500; padding-top:3px; margin:0;font-family:Arial,Helvetica,sans-serif\">Verifique os nossos serviços e valores de cada região que atende sua demanda.</p>\n";
        $html .= "								<!--end::Desc-->\n";
        $html .= "							</div>\n";
        $html .= "							<!--end::Content-->\n";
        $html .= "							<!--begin::Separator-->\n";
        $html .= "							<div class=\"separator separator-dashed\" style=\"margin:17px 0 15px 0\"></div>\n";
        $html .= "							<!--end::Separator-->\n";
        $html .= "						</div>\n";
        $html .= "						<!--end::Block-->\n";
        $html .= "					</div>\n";
        $html .= "					<!--end::Item-->\n";
        $html .= "					<!--begin::Item-->\n";
        $html .= "					<div style=\"display:flex\">\n";
        $html .= "						<!--begin::Media-->\n";
        $html .= "						<div style=\"display: flex; justify-content: center; align-items: center; width:40px; height:40px; margin-right:13px\">\n";
        $html .= "							<img alt=\"Logo\" src=\"".BASE_URL."/assets/media/email/icon-polygon.svg\">\n";
        $html .= "							<span style=\"position: absolute; color:#50CD89; font-size: 16px; font-weight: 600;\">3</span>\n";
        $html .= "						</div>\n";
        $html .= "						<!--end::Media-->\n";
        $html .= "						<!--begin::Block-->\n";
        $html .= "						<div>\n";
        $html .= "							<!--begin::Content-->\n";
        $html .= "							<div>\n";
        $html .= "								<!--begin::Title-->\n";
        $html .= "								<a href=\"#\" style=\"color:#181C32; font-size: 14px; font-weight: 600;font-family:Arial,Helvetica,sans-serif\">Planejamento dos trabalhos</a>\n";
        $html .= "								<!--end::Title-->\n";
        $html .= "								<!--begin::Desc-->\n";
        $html .= "								<p style=\"color:#5E6278; font-size: 13px; font-weight: 500; padding-top:3px; margin:0;font-family:Arial,Helvetica,sans-serif\">Mantenha organizado suas demandas de  media imobiliaria, consulte os serviços e agendas disponíveis de nossa equipe.</p>\n";
        $html .= "								<!--end::Desc-->\n";
        $html .= "							</div>\n";
        $html .= "							<!--end::Content-->\n";
        $html .= "						</div>\n";
        $html .= "						<!--end::Block-->\n";
        $html .= "					</div>\n";
        $html .= "					<!--end::Item-->\n";
        $html .= "				</div>\n";
        $html .= "				<!--end::Wrapper-->\n";
        $html .= "			</td>\n";
        $html .= "		</tr>\n";
        $html .= "		<tr>\n";
        $html .= "			<td style=\"font-size: 13px; padding:0 15px; text-align:center; font-weight: 500; color: #A1A5B7;font-family:Arial,Helvetica,sans-serif\" valign=\"center\" align=\"center\">\n";
        $html .= "				<p>© SheepHouse.\n";
        $html .= "				</p>\n";
        $html .= "			</td>\n";
        $html .= "		</tr>\n";
        $html .= "	</tbody>\n";
        $html .= "</table>\n";
        $html .= "</div>\n";
        $html .= "</div>\n";

        return $html;

    }
    static public function GerarConfirmacaoReserva($param)
    {
        $html ="<!doctype html>\n";
        $html .="<html>\n";
        $html .="<head>\n";
        $html .="<meta charset=\"utf-8\">\n";
        $html .="<title>SheepHouse</title>\n";
        $html .="</head>\n";
        $html .="<body>\n";
        $html .="<div class=\"scroll-y flex-column-fluid px-10 py-10\" data-kt-scroll=\"true\" data-kt-scroll-activate=\"true\" data-kt-scroll-height=\"auto\" data-kt-scroll-dependencies=\"#kt_app_header_nav\" data-kt-scroll-offset=\"5px\" data-kt-scroll-save-state=\"true\" style=\"background-color:#D5D9E2; --kt-scrollbar-color: #d9d0cc; --kt-scrollbar-hover-color: #d9d0cc\">\n";
        $html .="	<style>html,body { padding:0; margin:0; font-family: Inter, Helvetica, \"sans-serif\"; } a:hover { color: #009ef7; }</style>\n";
        $html .="	<div id=\"#kt_app_body_content\" style=\"background-color:#D5D9E2; font-family:Arial,Helvetica,sans-serif; line-height: 1.5; min-height: 100%; font-weight: normal; font-size: 15px; color: #2F3044; margin:0; padding:0; width:100%;\">\n";
        $html .="		<div style=\"background-color:#ffffff; padding: 45px 0 34px 0; border-radius: 24px; margin:40px auto; max-width: 600px;\">\n";
        $html .="			<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"auto\" style=\"border-collapse:collapse\">\n";
        $html .="				<tbody>\n";
        $html .="					<tr>\n";
        $html .="						<td  style=\" padding-bottom: 10px\">\n";
        $html .="							<div style=\"margin:0 60px 34px 60px;\">\n";
        $html .="								<div style=\"margin-bottom: 10px; text-align:center; \">\n";
        $html .="									<a href=\"".URL_SITE."\" rel=\"noopener\" target=\"_blank\">\n";
        $html .="										<img alt=\"Logo\" src=\"".URL_SITE."assets/logo_full.png\" style=\"height: 35px\" />\n";
        $html .="									</a>\n";
        $html .="								</div>\n";
            $html .="								<div style=\"font-size: 14px; font-weight: 500; margin-bottom: 42px; font-family:Arial,Helvetica,sans-serif\">\n";
            $html .="									<p style=\"margin-bottom:9px; color:#181C32; font-size:17px; font-weight:700\">Prezado(a), ".$param['nome']."</p>\n";
            $html .="									<p style=\"margin-bottom:2px; color:#7E8299\">
									                      Obrigado por utilizar a plataforma de agendamento de fotos imobiliárias Sheep House! Este e-mail é para confirmar que recebemos sua solicitação de captação de imagens. 
									                      </p>\n";

            $html .= '
            <table width="100%" border="0" cellpadding="5" cellspacing="0">
												  <tbody>
													<tr>
													  <td width="36%">Endereço do imóvel:</td>
													  <td width="64%">'.$param['endereco'].'</td>
													</tr>
													<tr>
													  <td>Obs:</td>
													  <td>'.$param['obs'].'</td>
													</tr>
													<tr>
													  <td>Data:</td>
													  <td>'.$param['data'].'</td>
													</tr>
													<tr>
													  <td>Corretor:</td>
													  <td>'.$param['nome_corretor'].'</td>
												    </tr>
													<tr>
													  <td>Fornecedor:</td>
													  <td>'.$param['nome_fotografo'].'</td>
												    </tr>
												    tr>
													  <td>E-mail Fornecedor:</td>
													  <td>'.$param['email_fotografo'].'</td>
												    </tr>
													<tr>
													  <td>Rg do fornecedor:</td>
													  <td>'.$param['rg'].'</td>
												    </tr>
													<tr>
													  <td>&nbsp;</td>
													  <td>&nbsp;</td>
												    </tr>
													<tr>
													  <td colspan="2" align="center">&nbsp;</td>
												    </tr>
												  </tbody>
												</table>
            ';
            if($param['reagendar'] == 1)
            {
                $html .="									<p style=\"margin-bottom:2px; color:#7E8299\">
									                       Seu reagendamento é feito sem custos adicionais desde que esteja em conformidade com nossa política de cancelamentos. LINK POLITICA DE CANCELAMENTOS<br>
									                       Gostaríamos de lembrá-lo(a) de que estamos sempre disponíveis para atendê-lo(a) em qualquer dúvida ou necessidade. Se tiver qualquer pergunta ou precisar de mais informações, não hesite em entrar em contato conosco.
									                    
								                        </p>\n";
            }
            else
            {
                $html .="									<p style=\"margin-bottom:2px; color:#7E8299\">
									                       Gostaríamos de lembrá-lo(a) de que estamos sempre disponíveis para atendê-lo(a) em qualquer dúvida ou necessidade. Se tiver qualquer pergunta ou precisar de mais informações, não hesite em entrar em contato conosco.
								                        </p>\n";
            }


            $html .="									<p style=\"margin-bottom:2px; color:#7E8299\">
									                     Agradecemos pela confiança depositada em nossa plataforma e estamos ansiosos para trabalhar com você. Esperamos que a captação de imagens seja bem-sucedida e ajude a impulsionar seus negócios imobiliários.
									                    </p>\n";
            $html .="								</div>\n";


        $html .="								<div style=\"margin-bottom: 15px\">\n";
        $html .="									<h3 style=\"text-align:left; color:#181C32; font-size: 18px; font-weight:600; margin-bottom: 22px\">Serviços</h3>\n";
        $html .="									<div style=\"padding-bottom:9px\">\n";
        if(is_array($param['itens']))
        {
            $html .=' <table width="100%" border="0" cellpadding="5" cellspacing="0">
                          <tbody>
                            <tr>
                              <td width="71%" bgcolor="#f5f5f5"><strong>Serviço</strong></td>
                              <td width="29%" align="center" valign="baseline" bgcolor="#f5f5f5"><strong>Valor</strong></td>
                            </tr>';
            foreach ($param['itens'] as $iten) {
                $html .=' <tr>
                              <td>'.$iten['nome'].'</td>
                              <td>R$ '.number_format($iten['valor'],2,',','.').'</td>
                            </tr>';

                $total += $iten['valor'];
            }
            $html .=' <tr>
                              <td bgcolor="#f5f5f5"><strong>TOTAL</strong></td>
                              <td bgcolor="#f5f5f5"><strong>R$ '.number_format($total,2,',','.').'</strong></td>
                            </tr>';
                $html .=' </tbody>
                            </table>';
        }

        if(is_array($param['itens_fornecedor']))
        {
            $html .=' <table width="100%" border="0" cellpadding="5" cellspacing="0">
                          <tbody>
                            <tr>
                              <td width="71%" bgcolor="#f5f5f5"><strong>Serviço</strong></td>
                              <td width="29%" align="center" valign="baseline" bgcolor="#f5f5f5"><strong>Valor</strong></td>
                            </tr>';
            foreach ($param['itens_fornecedor'] as $iten) {
                $html .=' <tr>
                              <td>'.$iten['nome'].'</td>
                              <td>R$ '.number_format($iten['valor_fornecedor'],2,',','.').'</td>
                            </tr>';
                $total += $iten['valor_fornecedor'];
            }
            $html .=' <tr>
                              <td bgcolor="#f5f5f5"><strong>TOTAL</strong></td>
                              <td bgcolor="#f5f5f5"><strong>R$ '.number_format($total,2,',','.').'</strong></td>
                            </tr>';
            $html .=' </tbody>
                            </table>';
        }
        $html .="									</div>\n";
        $html .="								</div>\n";
        $html .="								<div style='text-align: center'><a href=\"".URL_SITE."\" target=\"_blank\" style=\"background-color:#50cd89; border-radius:6px;display:inline-block; padding:15px 19px; color: #FFFFFF; font-size: 14px; font-weight:500;\">Ir para SheepHouse</a></div>\n";
        $html .="							</div>\n";
        $html .="						</td>\n";
        $html .="					</tr>\n";
        $html .="					<tr>\n";
        $html .="						<td align=\"center\" valign=\"center\" style=\"font-size: 13px; padding:0 15px; text-align:center; font-weight: 500; color: #A1A5B7;font-family:Arial,Helvetica,sans-serif\">\n";
        $html .="							<p>&copy; Todos os direitos reservados SheepHouse.\n";
        $html .="							</p>\n";
        $html .="						</td>\n";
        $html .="					</tr>\n";
        $html .="				</tbody>\n";
        $html .="			</table>\n";
        $html .="		</div>\n";
        $html .="	</div>\n";
        $html .="</div>\n";
        $html .="</body>\n";
        $html .="</html>\n";


        return $html;
    }
    static public function EmailCancelamento($param)
    {
        $html ="<!doctype html>\n";
        $html .="<html>\n";
        $html .="<head>\n";
        $html .="<meta charset=\"utf-8\">\n";
        $html .="<title>SheepHouse</title>\n";
        $html .="</head>\n";
        $html .="<body>\n";
        $html .="<table width=\"100%\" border=\"0\" cellpadding=\"15\" cellspacing=\"0\"  style=\"font-family: Inter, Helvetica, 'sans-serif'\">\n";
        $html .="  <tbody>\n";
        $html .="    <tr>\n";
        $html .="      <td align=\"center\"><img src=\"".URL_SITE."assets/logo_full.png\" width=\"350\" height=\"137\" alt=\"\"/></td>\n";
        $html .="    </tr>\n";
        $html .="    <tr>\n";
        $html .="      <td>";
        $html .='												<table width="100%" border="0" cellpadding="5" cellspacing="0">
                  <tbody>
                    <tr>
                      <td width="100%"><p>Prezado(a), <strong>'.$param['nome_corretor'].'</strong></p>
                        <p>Obrigado por utilizar a plataforma de  agendamento de fotos imobiliárias Sheep House! Este e-mail é para confirmar que  recebemos sua solicitação de CANCELAMENTO da captação de imagens no imóvel da <strong>'.$param['endereco'].' </strong>que foi agendado para o dia <strong>'.$param['data'].'<br>
                          </strong><br>
                          Lamentamos que não tenha sido possível atender  às suas necessidades neste momento. Entendemos que imprevistos acontecem e  estamos aqui para ajudá-lo(a) em quaisquer outras necessidades futuras.<br>
                          Seu CANCELAMENTO  é feito sem custos adicionais desde que  esteja em conformidade com nossa política de cancelamentos. <strong><a href="#">LINK POLITICA DE  CANCELAMENTOS</a></strong></p>
                        <p>Caso tenha alguma dúvida ou precise de mais  informações, por favor, não hesite em entrar em contato conosco. Estamos sempre  disponíveis para atendê-lo(a) e ajudá-lo(a) em qualquer necessidade.</p>
                        <p>Agradecemos pela confiança depositada em nossa  plataforma e esperamos ter a oportunidade de trabalhar com você novamente no  futuro.</p>
                        <p>Atenciosamente,</p>
                      <p>Equipe Sheep House.</p></td>
                    </tr>
                  </tbody>
                </table>';

        $html .="      </td>\n";
        $html .="    </tr>\n";
        $html .="    <tr>\n";
        $html .="      <td><hr/></td>\n";
        $html .="    </tr>\n";
        $html .="    <tr>\n";
        $html .="      <td align=\"center\"><p><a href=\"https://app.sheephouse.com.br\">www.sheephouse.com.br</a> - Todos os direitos reservados</p></td>\n";
        $html .="    </tr>\n";
        $html .="  </tbody>\n";
        $html .="</table>\n";
        $html .="</body>\n";
        $html .="</html>\n";
        return $html;
    }

    static public function EntregaMaterial($param)
    {
        $html ="<!doctype html>\n";
        $html .="<html>\n";
        $html .="<head>\n";
        $html .="<meta charset=\"utf-8\">\n";
        $html .="<title>SheepHouse</title>\n";
        $html .="</head>\n";
        $html .="<body>\n";
        $html .="<table width=\"100%\" border=\"0\" cellpadding=\"15\" cellspacing=\"0\"  style=\"font-family: Inter, Helvetica, 'sans-serif'\">\n";
        $html .="  <tbody>\n";
        $html .="    <tr>\n";
        $html .="      <td align=\"center\"><img src=\"".URL_SITE."assets/logo_full.png\" width=\"350\" height=\"137\" alt=\"\"/></td>\n";
        $html .="    </tr>\n";
        $html .="    <tr>\n";
        $html .="      <td>";
        $html .= '
        <table width="100%" border="0" cellpadding="5" cellspacing="0">
          <tbody>
            <tr>
              <td width="100%"><p>Prezado(a) , <strong>'.$param['nome_corretor'].'</strong></p>
                <p>É com satisfação que informamos que a sessão  de fotos do seu imóvel localizado na <strong>'.$param['endereco'].' </strong>realizado no dia <strong>'.$param['data'].'</strong> pelo fotógrafo<strong> '.$param['nome_fotografo'].' </strong>foi concluída com sucesso. <br>
                  <br>
                  As fotos estão prontas e disponíveis para download, basta acessar o link abaixo<br>
                  <a href="'.$param['drop_imagens'].'"><strong>'.$param['drop_imagens'].'</strong></a><br>
                  <br>';
        if($param['link'])  $html .= '   As fotos de Área comum podem ser acessada no Link abaixo:<br>
                  <a href="'.$param['link'].'"><strong>'.$param['link'].'</strong></a><br>
                  <br><br>';
        if($param['obs'])  $html .= ' Observações:<br>
                  <p>'.$param['obs'].'</p>
                  <br>';
        $html .= ' Agradecemos pela confiança depositada em nossa  plataforma para a captação de imagens do seu imóvel. Nossa equipe trabalha com  dedicação e profissionalismo para atender às suas necessidades e ajudar a  impulsionar seus negócios imobiliários.</p>
                <p>Para acessar as fotos, basta fazer login na  sua conta na plataforma Sheep House e clicar no botão &quot;Download&quot; na  sessão correspondente ao imóvel que foi fotografado. Se tiver alguma  dificuldade ou precisar de ajuda, por favor, não hesite em entrar em contato  conosco.</p>
                <p>Esperamos que as fotos atendam às suas  expectativas e ajudem a destacar o seu imóvel no mercado imobiliário. Ficamos à  disposição para atendê-lo(a) em qualquer necessidade futura.</p>
                <p>Agradecemos novamente pelo agendamento na  Sheep House e estamos ansiosos para trabalhar com você novamente no futuro.</p>
                <p>Atenciosamente,</p>
              <p>Equipe Sheep House.</p></td>
            </tr>
          </tbody>
        </table>
        ';
        $html .="      </td>\n";
        $html .="    </tr>\n";
        $html .="    <tr>\n";
        $html .="      <td><hr/></td>\n";
        $html .="    </tr>\n";
        $html .="    <tr>\n";
        $html .="      <td align=\"center\"><p><a href=\"https://app.sheephouse.com.br\">www.sheephouse.com.br</a> - Todos os direitos reservados</p></td>\n";
        $html .="    </tr>\n";
        $html .="  </tbody>\n";
        $html .="</table>\n";
        $html .="</body>\n";
        $html .="</html>\n";
        return $html;
    }
    static public function FormularioVideos($param)
    {
        $html ="<!doctype html>\n";
        $html .="<html>\n";
        $html .="<head>\n";
        $html .="<meta charset=\"utf-8\">\n";
        $html .="<title>SheepHouse</title>\n";
        $html .="</head>\n";
        $html .="<body>\n";
        $html .="<table width=\"100%\" border=\"0\" cellpadding=\"15\" cellspacing=\"0\"  style=\"font-family: Inter, Helvetica, 'sans-serif'\">\n";
        $html .="  <tbody>\n";
        $html .="    <tr>\n";
        $html .="      <td align=\"center\"><img src=\"".URL_SITE."assets/logo_full.png\" width=\"350\" height=\"137\" alt=\"\"/></td>\n";
        $html .="    </tr>\n";
        $html .="    <tr>\n";
        $html .="      <td>";
        $html .='
        <table width="100%" border="0" cellpadding="5" cellspacing="0">
												  <tbody>
													<tr>
													  <td width="100%"><p>Prezado(a), '.$param['nome_corretor'].'<br>
                                                        Recebemos seu pedido para gravação  profissional de Vídeos no imóvel localizado na <strong>'.$param['endereco'].', </strong>que será  gravado no dia <strong>'.$param['data'].'</strong> pelo fornecedor <strong>'.$param['nome_fotografo'].'</strong><br>
                                                        Com o intuito de manter nossa agilidade na  prestação de serviços, pedimos que nos envie algumas informações  para que possamos dar inicio na edição de  vídeo logo que o material suba aos nossos servidores.<br>
                                                        Para que possamos atender às suas  expectativas, gostaríamos que nos fornecesse os seguintes detalhes sobre o  projeto:</p>
                                                                                                                <ul>
                                                                                                                  <li><strong>Qual é o objetivo do vídeo:</strong></li>
                                                                                                                  <li><strong>Quais são os principais pontos que devem ser destacados no vídeo:</strong></li>
                                                                                                                  <li><strong>Além disso, gostaríamos que nos fornecesse até 04 legendas que possam  ser utilizadas no vídeo.</strong></li>
                                                                                                                  <li><strong>Deseja inserir sua logomarca (anexar)   ?</strong></li>
                                                                                                                </ul>
                                                                                                                <p>Nos comprometemos a entregar o vídeo  finalizado em até 5 dias após a captação de imagens no imóvel.<br>
                                                                                                                  Agradecemos antecipadamente pela colaboração e  esperamos poder trabalhar com você para criar um vídeo de qualidade.<br>
                                                          <em>Se este email não for respondido até  DATA da sessao nosso departamento de edição entende que você  prefere que a nossa equipe cuide da edição sem legendas e informações  adicionais. </em><br>
                                                                                                                  Atenciosamente,<br>
                                                          <br>
                                                          <br>
                                                          Isaias Schneider<br>
                                                          Departamento de Produção de Vídeos<br>
                                                      Sheep House</p></td>
												    </tr>
												  </tbody>
												</table>

        ';

        $html .="      </td>\n";
        $html .="    </tr>\n";
        $html .="    <tr>\n";
        $html .="      <td><hr/></td>\n";
        $html .="    </tr>\n";
        $html .="    <tr>\n";
        $html .="      <td align=\"center\"><p><a href=\"https://app.sheephouse.com.br\">www.sheephouse.com.br</a> - Todos os direitos reservados</p></td>\n";
        $html .="    </tr>\n";
        $html .="  </tbody>\n";
        $html .="</table>\n";
        $html .="</body>\n";
        $html .="</html>\n";
        return $html;
    }
    static public function EntregaVideos($param)
    {
        $html ="<!doctype html>\n";
        $html .="<html>\n";
        $html .="<head>\n";
        $html .="<meta charset=\"utf-8\">\n";
        $html .="<title>SheepHouse</title>\n";
        $html .="</head>\n";
        $html .="<body>\n";
        $html .="<table width=\"100%\" border=\"0\" cellpadding=\"15\" cellspacing=\"0\"  style=\"font-family: Inter, Helvetica, 'sans-serif'\">\n";
        $html .="  <tbody>\n";
        $html .="    <tr>\n";
        $html .="      <td align=\"center\"><img src=\"".URL_SITE."assets/logo_full.png\" width=\"350\" height=\"137\" alt=\"\"/></td>\n";
        $html .="    </tr>\n";
        $html .="    <tr>\n";
        $html .="      <td>";
        $html .='
			<table width="100%" border="0" cellpadding="5" cellspacing="0">
												  <tbody>
													<tr>
													  <td width="100%">
                                                        <p>Prezado <strong>'.$param['nome_corretor'].'</strong>,</p>
                                                        <p>Gostaria de agradecer pela oportunidade de  trabalhar com você na produção do vídeo profissional da propriedade localizada  na [Endereço]. Temos o prazer de informar que o vídeo já está pronto e pode ser  acessado por meio do link abaixo:</p>
                                                        <p><strong><a href="#">'.$param['link'].'</a></strong></p>
                                                        <p>Esperamos que este vídeo ajude a destacar as  melhores características da propriedade e a atrair o interesse de potenciais  compradores<br>
                                                          Também gostaria de agradecer pela confiança em  nosso trabalho. Foi um prazer trabalhar com você e esperamos poder colaborar  novamente em futuros projetos.</p>
                                                        <p>Atenciosamente,</p>
                                                        <p>Isaias Schneider<br>
                                                          Departamento de Produção de Vídeos<br>
                                                      Sheep House</p></td>
												    </tr>
												  </tbody>
												</table>

        ';

        $html .="      </td>\n";
        $html .="    </tr>\n";
        $html .="    <tr>\n";
        $html .="      <td><hr/></td>\n";
        $html .="    </tr>\n";
        $html .="    <tr>\n";
        $html .="      <td align=\"center\"><p><a href=\"https://app.sheephouse.com.br\">www.sheephouse.com.br</a> - Todos os direitos reservados</p></td>\n";
        $html .="    </tr>\n";
        $html .="  </tbody>\n";
        $html .="</table>\n";
        $html .="</body>\n";
        $html .="</html>\n";
        return $html;
    }

    static public function EmailFatura($param)
    {
        $html ="<!doctype html>\n";
        $html .="<html>\n";
        $html .="<head>\n";
        $html .="<meta charset=\"utf-8\">\n";
        $html .="<title>SheepHouse</title>\n";
        $html .="</head>\n";
        $html .="<body>\n";
        $html .="<table width=\"100%\" border=\"0\" cellpadding=\"15\" cellspacing=\"0\"  style=\"font-family: Inter, Helvetica, 'sans-serif'\">\n";
        $html .="  <tbody>\n";
        $html .="    <tr>\n";
        $html .="      <td align=\"center\"><img src=\"".URL_SITE."assets/logo_full.png\" width=\"350\" height=\"137\" alt=\"\"/></td>\n";
        $html .="    </tr>\n";
        $html .="    <tr>\n";
        $html .="      <td>";
        if($param['credito']) {
            $html .= '
			<table width="100%" border="0" cellpadding="5" cellspacing="0">
												  <tbody>
													<tr>
													  <td width="100%">
                                                        <p>Olá <strong>' . $param['nome_cliente'] . '</strong>,</p>
                                                        <p>Você esta recebendo este email por que comprou pacote de créditos SheepHouse.</p>
                                                        <p>Abaixo o link de acesso a sua fatura. Qualquer dúvida por favor responda este e-mail ou use a plataforma SheepHouse para nos contactar.</p>
                                                        <p><strong><a href="' . $param['link'] . '">Acessar Fatura</a></strong></p><br>
                                                        <br>
                                                         <p>Obrigado por escolher a SheepHouse.</p>
                                                           
                                                        <p>Atenciosamente,</p>
                                                        <p>Diretoria Financeira</p></td>
												    </tr>
												  </tbody>
												</table>

        ';
        }else {

            $html .= '
			<table width="100%" border="0" cellpadding="5" cellspacing="0">
												  <tbody>
													<tr>
													  <td width="100%">
                                                        <p>Olá <strong>' . $param['nome_cliente'] . '</strong>,</p>
                                                        <p>Você esta recebendo este email com o detalhamento dos serviços prestados para este vencimento.</p>
                                                        <p>Abaixo o link de acesso a sua fatura e pagamento. Qualquer dúvida por favor responda este e-mail ou use a plataforma SheepHouse para nos contactar.</p>
                                                        <p><strong><a href="' . $param['link'] . '">Acessar Fatura</a></strong></p><br>
                                                        <p>Segue também o Link para pagamento:</p>
                                                        <p><strong><a href="' . $param['boleto'] . '">Link de Pagamento</a></strong></p><br>
                                                        <br>
                                                         <p>Obrigado por escolher a SheepHouse.</p>
                                                           
                                                        <p>Atenciosamente,</p>
                                                        <p>Diretoria Financeira</p></td>
												    </tr>
												  </tbody>
												</table>

        ';
        }

        $html .="      </td>\n";
        $html .="    </tr>\n";
        $html .="    <tr>\n";
        $html .="      <td><hr/></td>\n";
        $html .="    </tr>\n";
        $html .="    <tr>\n";
        $html .="      <td align=\"center\"><p><a href=\"https://app.sheephouse.com.br\">www.sheephouse.com.br</a> - Todos os direitos reservados</p></td>\n";
        $html .="    </tr>\n";
        $html .="  </tbody>\n";
        $html .="</table>\n";
        $html .="</body>\n";
        $html .="</html>\n";
        return $html;
    }
}
