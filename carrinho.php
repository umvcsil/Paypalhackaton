<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Concurso Hackathon PayPal - Detalhes do produto</title>
        <link rel="stylesheet" type="text/css" href="css/site.css" media="all">  
		<?php
		session_start();
		
		$produto = $_SESSION['PRODUTO'];
		//PEGA A CHAVE DO ARRAY
		$chave = array_keys($produto);

		//EXIBE
		for($i=0; $i<sizeof($chave); $i++) {
		$indice = $chave[$i];

		//VERIFICA
		//if(!empty($produto[$indice][QTDE]) ) {

		//GRAVA NO ARRAY CESTA
		$cesta[$indice]['NUMERO_ITEM'] = $produto[$indice]['NUMERO_ITEM'];
		$cesta[$indice]['NOME_ITEM'] = $produto[$indice]['NOME_ITEM'];
		$cesta[$indice]['ITEM_PRECO'] = $produto[$indice]['ITEM_PRECO'];
		//$cesta[$indice][QTDE] = $produto[$indice]['QTDE'];
		//}//FECHA IF
		}//FECHA FOR
		//GRAVA NA SESSÃO
		$_SESSION['cesta'] = $cesta;
		?>
  </head>
    <body>
        <div class="main">
            <header class="main-header">
               <h1 class="main-title"><a href="index.php">Concurso Hackathon PayPal</a></h1>
            </header>
            <div class="content">
                <div class="product-details">
				<td><font size="2" face="Arial">Carrinho de Compras: </font></td>
</tr>
</table>
<br>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr bgcolor="#CCCCCC">
<td width="6%">&nbsp;</td>
<td width="11%"><span class="style2">Qtde</span></td>
<td width="58%"><span class="style2">Produto</span></td>
<td width="25%"><span class="style2">Valor</span></td>
</tr>

<?php
//PEGA A CHAVE

$chave_cesta = array_keys($_SESSION['cesta']);
$cesta = $_SESSION['cesta'];
//EXIBE OS PRODUTOS DA CESTA
for($i=0; $i<sizeof($chave_cesta); $i++) { 
	$indice = $chave_cesta[$i]; 
?>
<tr>
<td height="25">&nbsp;</td>
<td height="25"><font face=’Arial’ size=’2′><?php echo  $cesta[$indice]['NUMERO_ITEM']; ?></font></td>
<td height="25"><font face=’Arial’ size=’2′><?php echo  $cesta[$indice]['NOME_ITEM']; ?> </font></td>
<td height="25"><font face=’Arial’ size=’2′>R$ <?php  echo $cesta[$indice]['ITEM_PRECO']; ?></font></td>
</tr>

<?php
}//FECHA FOR ?>
				</div>
			</div>	