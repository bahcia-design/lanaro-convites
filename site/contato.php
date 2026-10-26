<?php
	if (isset($_GET["checkstatus"])) {
		die("online");
	}
	
	error_reporting(E_ALL);

	require_once "./gallery/site/mainlib.php";
	
	include "head.php";
?>

    <div id="tf-contact">
        <div class="container">
            <div class="section-title">
                <h3>Contato e Localização</h3>
                <hr>
				<div class="row">
					<div class="col-sm-6">
						<i class="fa fa-phone-square" aria-hidden="true"></i> 19 3601.6781<br />
						<i class="fa fa-comments" aria-hidden="true"></i> <a href='https://api.whatsapp.com/send?phone=5519981487391&text=Ol%C3%A1,%20tudo%20bem?' target='_blank'>19 98148.7391 (whatsapp)</a>
						<br />&nbsp;<br />
						<i class="fa fa-envelope" aria-hidden="true"></i> <a href="mailto:contato@lanaroconvites.com.br">contato@lanaroconvites.com.br</a>
						<br />&nbsp;<br />
						<i class="fa fa-facebook" aria-hidden="true"></i> <a href="https://www.facebook.com/L%C3%A1-na-R%C3%B4-Convites-e-Arte-121694601304667/">facebook.com/Lá-na-Rô-Convites-e-Arte</a>
						<br />&nbsp;<br />
						<i class="fa fa-instagram" aria-hidden="true"></i> <a href="https://www.instagram.com/lanaroconvites/">@lanaroconvites</a>
						<br />&nbsp;<br />
						<i class="fa fa-map-marker" aria-hidden="true"></i> Av. Cillos, 3.641<br />
						Pq. Novo Mundo<br />
						Americana/SP
						<br />&nbsp;<br />
						<i class="fa fa-clock-o" aria-hidden="true"></i> Horário de Atendimento:<br />
						<b>Segunda a Sexta:</b><br />
						9h00 às 18h00
						<br />
						<b>Sábados:</b><br />
						9h00 às 12h00
					</div>
					<div class="col-sm-6">
						<iframe class="img-thumbnail" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7357.5880522073!2d-47.347812!3d-22.773024!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94c8996b1109fd55%3A0x8992d49c603d358f!2sAv.+de+Cillo%2C+3641+-+Jardim+Sao+Jose%2C+Americana+-+SP%2C+13467-600!5e0!3m2!1spt-BR!2sbr!4v1524699155185" width="100%" frameborder="0" style="border:0;height:300px" allowfullscreen></iframe>
					</div>
				</div>
            </div>
        </div>
    </div>
				
<?php
	include "foot.php";
?>