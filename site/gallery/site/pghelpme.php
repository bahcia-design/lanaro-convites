<?php
	require_once "mainlib.php"; /* Funções gerais, chamar em todo .php de conteúdo que for criado */
	
	/* Título com a quantidade de registros encontrados */
	titleInfo("Dicas e Ajuda","Selecione um tópico para saber mais:");
	?>
	<div id="item1" class="well">
		<div class="negrito"><a for="c_item1" href="javascript:void(0)" onclick="helpDisplay(this)">Acesso ao sistema</a></div>
		<div id="c_item1" display="false" class="oculto">
			<div class="margemabaixo">
				O sistema <i>Gallery</i> possui apenas dos usuários:<br />
				- <b>Administrador</b>: responsável pelo gerenciamento do sistema, criação de álbuns e definição das configurações.<br />
				- <b>Editor</b>: possui apenas permissão para gerenciar o conteúdo dos álbuns já criados. Ou seja, subir ou eliminar imagens.<br />
				A senha dos dois tipos de usuário, bem como nome de apresentação são definidas pelo Administrador, a senha inicial do administrador é
				"gallery", enquando do editor é apenas "editor".<br />
				<b>Nota:</b> é recomendável que o Administrador altere as senhas logo após a instalação do sistema, por questões de segurança.
			</div>
			<div class="alert alert-danger">
			<strong>Esqueci a senha</strong>
			Se for Editor, apenas peça uma nova senha ao Administrador. Se você for o Administrador, peça
			uma nova senha ao responsável pela manutenção e criação de seu website.
			</div>
		</div>
	</div>
	
	<div id="item2" class="well">
		<div class="negrito"><a for="c_item2" href="javascript:void(0)" onclick="helpDisplay(this)">Álbuns (galerias)</a></div>
		<div id="c_item2" display="false" class="oculto">
			<div class="margemabaixo">
				Você pode consultar os álbuns através do link "álbuns" na home page, se você for Administrador, nesta mesma
				página encontrará um botão "Criar novo álbum agora" onde pode criar um novo álbun de imagens.<br />
				Basta informar o nome e descrição do novo álbum para continuar. Em seguida é só enviar as imagens de seu computador
				ou smartphone.
			</div>
			<div class="alert alert-danger">
			  <strong>Obrigatório:</strong>
			  O nome do álbum é obrigatório, e diferente de qualquer outro já utilizado. A Descrição é opcional, porém recomendada.
			</div>
			<div class="alert alert-danger">
			  <strong>Não consigo criar um álbum:</strong>
			  Apenas o Administrador possui permissões para criar álbuns, caso você esteja com a senha de Administrador, verifique qual mensagem está sendo apresentada e informe
			  ao responsável pela criação e manutenção do website.
			</div>
		</div>
	</div>
	
	<div id="item3" class="well">
		<div class="negrito"><a for="c_item3" href="javascript:void(0)" onclick="helpDisplay(this)">Envio de fotos</a></div>
		<div id="c_item3" display="false" class="oculto">
			<div class="margemabaixo">
				Para enviar fotos, você primeiro precisa selecionar o álbum desejado (ou criar um, se for o caso). A seleção das fotos é múltipla, ou seja, você pode enviar
				várias imagens de uma única vez (desde que não ultrapasse os limites da conexão). O envio pode ser feito de:<br />
				- Uma pasta de seu computador<br />
				- A galeria de fotos de seu smartphone<br />
				- Diretamente da câmera de seu smartphone			
			</div>
			<div class="alert alert-danger">
			  <strong>Alguns cuidados:</strong><br />
			  - Não selecione muitas imagens, o upload pode demorar muito ou até mesmo ser impedido.<br />
			  - Selecione apenas arquivos de imagens<br />
			  - <b>Recomendamos</b> fortemente que você utilize um software de redimensionamento e marca d'água das imagens antes do upload. Além de agilizar o seu upload, isto ainda
			  protege suas imagens contra uso não autorizado.
			</div>
			<div class="alert alert-danger">
			  <strong>Não consigo enviar imagens:</strong>
			  Uma mensagem com o problema é apresentada quando o envio não é concluído. Algumas causas prováveis são:<br />
			  - Imagem está muito pesada (muito grande) ou você está selecionando muitas imagens.<br />
			  - O arquivo não é um tipo de imagem válido. O tipo mais recomendado é imagem em <i>JPG</i>.<br />
			  - Sua internet está muito lenta, se estiver em uma conexão de dados, experimente acessar via uma rede WiFi.<br />
			  - O servidor pode estar indisponível no momento. Neste caso apenas tente enviar daqui alguns minutos.<br />
			  - Caso não encontre uma solução, anote a mensagem apresentada na tentativa de upload e informe ao desenvolvedor de seu website.
			</div>
		</div>
	</div>
	
	<div id="item4" class="well">
		<div class="negrito"><a for="c_item4" href="javascript:void(0)" onclick="helpDisplay(this)">Capa do Álbum</a></div>
		<div id="c_item4" display="false" class="oculto">
			<div class="margemabaixo">
				Quando você sobre as primeiras fotos da galeria, o sistema escolhe automáticamente uma delas para a <i>capa</i> do álbum. Claro que você pode alterar depois conforme
				o seu gosto. Para tal, basta que você selecione a imagem, e após ela abrir, clique no botão "capa" logo abaixo da imagem.
			</div>
			<div class="alert alert-danger">
			  <strong>E se eu excluir a foto de capa?</strong><br />
			  O álbum ficará sem capa até que você mesmo defina uma nova imagem como capa.
			</div>
		</div>
	</div>
	
	<div id="item5" class="well">
		<div class="negrito"><a for="c_item5" href="javascript:void(0)" onclick="helpDisplay(this)">Excluir fotos</a></div>
		<div id="c_item5" display="false" class="oculto">
			<div class="margemabaixo">
				Para excluir as fotos basta selecionar o álbum e então clicar na foto desejada para abrir. Quando a foto aparecer, nos botões abaixo, um deles terá a função
				de excluir a imagem. <b>Tome cuidado</b> pois esta função não pode ser desfeita.
			</div>
			<div class="alert alert-danger">
			  <strong>Como faz para excluir todas as fotos de uma vez?</strong><br />
			  Para o <i>Administrador</i> isto é possível, o botão que elimina todas as fotos do álbum de uma vez está no rodadé da página do álbum.<br />
			  A senha do Administrador será exigida para esta operação.
			</div>
		</div>
	</div>