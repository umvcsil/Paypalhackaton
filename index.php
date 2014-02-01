<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Concurso Hackathon PayPal</title>
        <link rel="stylesheet" type="text/css" href="css/site.css" media="all">
            
	<?php
		include_once("configuracao.php");

	?>

 </head>
 
<body>
        <div class="main">
            <header class="main-header">
                <h1 class="main-title"><a href="index.php">Concurso Hackathon PayPal</a></h1>
            </header>
	
			<h1 align="center">Produtos Campus Party</h1>
	            <div class="content" >
					<ul class="product-list">
						<li class="product-item">
							<dl>
							    <form method="post" action="regra.php">
								<dt class="product-title" >Óculos Ciclope</dt>
								 <dd class="product-image"><img src="img/thumb/oculos.jpg" alt="Óculos Ciclope" /></dd>								
								 <dd class="product-price"><span class="currency">R$</span> <span class="value">100.00</span></dd>							    
								 <input type="hidden" name="nomeitem" value="Oculos Ciclope" />
							     <input type="hidden" name="numeroitem" value="1" /> 
								 <input type="hidden" name="itempreco" value="100" />
								 <input type="hidden" name="paytype" value="2" />
								


							</form>
							</dl>
							<a href="details.php?numeroitem=1&nomeitem=Oculos Ciclope&itempreco=100&img=oculos.jpg&paytype=2 ">Ver mais</a>
						</li>
						<li class="product-item">
                        <dl>
							<form method="post" action="regra.php">
                            <dt class="product-title">Revista Mad - R$1 por dia</dt>
							<input type="hidden" name="nomeitem" value="Revista Mad" />
							<input type="hidden" name="numeroitem" value="2" /> 
							<input type="hidden" name="itempreco" value="1" />
							<input type="hidden" name="paytype" value="1" />
							<input type="hidden" name="itemQtd" value="1" />							
                            <dd class="product-image"><img src="img/thumb/mad.jpg" alt="Revista Mad - Assine R$1 por dia" /></dd>
                            <dd class="product-price"><span class="currency">R$</span> <span class="value">1.00</span></dd>


							</form>
                        </dl>
                        <a href="details.php?numeroitem=2&nomeitem=Revista Mad&itempreco=1&img=mad.jpg&paytype=1 ">Ver mais</a>
                    </li>
					</ul>
					</div>	
            
			<div class="footer">
			

						<table>
						
							<tr>
							<td>
							<strong>Certificados de Segurança</strong></td>
							</tr>
							<tr>
							<td>
							<!-- PayPal Logo --> <img src="https://www.paypalobjects.com/webstatic/mktg/br/compra_segura_horizontal.png" border="0" alt="CompraSegura"> <!-- PayPal Logo -->
							</td>
							</tr>
						 </table>
		
			</div>
	</div>				 
	
</body>
</html>