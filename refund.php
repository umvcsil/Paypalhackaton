
<?php
session_start();
include_once("configuracao.php");
include_once("paypal.class.php");


$ID_TRAN = $_SESSION['ID_TRAN'];
$nome = $_SESSION["NOME"];

 
//Campos da requisição da operação RefundTransaction.
$requestNvp = array(
    'USER' => $user,
    'PWD' => $pswd,
    'SIGNATURE' => $signature,
 
    'VERSION' => '108.0',
    'METHOD'=> 'RefundTransaction',
 
    'TRANSACTIONID' => $ID_TRAN,
    'REFUNDTYPE' => 'Full'
);
 
//Envia a requisição e obtém a resposta da PayPal
$paypal= new MyPayPal();
$responseNvp = $paypal->sendNvpRequest($requestNvp, $Servidor);

 
//Verifica se a operação foi bem sucedida
if (isset($responseNvp['ACK']) && $responseNvp['ACK'] == 'Success') {
    echo "SUCESSO";
}



?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Concurso Hackathon PayPal - Página de retorno</title>
        <link rel="stylesheet" type="text/css" href="css/site.css" media="all">
		</head>
    <body>
        <div class="main">
            <header class="main-header">
                <h1 class="main-title"><a href="index.php">Concurso Hackathon PayPal</a></h1>
            </header>
            <div class="content">
                <div class="content-info">
                    <h2 class="content-title"><?php echo $nome; ?>, Reebolso com Sucesso!</h2>
					<!-- PayPal Logo --> <img src="https://www.paypalobjects.com/webstatic/mktg/br/compra_segura_horizontal.png" border="0" alt="CompraSegura"> <!-- PayPal Logo -->
				</div>
			</div>
	</body>
</html>