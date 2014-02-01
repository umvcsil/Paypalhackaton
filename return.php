<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Concurso Hackathon PayPal - Página de retorno</title>
        <link rel="stylesheet" type="text/css" href="css/site.css" media="all">
		<?php
		//TRATA AS VARIAVEIS DE SESSÃO
		$nome = urldecode($_SESSION["NOME"]);
		$email = urldecode($_SESSION["EMAIL"]);
		$cpf = urldecode($_SESSION["CPF"]);
		$nome_entrega = urldecode($_SESSION["NOME_ENTREGA"]);
		$endereco = urldecode($_SESSION["ENDERECO"]);
		$cidade = urldecode($_SESSION["CIDADE"]);
		$estado = urldecode($_SESSION["ESTADO"]);
		$cep = urldecode($_SESSION["CEP"]);
		$descricao = urldecode($_SESSION['nomeitem']);
		$quantidade = urldecode($_SESSION["itemQtd"]);
		$total = urldecode($_SESSION['totalamount']);
		$paytype = urldecode($_SESSION['paytype']);
		
		if($paytype == 1){
			$tipo_venda = "Assinatura";
			}else{
			$tipo_venda = "Compra";
			}
				
		
		
		
		?>
    </head>
    <body>
        <div class="main">
            <header class="main-header">
                <h1 class="main-title"><a href="index.php">Concurso Hackathon PayPal</a></h1>
            </header>
            <div class="content">
                <div class="content-info">
                    <h2 class="content-title">Sucesso!</h2>
                    <p>Parabéns <?php echo $nome; ?>, sua <?php echo $tipo_venda; ?> foi registrada com sucesso.</p>
					<P>Caso deseja fazer Reembolso clique no  <a href="refund.php">Link</a></p>
                </div>
                <div class="field-group">
                    <h3 class="field-group-title">Seus dados</h3>
                    <ul class="field-group-list">
                        <li class="field-group-item"><span class="field-group-item-name">Nome:</span> <span class="field-group-item-value"><?php echo utf8_encode($nome); ?></span>
                        <li class="field-group-item"><span class="field-group-item-name">Email:</span> <span class="field-group-item-value"><?php echo utf8_decode($email); ?></span>
                        <li class="field-group-item"><span class="field-group-item-name">CPF:</span> <span class="field-group-item-value"><?php echo $cpf; ?></span>
                    </ul>
                </div>
                <div class="field-group">
                    <!--
                    Caso sua integração vá vender produtos digitais, então essa seção não é necessária. O endereço
                    de entrega deve ser exibido apenas no caso de produtos físicos.
                    -->
                    <h3 class="field-group-title">Dados para entrega (apenas para produtos físicos)*</h3>
                    <ul class="field-group-list">
                        <li class="field-group-item"><span class="field-group-item-name">Nome:</span> <span class="field-group-item-value"><?php echo $nome_entrega; ?></span>
                        <li class="field-group-item"><span class="field-group-item-name">Endereço:</span> <span class="field-group-item-value"><?php echo $endereco; ?></span>
                        <li class="field-group-item"><span class="field-group-item-name">Cep:</span> <span class="field-group-item-value"><?php echo $cep; ?></span>
                        <li class="field-group-item"><span class="field-group-item-name">Cidade:</span> <span class="field-group-item-value"><?php echo $cidade; ?></span>
                        <li class="field-group-item"><span class="field-group-item-name">Estado:</span> <span class="field-group-item-value"><?php echo $estado; ?></span>
                    </ul>
                </div>
                <div class="field-group">
                    <h3 class="field-group-title">Produtos comprados</h3>
                    <table class="field-group-table">
                        <thead class="field-group-table-header">
                            <tr>
                                <th width="50%">Produto</th>
                                <th width="20%">Quantidade</th>
                                <th width="*">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $descricao; ?></td>
                                <td><?php echo $quantidade; ?></td>
                                <td>R$  <?php echo $total; ?></td>
                            </tr>
                            
                        </tbody>
                        <tfoot class="field-group-table-footer">
                            <tr>
                                <td colspan="2" class="field-group-total-name">Total</td>
                                <td>R$ <?php echo $total; ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>