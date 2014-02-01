<?php
class MyPayPal {
    	
	function FuncEnvio($methodName_, $nvpStr_, $user, $pswd, $signature, $Servidor, $tipo) {
			// Configurações
			$API_UserName = urlencode($user);
			$API_Password = urlencode($pswd);
			$API_Signature = urlencode($signature);
			

			
			if($Servidor=='sandbox')
			{
				$Servidor 	=	'.sandbox';
			}
			else
			{
				$Servidor 	=	'';
			}
	
			$API_Endpoint = "https://api-3t".$Servidor.".paypal.com/nvp";
			
		
			// Parâmetros CURL
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
		
			
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
		
			// Coloca as configurações na requisição e define tipo de pagamento
			
			if($tipo=='1'){
			    $version = urlencode('108.0');
				$pay_type = 'RecurringPayments';
				$desc =  'Assinatura';
				$period = 'Day';
				$frequency = '1';
				$maxfailed = '3';
				$time = strtotime(date("G:i:s M m, Y T"));
				$startdate = date('Y-m-d\T00:00:00\Z',  $time);
				 $nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&L_BILLINGTYPE0=$pay_type&DESC=$desc&PROFILESTARTDATE=$startdate&BILLINGFREQUENCY=$frequency&MAXFAILEDPAYMENTS=$maxfailed&BILLINGPERIOD=$period&L_BILLINGAGREEMENTDESCRIPTION0=$desc&SIGNATURE=$API_Signature$nvpStr_";
				}else{
				$version = urlencode('108.0');
				$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

				}
		
			
			curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
		
			// Pega resposta do servidor
			$httpResponse = curl_exec($ch);
		
			if(!$httpResponse) {
				exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
			}
		
			// Mostra os detalhes da resposta
			$httpResponseAr = explode("&", $httpResponse);
		
			$httpRespostaPaypal = array();
			foreach ($httpResponseAr as $i => $value) {
				$tmpAr = explode("=", $value);
				if(sizeof($tmpAr) > 1) {
					$httpRespostaPaypal[$tmpAr[0]] = $tmpAr[1];
				}
			}
		
			if((0 == sizeof($httpRespostaPaypal)) || !array_key_exists('ACK', $httpRespostaPaypal)) {
				exit("Resposta HTTP invalida para requisiçao($nvpreq) to $API_Endpoint.");
			}
			
			/*
			if (!this.isIPNValid($httpRespostaPaypal)) {
				return;
			}
		 
			//Se chegamos até aqui, significa que estamos lidando com uma
			//notificação IPN válida. Agora precisamos verificar se somos o
			//destinatário dessa notificação, verificando o campo receiver_email.
			if ($_POST['receiver_email'] == $receiver_email) {
				
			}
		*/
		
		return $httpRespostaPaypal;
		}
	
	
	function isIPNValid(array $message)
{
		$endpoint = 'https://www.paypal.com';
	 
		if (isset($message['test_ipn']) && $message['test_ipn'] == '1') {
			$endpoint = 'https://www.sandbox.paypal.com';
		}
	 
		$endpoint .= '/cgi-bin/webscr?cmd=_notify-validate';
	 
		$curl = curl_init();
	 
		curl_setopt($curl, CURLOPT_URL, $endpoint);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($message));
	  
		$response = curl_exec($curl);
		$error = curl_error($curl);
		$errno = curl_errno($curl);
	 
		curl_close($curl);
	  
		return empty($error) && $errno == 0 && $response == 'VERIFIED';
}

/**
 * Envia uma requisição NVP para uma API PayPal.
 *
 * @param array $requestNvp Define os campos da requisição.
 * @param boolean $sandbox Define se a requisição será feita no sandbox ou no
 *                         ambiente de produção.
 *
 * @return array Campos retornados pela operação da API. O array de retorno poderá
 *               pode ser vazio, caso a operação não seja bem sucedida. Nesse caso,
 *               os logs de erro deverão ser verificados.
 */
function sendNvpRequest(array $requestNvp, $sandbox = false)
{
    //Endpoint da API
    $apiEndpoint  = 'https://api-3t.' . ($sandbox? 'sandbox.': null);
    $apiEndpoint .= 'paypal.com/nvp';
 
    //Executando a operação
    $curl = curl_init();
 
    curl_setopt($curl, CURLOPT_URL, $apiEndpoint);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($requestNvp));
 
    $response = urldecode(curl_exec($curl));
 
    curl_close($curl);
 
    //Tratando a resposta
    $responseNvp = array();
 
    if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $response, $matches)) {
        foreach ($matches['name'] as $offset => $name) {
            $responseNvp[$name] = $matches['value'][$offset];
        }
    }
 
    //Verificando se deu tudo certo e, caso algum erro tenha ocorrido,
    //gravamos um log para depuração.
    if (isset($responseNvp['ACK']) && $responseNvp['ACK'] != 'Success') {
        for ($i = 0; isset($responseNvp['L_ERRORCODE' . $i]); ++$i) {
            $message = sprintf("PayPal NVP %s[%d]: %s\n",
                               $responseNvp['L_SEVERITYCODE' . $i],
                               $responseNvp['L_ERRORCODE' . $i],
                               $responseNvp['L_LONGMESSAGE' . $i]);
 
            error_log($message);
        }
    }
 
    return $responseNvp;
}
		


}
?>