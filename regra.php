<?php
session_start();
include_once("configuracao.php");
include_once("paypal.class.php");


if($_POST) //Recebe a Lista de Produtos
{
	//Vou Tratar variaveis para os itens
	$nomeitem = $_POST["nomeitem"]; //Nome do Item
	$itempreco = $_POST["itempreco"]; //Preço do Item
	$numeroitem = $_POST["numeroitem"]; //Numero do Item
	$itemQtd = $_POST["itemQtd"]; // Quantidade do Item
	$PrecoTotal = ($itempreco*$itemQtd); // Total do produtos
	$paytype	= $_POST["paytype"]; // Tipo de Pagamento
	

	//Enviar Dados
	$padata = 	'&CURRENCYCODE='.urlencode($PayPalCurrencyCode).
				'&PAYMENTACTION=Sale'.
				'&ALLOWNOTE=1'.
				'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
				'&PAYMENTREQUEST_0_AMT='.urlencode($PrecoTotal).
				'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($PrecoTotal). 
				'&L_PAYMENTREQUEST_0_QTY0='. urlencode($itemQtd).
				'&L_PAYMENTREQUEST_0_AMT0='.urlencode($itempreco).
				'&L_PAYMENTREQUEST_0_NAME0='.urlencode($nomeitem).
				'&L_PAYMENTREQUEST_0_NUMBER0='.urlencode($numeroitem).
				'&AMT='.urlencode($PrecoTotal).				
				'&RETURNURL='.urlencode($PayPalReturnURL ).
				'&CANCELURL='.urlencode($PayPalCancelURL);	
		
		//Executar "SetExpressCheckOut" 
		$paypal= new MyPayPal();
		$httpRespostaPaypal = $paypal->FuncEnvio('SetExpressCheckout', $padata, $user, $pswd, $signature, $Servidor,$paytype);
		
		//IF de acordo com a msg do Paypal
		if("SUCCESS" == strtoupper($httpRespostaPaypal["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpRespostaPaypal["ACK"]))
		{
					
				// Armazena na seção para recuperar depois com retorno da pagina do paypal 
				$_SESSION['itempreco'] =  $itempreco;
				$_SESSION['totalamount'] = $PrecoTotal;
				$_SESSION['nomeitem'] =  $nomeitem;
				$_SESSION['itemNo'] =  $numeroitem;
				$_SESSION['itemQtd'] =  $itemQtd;
				$_SESSION['paytype'] = $paytype;
				
				if($Servidor=='sandbox')
				{
					$Servidor 	=	'.sandbox';
				}
				else
				{
					$Servidor 	=	'';
				}
				//Redireciona o usuario para pagina do paypal com o token
			 	$paypalurl ='https://www'.$Servidor.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpRespostaPaypal["TOKEN"].'';
				header('Location: '.$paypalurl);
			 
		}else{
			//Exibe Erro
			echo '<div style="color:red"><b>Error : </b>'.urldecode($httpRespostaPaypal["L_LONGMESSAGE0"]).'</div>';
			echo '<pre>';
			print_r($httpRespostaPaypal);
			echo '</pre>';
		}

}

//Paypal redireciona devolta usando o ReturnURL, Tem que receber o TOKEN e ID
if(isset($_GET["token"]) && isset($_GET["PayerID"]))
{


	$token = $_GET["token"];
	$playerid = $_GET["PayerID"];
	
	//Recupera as variaveis da seção
	$itempreco 		= $_SESSION['itempreco'];
	$PrecoTotal = $_SESSION['totalamount'];
	$nomeitem 		= $_SESSION['nomeitem'];
	$numeroitem 	= $_SESSION['itemNo'];
	$itemQtd 		= $_SESSION['itemQtd'];
	$paytype        = $_SESSION['paytype']; 

	
	$padata = 	'&TOKEN='.urlencode($token).
						'&PAYERID='.urlencode($playerid).
						'&PAYMENTACTION='.urlencode("SALE").
						'&AMT='.urlencode($PrecoTotal).
						'&CURRENCYCODE='.urlencode($PayPalCurrencyCode);
	
	

	//Executa o "DoExpressCheckoutPayment" e o "GetExpressCheckoutDetails"
	$paypal= new MyPayPal();

		$httpRespostaPaypal = $paypal->FuncEnvio('GetExpressCheckoutDetails', $padata, $user, $pswd, $signature, $Servidor,$paytype);
		
		
		if("SUCCESS" == strtoupper($httpRespostaPaypal["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpRespostaPaypal["ACK"])) 
				{
				$cpf = $httpRespostaPaypal["TAXID"];
				$httpRespostaPaypal = $paypal->FuncEnvio('DoExpressCheckoutPayment', $padata, $user, $pswd, $signature, $Servidor,$paytype);
							//As notificações sempre serão via HTTP POST, então verificamos o método
			//utilizado na requisição, antes de fazer qualquer coisa.
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				//Antes de trabalhar com a notificação, precisamos verificar se ela
				//é válida e, se não for, descartar.
				$httpRespostaVerifica = $paypal->isIPNValid($_POST);
				if (!$httpRespostaVerifica($_POST)) {
					return;
				}
			 
				//Se chegamos até aqui, significa que estamos lidando com uma
				//notificação IPN válida. Agora precisamos verificar se somos o
				//destinatário dessa notificação, verificando o campo receiver_email.
				if ($_POST['receiver_email'] == $receiver_email) {
					//Está tudo correto, somos o destinatário da notificação, vamos
					//gravar um log dessa notificação.

				    $fp = fopen("bloco1.txt", "a");
 
					// Escreve "exemplo de escrita" no bloco1.txt
					$escreve = fwrite($fp, "retorno ok");
					 
					// Fecha o arquivo
					fclose($fp);
			 
					if (logIPN($pdo, $_POST)) {
						//Log gravado, podemos seguir com as regras de negócio para
						//essa notificação.
					}
				}
			}
				

		}else{
				echo '<div style="color:red"><b>Error : </b>'.urldecode($httpRespostaPaypal["L_LONGMESSAGE0"]).'</div>';
				echo '<pre>';

				print_r($httpRespostaPaypal);
				echo '</pre>';
		}
		//um IF para verificar se retornou tudo ok.
		if("SUCCESS" == strtoupper($httpRespostaPaypal["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpRespostaPaypal["ACK"])) 
		{
				$_SESSION['ID_TRAN'] = $httpRespostaPaypal["TRANSACTIONID"];
				echo ' SUA ID: '.urldecode($httpRespostaPaypal["TRANSACTIONID"]);
				

					
					if('Pending' == $httpRespostaPaypal["PAYMENTSTATUS"])
					{
						$httpRespostaPaypal = $paypal->FuncEnvio('DoExpressCheckoutPayment', $padata, $user, $pswd, $signature, $Servidor,$paytype);
					}


					$transactionID = urlencode($httpRespostaPaypal["TRANSACTIONID"]);
					$nvpStr = "&TRANSACTIONID=".$transactionID;
					$paypal= new MyPayPal();
					$httpRespostaPaypal = $paypal->FuncEnvio('GetTransactionDetails', $nvpStr, $user, $pswd, $signature, $Servidor,$paytype);
					
					if("SUCCESS" == strtoupper($httpRespostaPaypal["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpRespostaPaypal["ACK"])) {



						//JOGA EM SESSÃO OS DADOS DO USUÁRIO PARA PAGINA DE RETORNO
						$_SESSION["NOME"] = $httpRespostaPaypal["FIRSTNAME"];
						$_SESSION["EMAIL"] = utf8_decode($httpRespostaPaypal["RECEIVEREMAIL"]);
						$_SESSION["CPF"] = $cpf;
						$_SESSION["NOME_ENTREGA"] = $httpRespostaPaypal["SHIPTONAME"];
						$_SESSION["ENDERECO"] = $httpRespostaPaypal["SHIPTOSTREET"];
						$_SESSION["CIDADE"] = $httpRespostaPaypal["SHIPTOCITY"];
						$_SESSION["ESTADO"] = $httpRespostaPaypal["SHIPTOSTATE"];
						$_SESSION["CEP"] = $httpRespostaPaypal["SHIPTOZIP"];
						$_SESSION["EMAIL"] = $httpRespostaPaypal["RECEIVEREMAIL"];
						$_SESSION["NOME"] = $httpRespostaPaypal["FIRSTNAME"];
						$_SESSION["EMAIL"] = utf8_decode($httpRespostaPaypal["RECEIVEREMAIL"]);
						$_SESSION["NOME"] = $httpRespostaPaypal["FIRSTNAME"];
						$_SESSION["EMAIL"] = utf8_decode($httpRespostaPaypal["RECEIVEREMAIL"]);
						
						

					if($paytype == '1'){
					$httpRespostaPaypal = $paypal->FuncEnvio('CreateRecurringPaymentsProfile', $padata, $user, $pswd, $signature, $Servidor,$paytype);
					}					
					if("SUCCESS" == strtoupper($httpRespostaPaypal["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpRespostaPaypal["ACK"])) {
					
						include_once("return.php");
					}else{
												echo '<div style="color:red"><b>GetTransactionDetails Falhou:</b>'.urldecode($httpRespostaPaypal["L_LONGMESSAGE0"]).'</div>';
						echo '<pre>';
						print_r($httpRespostaPaypal);
						echo '</pre>';
					}
					} else  {
						echo '<div style="color:red"><b>GetTransactionDetails Falhou:</b>'.urldecode($httpRespostaPaypal["L_LONGMESSAGE0"]).'</div>';
						echo '<pre>';
						print_r($httpRespostaPaypal);
						echo '</pre>';

					}
		
		}else{
				echo '<div style="color:red"><b>Error : </b>'.urldecode($httpRespostaPaypal["L_LONGMESSAGE0"]).'</div>';
				echo '<pre>';
				print_r($httpRespostaPaypal);
				echo '</pre>';
		}
	
}
?>
