<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Concurso Hackathon PayPal - Detalhes do produto</title>
        <link rel="stylesheet" type="text/css" href="css/site.css" media="all">
		<?php
		session_start();
		$numero_item = $_GET['numeroitem'];
		$nomeitem = $_GET['nomeitem'];
		$itempreco = $_GET['itempreco'];
		$paytype = $_GET['paytype'];
		$img = $_GET['img'];
		
		?>
    </head>
    <body>
        <div class="main">
            <header class="main-header">
               <h1 class="main-title"><a href="index.php">Concurso Hackathon PayPal</a></h1>
            </header>
            <div class="content">
                <div class="product-details">
                    <div class="product-image"><img src="img/<?php echo $img; ?>" alt="Revista Mad" /></div>
                    <div class="product-info">
					<form method="post" action="regra.php">
					<input type="hidden" name="nomeitem" value="<?php echo $nomeitem; ?>" />
				    <input type="hidden" name="numeroitem" value="<?php echo $numero_item; ?>" /> 
					<input type="hidden" name="itempreco" value="<?php echo $itempreco; ?>" />
					<input type="hidden" name="paytype" value="<?php echo $paytype; ?>" />
                        <h2 class="product-title"><?php echo $nomeitem; ?></h2>
                        <p class="product-price"><span class="currency">R$</span> <span class="value"><?php echo $itempreco; ?></span></p>
                        <p class="product-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam et magna pretium, mattis ipsum ac, tincidunt metus. Fusce eu nulla lacinia, sagittis augue quis, sagittis quam. Duis commodo fringilla mi, sit amet porta nisi vulputate id. Nunc porta mauris neque, ac accumsan nisl volutpat quis. Mauris id elit ac dolor blandit dapibus eget ac erat. Nulla dignissim est ut pretium ullamcorper. Praesent adipiscing consequat nisi sit amet hendrerit. Vestibulum ultrices hendrerit malesuada. Nam viverra, nisi id euismod blandit, metus diam adipiscing ligula, rhoncus dictum lacus lacus a nisl. Duis quis sem sagittis, varius lorem vitae, hendrerit est. Praesent eget varius justo, quis interdum ante. Nulla ultrices velit vestibulum ipsum consectetur malesuada. Praesent egestas, metus et sagittis molestie, mauris sapien interdum felis, ut bibendum risus urna pulvinar sem. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;</p>
                        <dd class="product-qtd" >Quantidade : <select name="itemQtd"><option value="1">1</option><option value="2">2</option><option value="3">3</option></select></dd>
						<input type="image" src="https://www.paypalobjects.com/webstatic/mktg/br/botao-checkout_vertical_ap.png" border="0" class="button" name="submitbutt" />
					</form>
                        <a class="button" href="#">Finalizar Compra</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>