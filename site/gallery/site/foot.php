        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Imagine Sites <?php echo date("Y"); ?></p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->
	
	<!-- Alguns modais necessários para mensagens ao usuário -->
	<div class="modal fade" id="modalWindow" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
				  <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
				  <h4 class="modal-title" id="modalTitle">Modal Header</h4>
				</div>
				<div class="modal-body" id="modalContent">
				  <p>This is a large modal.</p>
				</div>
				<div class="modal-footer">
				  <span id="sModalLoading" class="oculto"><img id="imgLoading" src="./img/load_blue1.gif" /> Processando...&nbsp;&nbsp;&nbsp;</span>
				  <button id="modalButton1" type="button" class="btn btn-info">Button 1</button>
				  <button id="modalButton2" type="button" class="btn btn-info">Button 2</button>
				  <button id="modalButton3" type="button" class="btn btn-info">Button 3</button>
				</div>
			</div>
		</div>
	</div>
	<!--------------------------------------------------------->
	
	<!-- Modal especial para telas de loading -->
	<div class="modal fade" id="loadingWindow" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
				  <h4 class="modal-title">Aguarde...</h4>
				</div>
				<div class="modal-body">
					<div class="central margemabaixo">
						<img src="./img/comm1.png" class="imgcomm" /><br />&nbsp;<br />
						<img src="./img/load_blue2.gif" />
					</div>
					<div class="central negrito" id="loadingTitle">
						Carregando...
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--------------------------------------------------------->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
	<!-- Custom Scripts -->
	<script src="js/scripts1.1.js"></script>
	<?php
		if (sessionField("usersession")!="") {
			echo "<script src=\"js/admin1.5.js\"></script>";
		}
	?>
</body>
</html>