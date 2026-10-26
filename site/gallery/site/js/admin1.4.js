/**
	Scritps privados (somente para logados)
*/

var USER_EDITOR = 0;
var USER_ADMIN = 1;

var selectedAlbum = 0;
var sendingAboutPic = false;
var sendingArticlePic = false;
var sendingFrasesPic = false;
var sendingTipsPic = false;

/* Caminhos dos web services */
var URL_CHANGENAME = "./ws/wschgname.php";
var URL_CHANGEPASS = "./ws/wschgpass.php";
var URL_NEWALBUM   = "./ws/wsnewalbum.php";
var URL_SENDPHOTOS = "./ws/wssendphotos.php";
var URL_SENDABOUTP = "./ws/wssendaboutpic.php";
var URL_SENDFRASESP = "./ws/wssendfrasespic.php";
var URL_SENDARTICLEP = "./ws/wssendarticlepic.php";
var URL_SENDTIPSP = "./ws/wssendtipspic.php";
var URL_EDITPHOTOS = "./ws/wseditphotos.php";
var URL_DELALBUM   = "./ws/wsdelalbum.php";
var URL_DELPICTURE = "./ws/wsdelpicture.php";
var URL_ALBUMCOVER = "./ws/wsalbumcover.php";
var URL_SAVECONFIG = "./ws/wssaveconf.php";
var URL_ALBUMDATA  = "./ws/wsalbumdata.php";
var URL_ABOUTDATA  = "./ws/wssetaboutdata.php";
var URL_FRASESDATA  = "./ws/wssetfrasesdata.php";
var URL_TIPSDATA  = "./ws/wssettipsdata.php";
var URL_EDIT_ARTICLE = "./ws/wssetarticle.php";

/**
	XMLHttpRequest é o "componente" dos browsers que faz comunicação de arquivos pela rede.
	É usado tanto para baixar quanto para enviar conteúdos
	Segurança: por questões de hacking, este componente só consegue manipular conexões
	dentro do próprio domínio de seu site ou servidor local.
*/
var uploadPhotos = new XMLHttpRequest();
/**
	Este evento abaixo é executado toda vez que o componente percebe uma mudança de status
	na rede, quando você está baixando ou enviando um arquivo. 
*/
uploadPhotos.onreadystatechange = function() 
{
	/* State 4 e Status 200 é o sinal de que o download ou upload terminou.
	   Existem outros sinais, mas vamos  ignorar isso por enquanto. */
	if (uploadPhotos.readyState == 4) {
		if (uploadPhotos.status == 200) {
			/* Status OK significa que a comunicação foi feita com sucesso. */
			if (uploadPhotos.statusText=='OK') {
				var resultado = uploadPhotos.responseText; /* responseText é o resultado que seu PHP de upload gera (os echo(...) da vida) retornam como String */
				/* Propositalmente se meu PHP tiver problemas com a foto eu resulto uma string que começa com "erro:", se não resulto uma com "sucesso:" e o nome da imagem gerada.
				   Abaixo eu checo o que tem na string de resultado */
				if (resultado.indexOf('erro:') >= 0)
					modalWindow({
						title: "Ops!",
						content: resultado.replace('erro:',''),
						icon: ICO_ASK,
						button1:{caption:"OK",action:"modalHide()"}
					});
				else {
					/* Chama a página de atualização das novas imagens (se não erro) */
					window.location = 'review.php?idalbum='+selectedAlbum+'&pictures='+resultado.replace('success:','');
				}
			} else /* Qualquer outro erro a gente apenas diz que não foi possível por enquanto (podem ser dezenas de erros) */
				modalWindow({
					title: "Ops!",
					content: "Não foi possível concluir a operação. Por favor, tente novamente mais tarde.",
					icon: ICO_ASK,
					button1:{caption:"OK",action:"modalHide()"}
				});
		} else {
			modalWindow({
				title: "Ops!",
				content: "Não foi possível concluir a operação. Por favor, tente novamente mais tarde.",
				icon: ICO_ASK,
				button1:{caption:"OK",action:"modalHide()"}
			});
		}
	}
}

var imgAboutSend   = new XMLHttpRequest();

/* Rotina de sucesso do envio de imagem deve ser criada dinamicamente */
imgAboutSendInfoSucess = function() {};

/* Este evento abaixo é executado toda vez que o componente percebe uma mudança de status
   na rede, quando você está baixando ou enviando um arquivo. */
imgAboutSend.onreadystatechange = function() 
{
	/* State 4 e Status 200 é o sinal de que o download ou upload terminou.
	   Existem outros sinais, mas vamos  ignorar isso por enquanto. */
	if (imgAboutSend.readyState == 4) { 
		if (imgAboutSend.status == 200) {
			/* Status OK significa que a comunicação foi feita com sucesso. */
			if (imgAboutSend.statusText=='OK') {
				imgAboutSendInfoSucess(imgAboutSend.responseText); /* Processa o resultado */
			} else { /* Qualquer outro erro a gente apenas diz que não foi possível por enquanto (podem ser dezenas de erros) */
				modalWindow({
					title: "Ops!",
					content: "Não foi possível concluir a operação. Por favor, tente novamente mais tarde.",
					icon: ICO_ASK,
					button1:{caption:"OK",action:"modalHide()"}
				});
			}
		} else {
			modalWindow({
				title: "Ops!",
				content: "Não foi possível concluir a operação. Por favor, tente novamente mais tarde.",
				icon: ICO_ASK,
				button1:{caption:"OK",action:"modalHide()"}
			});
		}
	}
}

var imgFrasesSend   = new XMLHttpRequest();

/* Rotina de sucesso do envio de imagem deve ser criada dinamicamente */
imgFrasesSendInfoSucess = function() {};

/* Este evento abaixo é executado toda vez que o componente percebe uma mudança de status
   na rede, quando você está baixando ou enviando um arquivo. */
imgFrasesSend.onreadystatechange = function() 
{
	/* State 4 e Status 200 é o sinal de que o download ou upload terminou.
	   Existem outros sinais, mas vamos  ignorar isso por enquanto. */
	if (imgFrasesSend.readyState == 4) { 
		if (imgFrasesSend.status == 200) {
			/* Status OK significa que a comunicação foi feita com sucesso. */
			if (imgFrasesSend.statusText=='OK') {
				imgFrasesSendInfoSucess(imgFrasesSend.responseText); /* Processa o resultado */
			} else { /* Qualquer outro erro a gente apenas diz que não foi possível por enquanto (podem ser dezenas de erros) */
				modalWindow({
					title: "Ops!",
					content: "Não foi possível concluir a operação. Por favor, tente novamente mais tarde.",
					icon: ICO_ASK,
					button1:{caption:"OK",action:"modalHide()"}
				});
			}
		} else {
			modalWindow({
				title: "Ops!",
				content: "Não foi possível concluir a operação. Por favor, tente novamente mais tarde.",
				icon: ICO_ASK,
				button1:{caption:"OK",action:"modalHide()"}
			});
		}
	}
}

var imgArticleSend   = new XMLHttpRequest();

/* Rotina de sucesso do envio de imagem deve ser criada dinamicamente */
imgArticleSendInfoSucess = function() {};

/* Este evento abaixo é executado toda vez que o componente percebe uma mudança de status
   na rede, quando você está baixando ou enviando um arquivo. */
imgArticleSend.onreadystatechange = function() 
{
	/* State 4 e Status 200 é o sinal de que o download ou upload terminou.
	   Existem outros sinais, mas vamos  ignorar isso por enquanto. */
	if (imgArticleSend.readyState == 4) { 
		if (imgArticleSend.status == 200) {
			/* Status OK significa que a comunicação foi feita com sucesso. */
			if (imgArticleSend.statusText=='OK') {
				imgArticleSendInfoSucess(imgArticleSend.responseText); /* Processa o resultado */
			} else { /* Qualquer outro erro a gente apenas diz que não foi possível por enquanto (podem ser dezenas de erros) */
				modalWindow({
					title: "Ops!",
					content: "Não foi possível concluir a operação. Por favor, tente novamente mais tarde.",
					icon: ICO_ASK,
					button1:{caption:"OK",action:"modalHide()"}
				});
			}
		} else {
			modalWindow({
				title: "Ops!",
				content: "Não foi possível concluir a operação. Por favor, tente novamente mais tarde.",
				icon: ICO_ASK,
				button1:{caption:"OK",action:"modalHide()"}
			});
		}
	}
}

var imgTipsSend   = new XMLHttpRequest();

/* Rotina de sucesso do envio de imagem deve ser criada dinamicamente */
imgTipsSendInfoSucess = function() {};

/* Este evento abaixo é executado toda vez que o componente percebe uma mudança de status
   na rede, quando você está baixando ou enviando um arquivo. */
imgTipsSend.onreadystatechange = function() 
{
	/* State 4 e Status 200 é o sinal de que o download ou upload terminou.
	   Existem outros sinais, mas vamos  ignorar isso por enquanto. */
	if (imgTipsSend.readyState == 4) { 
		if (imgTipsSend.status == 200) {
			/* Status OK significa que a comunicação foi feita com sucesso. */
			if (imgTipsSend.statusText=='OK') {
				imgTipsSendInfoSucess(imgTipsSend.responseText); /* Processa o resultado */
			} else { /* Qualquer outro erro a gente apenas diz que não foi possível por enquanto (podem ser dezenas de erros) */
				modalWindow({
					title: "Ops!",
					content: "Não foi possível concluir a operação. Por favor, tente novamente mais tarde.",
					icon: ICO_ASK,
					button1:{caption:"OK",action:"modalHide()"}
				});
			}
		} else {
			modalWindow({
				title: "Ops!",
				content: "Não foi possível concluir a operação. Por favor, tente novamente mais tarde.",
				icon: ICO_ASK,
				button1:{caption:"OK",action:"modalHide()"}
			});
		}
	}
}

/* Pergunta sobre exclusão da imagem */
function askAlbumCover(albumID, filename) {
	modalWindow({                                             
		title: "Imagem de capa",
		content: "<div style='padding-bottom:20px'>Definir capa com esta imagem?</div>",
	    icon: ICO_ASK,
		button1:{caption:"Sim",action:"confirmAlbumCover("+albumID+",'"+filename+"')"},
		button2:{caption:"Não",action:"modalHide()"}
	});
}

/* Traz uma janela para edição do álbum */
function askAlbumEdit(albumID) {
	/* Em caso de retorno com sucesso do ajax */
	withSuccess = function(data) {
		/* Em caso de sucesso */
		if (data.feedback=="success") {
			htmlContent = "<div>Nome do álbum:</div>";
			htmlContent += "<div id='dAlbumName' class='form-group has-feedback'><input type='text' class='form-control' id='textAlbumName' placeholder='Nome do álbum' value='"+data.albumName+"' /><span id='sAlbumName' class='glyphicon glyphicon-warning-sign form-control-feedback oculto'></span></div>";
			htmlContent += "<div id='dAlbumDesc' class='form-group has-feedback'><textarea class='form-control' id='textAlbumDesc' placeholder='Descrição'>"+data.albumDesc+"</textarea><span id='sAlbumDesc' class='glyphicon glyphicon-warning-sign form-control-feedback oculto'></span></div>";

			modalWindow({
				title: "Editar álbum",
				content: htmlContent,
				icon: ICO_ASK,
				button1:{caption:"Salvar...",action:"startSaveAlbum("+albumID+")"},
				button2:{caption:"Cancelar",action:"modalHide()"}
			});
		} else {
			/* Erro genérico não identificado de conexão ou servidor */
			modalWindow({
				title: "Ops!", 
				icon: ICO_ERROR,
				content: "Não foi possível edita o álbum agora.<br />Tente mais tarde por favor.",
				button1:{caption:"OK",action:"modalHide()"}
			});
		}			
	};
	
	/* Chama a rotina de criação do álbum */
	$.ajax({
	  type: "POST",
	  url: URL_ALBUMDATA,
	  data: {idalbum:albumID},
	  success: withSuccess,
	  dataType: "json"
	});
}

/* Solicita a mudança do nome do usuário */
function askChangeName(USER_TYPE, currentName) {
	htmlInput = "Nome do usuário:<br /><input type='text' class='form-control' value='"+currentName+"' id='textNewName' />";
	modalWindow({                                             
		title: "Alterar nome",
		content: htmlInput,
	    icon: ICO_ASK,
		button1:{caption:"Salvar",action:"startSaveNewName("+USER_TYPE+")"},
		button2:{caption:"Cancelar",action:"modalHide()"}
	});
}

/* Solicita a mudança da senha de acesso do usuário */
function askChangePassword(USER_TYPE, oldPass, newPass, newPass2) {
	if (USER_TYPE==USER_ADMIN) {
		htmlTitle = "Alterar senha do Administrador";
		htmlInput = "<div id='dCurrentPass' class='form-group has-feedback'><label class='control-label' for='textCurrentPass'>Senha atual</label><input type='password' class='form-control' id='textCurrentPass' value='"+oldPass+"' /><span id='sCurrentPass' class='glyphicon glyphicon-warning-sign form-control-feedback oculto'></span></div>";
		htmlInput += "<div id='dNewPass' class='form-group has-feedback'><label class='control-label' for='textNewPass'>Nova senha</label><input type='password' class='form-control' id='textNewPass' value='"+newPass+"' /><span id='sNewPass' class='glyphicon glyphicon-warning-sign form-control-feedback oculto'></span></div>";
		htmlInput += "<div id='dRepeatPass' class='form-group has-feedback'><label class='control-label' for='textRepeatPass'>Confirme a senha</label><input type='password' class='form-control' id='textRepeatPass' value='"+newPass2+"' /><span id='sRepeatPass' class='glyphicon glyphicon-remove form-control-feedback oculto'></span></div>";
	} else {
		htmlTitle = "Alterar senha do Editor";
		htmlInput = "<div id='dNewPass' class='form-group has-feedback'><label class='control-label' for='textNewPass'>Nova senha</label><input type='password' class='form-control' id='textNewPass' value='"+newPass+"' /><span id='sNewPass' class='glyphicon glyphicon-warning-sign form-control-feedback oculto'></span></div>";
		htmlInput += "<div id='dRepeatPass' class='form-group has-feedback'><label class='control-label' for='textRepeatPass'>Confirme a senha</label><input type='password' class='form-control' id='textRepeatPass' value='"+newPass2+"' /><span id='sRepeatPass' class='glyphicon glyphicon-remove form-control-feedback oculto'></span></div>";
	}

	modalWindow({                                             
		title: htmlTitle,
		content: htmlInput,
	    icon: ICO_ASK,
		button1:{caption:"Salvar",action:"startSaveNewPassword("+USER_TYPE+")"},
		button2:{caption:"Cancelar",action:"modalHide()"}
	});
}

/* Pergunta sobre deleção do álbum inteiro */
function askDeleteAlbum(albumID) {
	modalWindow({                                             
		title: "Excluir álbum completo",
		content: "<div style='padding-bottom:20px'>Tem certeza de que deseja <b>apagar</b> este álbum e todas as suas imagens?</div><div class='alert alert-danger'><strong>Atenção!</strong> Esta operação não poderá ser desfeita.</div>",
	    icon: ICO_ASK,
		button1:{caption:"Sim",action:"confirmPassDeleteAlbum("+albumID+")"},
		button2:{caption:"Não",action:"modalHide()"}
	});
}

/* Pergunta sobre exclusão da imagem */
function askDeletePicture(picID, albumID) {
	modalWindow({                                             
		title: "Excluir imagem",
		content: "<div style='padding-bottom:20px'>Deseja mesmo <b>excluir</b> esta imagem?</div><div class='alert alert-danger'><strong>Atenção!</strong> Esta operação não poderá ser desfeita.</div>",
	    icon: ICO_ASK,
		button1:{caption:"Sim",action:"confirmDeletePicture("+picID+","+albumID+")"},
		button2:{caption:"Não",action:"modalHide()"}
	});
}

/* Solicita os dados para a criação de um novo álbum */
function askNewAlbum(name, desc) {
	htmlContent = "<div>Escolha um nome para o novo álbum:</div>";
	htmlContent += "<div id='dAlbumName' class='form-group has-feedback'><input type='text' class='form-control' id='textAlbumName' placeholder='Nome do álbum' value='"+name+"' /><span id='sAlbumName' class='glyphicon glyphicon-warning-sign form-control-feedback oculto'></span></div>";
	htmlContent += "<div id='dAlbumDesc' class='form-group has-feedback'><textarea class='form-control' id='textAlbumDesc' placeholder='Descrição'>"+desc+"</textarea><span id='sAlbumDesc' class='glyphicon glyphicon-warning-sign form-control-feedback oculto'></span></div>";

	modalWindow({
		title: "Novo álbum",
		content: htmlContent,
	    icon: ICO_ASK,
		button1:{caption:"Criar...",action:"startSaveAlbum()"},
		button2:{caption:"Cancelar",action:"modalHide()"}
	});
}

/* Salva o novo nome de uma imagem */
function askSavePicture(idpic, idalbum) {
	$("#dbuttons").hide();
	$("#ddetailloading").removeClass("oculto");
	
	/* Em caso de retorno com sucesso do ajax */
	withSuccess = function(data) {
		/* Em caso de sucesso */
		if (data.feedback=="success") {
			/* Apenas recarrega a página para atualizar os dados do usuário */
			window.location = "./album.php?idalbum="+idalbum;
		} else if (data.feedback=="fail") {
			/* Retorno de falha no processamento da senha */
			modalWindow({
				title: "Ops!", 
				icon: ICO_ERROR,
				content: data.errorname,
				button1:{caption:"OK",action:"modalHide()"}
			});
		} else {
			/* Erro genérico não identificado de conexão ou servidor */
			modalWindow({
				title: "Ops!", 
				icon: ICO_ERROR,
				content: "Não foi possível edita a imagem agora.<br />Tente mais tarde por favor.",
				button1:{caption:"OK",action:"modalHide()"}
			});
		}			
	};
	
	/* Evita as quebras de linha e aspas */
	desc = $("#f_descpic").val();
	while (desc.indexOf('"') >= 0)
		desc = desc.replace('"','');

	/* Evita as quebras de linha e aspas */
	while (desc.indexOf('\n') >= 0)
		desc = desc.replace('\n','<br>');
		
	/* Especialistas selecionados */
	specsel = $.trim($("#sp_sel_"+idpic).val());
	
	/* Artigo selecionado */
	article = $.trim($("#cboArticle").val());
	
	/* Chama a rotina de criação do álbum */
	$.ajax({
	  type: "POST",
	  url: URL_EDITPHOTOS,
	  data: {idpic_0:idpic,namepic_0:$("#f_namepic").val(),descpic_0:desc,specpic_0:specsel,article_0:article,total:"1"},
	  success: withSuccess,
	  dataType: "json"
	});
}

/* Abre o diálogo para envio de imagens */
function askSendPictures(albumid) {
	displayUpload = "none";

	htmlContent = "<div><div>Categoria:</div>";
	htmlContent = htmlContent + "<div style='padding-bottom:20px'><select onchange='checkCategoryName()' class='form-control' id='cboCategory'><option value=''>Selecione</option>";
	if ($("#wellcat").attr("total")) {
		total = $("#wellcat").attr("total");
		for (i=0;i<total;i++) {
			name = $("#btncat"+i).html();
			selected = ($("#btncat"+i).attr("selectme") == "yes") ? "selected" : "";
			if (selected=="selected")
				displayUpload = "block";
			htmlContent = htmlContent + "<option "+selected+" value='"+name+"'>"+name+"</option>";
		}
	}

	htmlContent = htmlContent + "<option value='N'>Nova categoria...</option></select></div>";
	htmlContent = htmlContent + "<div id='dformphotos' style='display:"+displayUpload+"'><div>Selecione as fotos para enviar:</div>";
	htmlContent = htmlContent + "<div>";
	htmlContent = htmlContent + "<div id='daddphoto' style=\"width:85px;height:85px;background-image:url('./img/ico_add_photo_1.0.png');cursor:pointer\">";
	htmlContent = htmlContent + "<form id='fsendphotos'><input type='file' multiple='true' accept='image/*' id='f_photos' name='f_photos[]' style='opacity:0;filter:Alpha(opacity=0);width:85px;height:85px;cursor:pointer' onchange='startSendPhotos("+albumid+")' /></form>";
	htmlContent = htmlContent + "</div>";
	htmlContent = htmlContent + "<div id='dloadingphotos' style=\"display:none;width:85px;height:85px;text-align:center;border:1px solid #404040;font-size:8pt;vertical-align:middle\">&nbsp;<br /><img src='./img/ico_loadphotos_1.0.gif' /><br />Carregando, aguarde...</div>";
	htmlContent = htmlContent + "</div>";
	htmlContent = htmlContent + "</div></div></div></div>";

	modalWindow({
		title: "Enviar fotos",
		content: htmlContent,
	    icon: ICO_ASK,
		button2:{caption:"Cancelar",action:"modalHide()"}
	});
}

/* Questiona o nome da categoria */
function checkCategoryName() {
	if ($("#cboCategory").val()=="N") {	
		var newName = prompt('Nova categoria:','');
		if ((newName) && ($.trim(newName)!="")) {
			$("#cboCategory").append("<option value='"+newName+"'>"+newName+"</option>");
			$("#cboCategory").val(newName);
			$("#dformphotos").show();
		}
	} else {
		if ($("#cboCategory").val()!="")
			$("#dformphotos").show();
	}
}

/* Salva os dados de Quem Somos */
function confirmAboutData() {
	/* Obtem os campos do formulário */
	edt_about_title = $.trim($("#edt_about_title").val());
	edt_about_text  = $.trim($("#edt_about_text").val());
	img_about = $("#img_about").attr("filename");

	if (edt_about_title=="") {
		modalWindow({
			title: "Ops!", 
			icon: ICO_ERROR,
			content: "Informe o título por favor.",
			button1:{caption:"OK",action:"modalHide()"}
		});
		return;
	}

	if (edt_about_text=="") {
		modalWindow({
			title: "Ops!", 
			icon: ICO_ERROR,
			content: "Informe o texto por favor.",
			button1:{caption:"OK",action:"modalHide()"}
		});
		return;
	}
	
	
		/* Rotina a ser executada após a alteração do nome com sucesso */
		withSuccess = function(data) {
			$("#loadingWindow").modal("hide"); /* Esconde o loading */
			/* Em caso de sucesso */
			if (data.feedback=="success") {
				/* Apenas recarrega a página para mostrar os novos dados */
				window.location = './about.php';
			} else if (data.feedback=="fail") {
				/* Retorno de falha no processamento da senha */
				modalWindow({
					title: "Ops!", 
					icon: ICO_ERROR,
					content: data.errorname,
					button1:{caption:"OK",action:"modalHide()"}
				});
			} else {
				/* Erro genérico não identificado de conexão ou servidor */
				modalWindow({
					title: "Ops!", 
					icon: ICO_ERROR,
					content: "Não foi possível gravar os dados agora.<br />Tente mais tarde por favor.",
					button1:{caption:"OK",action:"modalHide()"}
				});
			}
		}

		/* Chama o loading antes de ativar a alteração */
		loadingWindow("Processando alteração...",function(){
			/* Chama a rotina que altera o nome do usuário selecionado */
			$.ajax({
			  type: "POST",
			  url: URL_ABOUTDATA,
			  data: {"edt_about_title":edt_about_title,"edt_about_text":edt_about_text,"img_about":img_about},
			  success: withSuccess,
			  dataType: "json"
			});
		});	

}

/* Salva os dados de Quem Somos */
function confirmFrasesData() {
	/* Obtem os campos do formulário */
	edt_frases_title = $.trim($("#edt_frases_title").val());
	edt_frases_text  = $.trim($("#edt_frases_text").val());
	img_frases = $("#img_frases").attr("filename");

	if (edt_frases_title=="") {
		modalWindow({
			title: "Ops!", 
			icon: ICO_ERROR,
			content: "Informe o título por favor.",
			button1:{caption:"OK",action:"modalHide()"}
		});
		return;
	}

	if (edt_frases_text=="") {
		modalWindow({
			title: "Ops!", 
			icon: ICO_ERROR,
			content: "Informe o texto por favor.",
			button1:{caption:"OK",action:"modalHide()"}
		});
		return;
	}
	
	
		/* Rotina a ser executada após a alteração do nome com sucesso */
		withSuccess = function(data) {
			$("#loadingWindow").modal("hide"); /* Esconde o loading */
			/* Em caso de sucesso */
			if (data.feedback=="success") {
				/* Apenas recarrega a página para mostrar os novos dados */
				window.location = './frases.php';
			} else if (data.feedback=="fail") {
				/* Retorno de falha no processamento da senha */
				modalWindow({
					title: "Ops!", 
					icon: ICO_ERROR,
					content: data.errorname,
					button1:{caption:"OK",action:"modalHide()"}
				});
			} else {
				/* Erro genérico não identificado de conexão ou servidor */
				modalWindow({
					title: "Ops!", 
					icon: ICO_ERROR,
					content: "Não foi possível gravar os dados agora.<br />Tente mais tarde por favor.",
					button1:{caption:"OK",action:"modalHide()"}
				});
			}
		}

		/* Chama o loading antes de ativar a alteração */
		loadingWindow("Processando alteração...",function(){
			/* Chama a rotina que altera o nome do usuário selecionado */
			$.ajax({
			  type: "POST",
			  url: URL_FRASESDATA,
			  data: {"edt_frases_title":edt_frases_title,"edt_frases_text":edt_frases_text,"img_frases":img_frases},
			  success: withSuccess,
			  dataType: "json"
			});
		});	

}

/* Confirma e salva os dados do artigo */
function confirmArticleData() {
	/* Obtem os campos do formulário */
	edt_article_id    = $.trim($("#edt_article_id").val());
	edt_article_title = $.trim($("#edt_article_title").val());
	edt_article_text  = $.trim($("#edt_article_text").val());
	img_article       = $("#img_article").attr("filename");

	if (edt_article_title=="") {
		modalWindow({
			title: "Ops!", 
			icon: ICO_ERROR,
			content: "Informe o título por favor.",
			button1:{caption:"OK",action:"modalHide()"}
		});
		return;
	}

	if (edt_article_text=="") {
		modalWindow({
			title: "Ops!", 
			icon: ICO_ERROR,
			content: "Informe o texto por favor.",
			button1:{caption:"OK",action:"modalHide()"}
		});
		return;
	}
	
	
		/* Rotina a ser executada após a alteração do nome com sucesso */
		withSuccess = function(data) {
			$("#loadingWindow").modal("hide"); /* Esconde o loading */
			/* Em caso de sucesso */
			if (data.feedback=="success") {
				/* Apenas recarrega a página para mostrar os novos dados */
				window.location = './articles.php';
			} else if (data.feedback=="fail") {
				/* Retorno de falha no processamento da senha */
				modalWindow({
					title: "Ops!", 
					icon: ICO_ERROR,
					content: data.errorname,
					button1:{caption:"OK",action:"modalHide()"}
				});
			} else {
				/* Erro genérico não identificado de conexão ou servidor */
				modalWindow({
					title: "Ops!", 
					icon: ICO_ERROR,
					content: "Não foi possível gravar os dados agora.<br />Tente mais tarde por favor.",
					button1:{caption:"OK",action:"modalHide()"}
				});
			}
		}

		/* Chama o loading antes de ativar a alteração */
		loadingWindow("Processando alteração...",function(){
			/* Chama a rotina que altera o nome do usuário selecionado */
			$.ajax({
			  type: "POST",
			  url: URL_EDIT_ARTICLE,
			  data: {"edt_article_id":edt_article_id,"edt_article_title":edt_article_title,"edt_article_text":edt_article_text,"img_article":img_article},
			  success: withSuccess,
			  dataType: "json"
			});
		});		
}

/* Salva os dados de Quem Somos */
function confirmTipsData() {
	/* Obtem os campos do formulário */
	edt_tips_title = $.trim($("#edt_tips_title").val());
	edt_tips_text  = $.trim($("#edt_tips_text").val());
	img_tips = $("#img_tips").attr("filename");

	if (edt_tips_title=="") {
		modalWindow({
			title: "Ops!", 
			icon: ICO_ERROR,
			content: "Informe o título por favor.",
			button1:{caption:"OK",action:"modalHide()"}
		});
		return;
	}

	if (edt_tips_text=="") {
		modalWindow({
			title: "Ops!", 
			icon: ICO_ERROR,
			content: "Informe o texto por favor.",
			button1:{caption:"OK",action:"modalHide()"}
		});
		return;
	}	
	
		/* Rotina a ser executada após a alteração do nome com sucesso */
		withSuccess = function(data) {
			$("#loadingWindow").modal("hide"); /* Esconde o loading */
			/* Em caso de sucesso */
			if (data.feedback=="success") {
				/* Apenas recarrega a página para mostrar os novos dados */
				window.location = './tips.php';
			} else if (data.feedback=="fail") {
				/* Retorno de falha no processamento da senha */
				modalWindow({
					title: "Ops!", 
					icon: ICO_ERROR,
					content: data.errorname,
					button1:{caption:"OK",action:"modalHide()"}
				});
			} else {
				/* Erro genérico não identificado de conexão ou servidor */
				modalWindow({
					title: "Ops!", 
					icon: ICO_ERROR,
					content: "Não foi possível gravar os dados agora.<br />Tente mais tarde por favor.",
					button1:{caption:"OK",action:"modalHide()"}
				});
			}
		}

		/* Chama o loading antes de ativar a alteração */
		loadingWindow("Processando alteração...",function(){
			/* Chama a rotina que altera o nome do usuário selecionado */
			$.ajax({
			  type: "POST",
			  url: URL_TIPSDATA,
			  data: {"edt_tips_title":edt_tips_title,"edt_tips_text":edt_tips_text,"img_tips":img_tips},
			  success: withSuccess,
			  dataType: "json"
			});
		});	

}

/* Salva a imagem de capa do álbum */
function confirmAlbumCover(albumID, filename) {
	$("#dbuttons").hide();
	$("#ddetailloading").removeClass("oculto");

	/* Em caso de retorno com sucesso do ajax */
	withSuccess = function(data) {
		/* Em caso de sucesso */
		if (data.feedback=="success") {
			/* Retorno de falha no processamento da senha */
			$("#dbuttons").show();
			$("#ddetailloading").addClass("oculto");
			modalWindow({
				title: "Pronto.", 
				icon: ICO_WARNING,
				content: "Capa definida com sucesso.",
				button1:{caption:"OK",action:"modalHide()"}
			});
		} else if (data.feedback=="fail") {
			/* Retorno de falha no processamento da senha */
			modalWindow({
				title: "Ops!", 
				icon: ICO_ERROR,
				content: data.errorname,
				button1:{caption:"OK",action:"modalHide()"}
			});
		} else {
			/* Erro genérico não identificado de conexão ou servidor */
			modalWindow({
				title: "Ops!", 
				icon: ICO_ERROR,
				content: "Não foi possível excluir a imagem agora.<br />Tente mais tarde por favor.",
				button1:{caption:"OK",action:"modalHide()"}
			});
		}			
	};
	
	/* Chama a rotina de criação do álbum */
	$.ajax({
	  type: "POST",
	  url: URL_ALBUMCOVER,
	  data: {idalbum:albumID,filecover:filename},
	  success: withSuccess,
	  dataType: "json"
	});
}

/* Exclui a imagem e volta para a galeria */
function confirmDeletePicture(picID, albumID) {
	$("#dbuttons").hide();
	$("#ddetailloading").removeClass("oculto");
	
	/* Em caso de retorno com sucesso do ajax */
	withSuccess = function(data) {
		/* Em caso de sucesso */
		if (data.feedback=="success") {
			/* Apenas recarrega a página para atualizar os dados do usuário */
			window.location = "./album.php?idalbum="+albumID;
		} else if (data.feedback=="fail") {
			/* Retorno de falha no processamento da senha */
			modalWindow({
				title: "Ops!", 
				icon: ICO_ERROR,
				content: data.errorname,
				button1:{caption:"OK",action:"modalHide()"}
			});
		} else {
			/* Erro genérico não identificado de conexão ou servidor */
			modalWindow({
				title: "Ops!", 
				icon: ICO_ERROR,
				content: "Não foi possível excluir a imagem agora.<br />Tente mais tarde por favor.",
				button1:{caption:"OK",action:"modalHide()"}
			});
		}			
	};
	
	/* Chama a rotina de criação do álbum */
	$.ajax({
	  type: "POST",
	  url: URL_DELPICTURE,
	  data: {idpic:picID,idalbum:albumID},
	  success: withSuccess,
	  dataType: "json"
	});
}

/* Pede uma senha para exclusão do álbum */
function confirmPassDeleteAlbum(albumID) {
	htmlTitle = "Excluir álbum completo";
	htmlInput = "<div id='dConfPass' class='form-group has-feedback'><label class='control-label' for='txtConfPass'>Essa operação exige senha do administrador</label><input type='password' class='form-control' id='txtConfPass' value='' placeholder='Senha' /><span id='sConfPass' class='glyphicon glyphicon-remove form-control-feedback oculto'></span></div>";

	modalWindow({                                             
		title: htmlTitle,
		content: htmlInput,
	    icon: ICO_ASK,
		button1:{caption:"OK",action:"startDeleteAlbum("+albumID+")"},
		button2:{caption:"Cancelar",action:"modalHide()"}
	});
}

/* Seleção de especialistas de uma imagem */
function specialistSelecion(idPicAlbum, idPicSpec) {
	markeds = $("#sp_sel_"+idPicAlbum).val();
	selected = $("#img_pic_"+idPicAlbum+"_"+idPicSpec).attr("selected");
	if (selected) {
		$("#img_pic_"+idPicAlbum+"_"+idPicSpec).removeAttr("selected");
		backColor = "#fff";
		$("#sp_sel_"+idPicAlbum).val(markeds.replace("("+idPicSpec+")",""));
	} else {
		$("#img_pic_"+idPicAlbum+"_"+idPicSpec).attr("selected","true");
		backColor = "#c0c0c0";
		$("#sp_sel_"+idPicAlbum).val(markeds.concat("("+idPicSpec+")"));
	}
	$("#div_pic_"+idPicAlbum+"_"+idPicSpec).css("background-color",backColor);
}

/* Chama a edicao de um artigo */
function startArticleEdition(articleID) {
	window.location = 'editarticle.php?article_id='+articleID;
}

/* Exclui um album inteiro (inclusive as fotos) */
function startDeleteAlbum(albumID) {
	pass = $.trim($("#txtConfPass").val());
	if (pass=="") {
		$("#dConfPass").addClass("has-error");
		$("#sConfPass").removeClass("oculto");
		$("#txtConfPass").focus();
	} else {
		$("#dConfPass").removeClass("has-error");
		$("#sConfPass").addClass("oculto");
		
		/* Exibe o loading */
		modalWindowDisable();
		
		/* Em caso de retorno com sucesso do ajax */
		withSuccess = function(data) {
			/* Em caso de sucesso */
			if (data.feedback=="success") {
				/* Volta para a lista de álbuns */
				window.location = "gallery.php";
			} else if (data.feedback=="fail") {
				/* Retorno de falha no processamento da senha */
				modalWindow({
					title: "Ops!", 
					icon: ICO_ERROR,
					content: data.errorname,
					button1:{caption:"OK",action:"modalHide()"}
				});
			} else {
				/* Erro genérico não identificado de conexão ou servidor */
				modalWindow({
					title: "Ops!", 
					icon: ICO_ERROR,
					content: "Não foi possível eliminar o álbum agora.<br />Tente mais tarde por favor.",
					button1:{caption:"OK",action:"modalHide()"}
				});
			}
		};
		
		/* Chama a rotina de criação do álbum */
		$.ajax({
		  type: "POST",
		  url: URL_DELALBUM, 
		  data: {confirmpass:pass,albumid:albumID},
		  success: withSuccess,
		  dataType: "json"
		});
	}
}

/* Inicia o salvamento das configurações */
function startSaveConfig() {
	$("#btSaveConfig").addClass("oculto");
	$("#saveConfigLoading").removeClass("oculto");
	
	/* Em caso de retorno com sucesso do ajax */
	withSuccess = function(data) {
		/* Em caso de sucesso */
		if (data.feedback=="success") {
			/* Apenas recarrega a página para atualizar os dados do usuário */
			modalWindow({
				title: "Pronto.", 
				icon: ICO_WARNING,
				content: "Configurações salvas com sucesso.",
				button1:{caption:"OK",action:"window.location = './home.php';"}
			});
		} else if (data.feedback=="fail") {
			$("#btSaveConfig").removeClass("oculto");
			$("#saveConfigLoading").addClass("oculto");
			/* Retorno de falha no processamento da senha */
			modalWindow({
				title: "Ops!", 
				icon: ICO_ERROR,
				content: data.errorname,
				button1:{caption:"OK",action:"modalHide()"}
			});
		} else {
			$("#btSaveConfig").removeClass("oculto");
			$("#saveConfigLoading").addClass("oculto");
			/* Erro genérico não identificado de conexão ou servidor */
			modalWindow({
				title: "Ops!", 
				icon: ICO_ERROR,
				content: "Não foi possível alterar as configurações agora.<br />Tente mais tarde por favor.",
				button1:{caption:"OK",action:"modalHide()"}
			});
		}			
	};
	
	/* Chama a rotina de criação do álbum */
	$.ajax({
	  type: "POST",
	  url: URL_SAVECONFIG,
	  data: {maxPicSize:$("#cboMaxPictureSize").val(),maxPicWidth:$("#cboMaxPictureWidth").val(),thumbWidth:$("#cboThumbWidth").val()},
	  success: withSuccess,
	  dataType: "json"
	});
}

/* Inicia o salvamento das edições das imagens */
function startSaveEditions(albumID) {
	campos = "";
	i = 0;
	id = $("#f_idPicture_"+i).val();
	while (id > 0) {
		title   = $.trim($("#f_textPicture_"+i).val());
		desc    = $.trim($("#f_descPicture_"+i).val());
		article = $("#cboArticle_"+i).val();
		specsel = $.trim($("#sp_sel_"+id).val());

		/* Evita as quebras de linha e aspas */
		while (desc.indexOf('"') >= 0)
			desc = desc.replace('"','');

		/* Evita as quebras de linha e aspas */
		while (desc.indexOf('\n') >= 0)
			desc = desc.replace('\n','<br>');

		if (campos!="")
			campos = campos + ",";
		campos = campos + "\"idpic_"+i+"\":\""+id+"\",\"namepic_"+i+"\":\""+title+"\",\"descpic_"+i+"\":\""+desc+"\",\"specpic_"+i+"\":\""+specsel+"\",\"article_"+i+"\":\""+article+"\"";

		i++;
		id = $("#f_idPicture_"+i).val();
	}

	if (campos!="") {
		campos = "{" + campos + ",\"total\":\""+i+ "\"}";

		$("#dloadingreview").removeClass("oculto");
		$("#dsavereview").addClass("oculto");

		/* Em caso de retorno com sucesso do ajax */
		withSuccess = function(data) {
			$("#dloadingreview").addClass("oculto");
			$("#dsavereview").removeClass("oculto");

			/* Em caso de sucesso */
			if (data.feedback=="success") {
				/* Apenas recarrega a página para atualizar os dados do usuário */
				window.location = "./album.php?idalbum="+albumID;
			} else if (data.feedback=="fail") {
				/* Retorno de falha no processamento da senha */
				modalWindow({
					title: "Ops!", 
					icon: ICO_ERROR,
					content: data.errorname,
					button1:{caption:"OK",action:"modalHide()"}
				});
			} else {
				/* Erro genérico não identificado de conexão ou servidor */
				modalWindow({
					title: "Ops!", 
					icon: ICO_ERROR,
					content: "Não foi possível editar as imagens agora.<br />Tente mais tarde por favor.",
					button1:{caption:"OK",action:"modalHide()"}
				});
			}			
		};
		
		/* Chama a rotina de criação do álbum */
		$.ajax({
		  type: "POST",
		  url: URL_EDITPHOTOS,
		  data: JSON.parse(campos),
		  success: withSuccess,
		  dataType: "json"
		});
	} else {
		modalWindow({
			title: "Ops!", 
			icon: ICO_ERROR,
			content: "Nenhuma imagem para salvar.",
			button1:{caption:"OK",action:"modalHide()"}
		});
	}
}

/* Inicia a criação do novo álbum */
function startSaveAlbum(albumID) {
	var name = $.trim($("#textAlbumName").val());
	var desc = $.trim($("#textAlbumDesc").val());

	/* Valida o nome do álbum */
	if (name=="") {
		$("#dAlbumName").addClass("has-warning");
		$("#sAlbumName").removeClass("oculto");
		$("#textAlbumName").focus();
	} else {
		$("#dAlbumName").removeClass("has-warning");
		$("#sAlbumName").addClass("oculto");		
		
		/* Exibe o loading */
		modalWindowDisable();

		/* Em caso de retorno com sucesso do ajax */
		withSuccess = function(data) {
			/* Esconde o loading */	
			modalWindowEnable();

			/* Em caso de sucesso */
			if (data.feedback=="success") {
				/* Navega para a página do novo álbum a fim de iniciar o upload de imagens */
				if (albumID)
					window.location = "album.php?idalbum="+data.albumid;					
				else
					window.location = "album.php?idalbum="+data.albumid+"&startupload=1";
			} else if (data.feedback=="fail") {
				/* Retorno de falha no processamento da senha */
				modalWindow({
					title: "Ops!", 
					icon: ICO_ERROR,
					content: data.errorname,
					button1:{caption:"OK",action:"modalHide()"}
				});
			} else {
				/* Erro genérico não identificado de conexão ou servidor */
				modalWindow({
					title: "Ops!", 
					icon: ICO_ERROR,
					content: "Não foi possível criar o álbum agora.<br />Tente mais tarde por favor.",
					button1:{caption:"OK",action:"modalHide()"}
				});
			}			
		};
		
		if (!albumID)
			albumID = 0;
		
		/* Chama a rotina de criação do álbum */
		$.ajax({
		  type: "POST",
		  url: URL_NEWALBUM,
		  data: {albumname:name,albumdesc:desc,albumid:albumID},
		  success: withSuccess,
		  dataType: "json"
		});
	}
}

/* Inicia a alteração do nome do usuário */
function startSaveNewName(USER_TYPE) {
	/*modalHide();*/
	newName = $.trim($("#textNewName").val());
	if (newName == '') {
		modalWindow({                                             
			title: "Ops!",
			content: "Você não informou um nome válido.",
			icon: ICO_ERROR
		});
	} else {
		/* Rotina a ser executada após a alteração do nome com sucesso */
		withSuccess = function(data) {
			$("#loadingWindow").modal("hide"); /* Esconde o loading */
			/* Em caso de sucesso */
			if (data.feedback=="success") {
				/* Apenas recarrega a página para atualizar os dados do usuário */
				window.location = './users.php';
			} else if (data.feedback=="fail") {
				/* Retorno de falha no processamento da senha */
				modalWindow({
					title: "Ops!", 
					icon: ICO_ERROR,
					content: data.errorname,
					button1:{caption:"OK",action:"modalHide()"}
				});
			} else {
				/* Erro genérico não identificado de conexão ou servidor */
				modalWindow({
					title: "Ops!", 
					icon: ICO_ERROR,
					content: "Não foi possível alterar o nome agora.<br />Tente mais tarde por favor.",
					button1:{caption:"OK",action:"modalHide()"}
				});
			}
		}

		/* Chama o loading antes de ativar a alteração */
		loadingWindow("Processando alteração...",function(){
			/* Chama a rotina que altera o nome do usuário selecionado */
			$.ajax({
			  type: "POST",
			  url: URL_CHANGENAME,
			  data: {newname:newName,usertype:USER_TYPE},
			  success: withSuccess,
			  dataType: "json"
			});
		});	
	}
}

/* Inicia a confirmação na nova senha */
function startSaveNewPassword(USER_TYPE) {
	currentPass = $.trim($("#textCurrentPass").val());
	newPass     = $.trim($("#textNewPass").val());
	repeatPass  = $.trim($("#textRepeatPass").val());

	if ((USER_TYPE == USER_ADMIN) && (currentPass=="")) {
		$("#dCurrentPass").addClass("has-warning");
		$("#sCurrentPass").removeClass("oculto");
		$("#textCurrentPass").focus();
	} else {
		$("#dCurrentPass").removeClass("has-warning");
		$("#sCurrentPass").addClass("oculto");
		if (newPass=="") {
			$("#dNewPass").addClass("has-warning");
			$("#sNewPass").removeClass("oculto");
			$("#textNewPass").focus();
		} else {
			$("#dNewPass").removeClass("has-warning");
			$("#sNewPass").addClass("oculto");
			if (newPass != repeatPass) {
				$("#dRepeatPass").addClass("has-error");
				$("#sRepeatPass").removeClass("oculto");
				$("#textRepeatPass").focus();
			} else {
				$("#dRepeatPass").removeClass("has-error");
				$("#sRepeatPass").addClass("oculto");

				$("#modalButton1").attr("disabled","true");
				$("#modalButton2").attr("disabled","true");
				
				$("#textCurrentPass").attr("disabled","true");
				$("#textNewPass").attr("disabled","true");
				$("#textRepeatPass").attr("disabled","true");
				
				$("#sModalLoading").removeClass("oculto");
				
				/* Processa o retorno da alteração da senha */
				withSuccess = function(data) {
					/* Esconde o loading da modal */
					$("#sModalLoading").addClass("oculto");
					/* Reativa os botões da modal */
					$("#modalButton1").removeAttr("disabled");
					$("#modalButton2").removeAttr("disabled");

					/* Em caso de sucesso */
					if (data.feedback=="success") {
						/* Apenas recarrega a página para atualizar os dados do usuário */
						modalWindow({
							title: "Pronto.", 
							icon: ICO_WARNING,
							content: "Senha alterada com sucesso!",
							button1:{caption:"OK",action:"modalHide()"}
						});
					} else if (data.feedback=="fail") {
						/* Retorno de falha no processamento da senha */
						modalWindow({
							title: "Ops!", 
							icon: ICO_ERROR,
							content: data.errorname,
							button1:{caption:"OK",action:"modalHide()"}
						});
					} else {
						/* Erro genérico não identificado de conexão ou servidor */
						modalWindow({
							title: "Ops!", 
							icon: ICO_ERROR,
							content: "Não foi possível alterar o nome agora.<br />Tente mais tarde por favor.",
							button1:{caption:"OK",action:"modalHide()"}
						});
					}				
				};
				
				/* Chama a rotina que altera a senha do usuário selecionado */
				$.ajax({
				  type: "POST",
				  url: URL_CHANGEPASS,
				  data: {oldpass:currentPass,newpass:newPass,usertype:USER_TYPE},
				  success: withSuccess,
				  dataType: "json"
				});
			}
		}
	}
}

/* Incia o envio das imagens do álbum */
function startSendPhotos(albumid) {
	/* Obtém o nome da sub-categoria */
	var categoryName = $("#cboCategory").val();
	if (categoryName=="") {
		modalWindow({
				title: "Ops!",
				icon: ICO_ERROR,
				content: "Nenhuma categoria selecionada.",
				button1:{caption:"OK",action:"modalHide()"}
		});
	} else {
		/* Obtém o objeto com as fotos */
		var filePhotos = document.getElementById("f_photos");
		/* Verifica se escolheu algum arquivo */
		if (!filePhotos.files[0]) {
			modalWindow({
				title: "Ops!",
				icon: ICO_ERROR,
				content: "Nenhuma foto selecionada.",
				button1:{caption:"OK",action:"modalHide()"}
			});
		} else {
			$("#daddphoto").hide();
			$("#dloadingphotos").show();
			setTimeout(function () {
				selectedAlbum = albumid;
				uploadPhotos.open("post", URL_SENDPHOTOS+"?albumid="+albumid+"&category="+categoryName, true);
				uploadPhotos.send(new FormData(document.getElementById("fsendphotos")));
			}, 1000);
		}
	}
}

/* Inicia o envio da foto do about */
function startUploadAboutPic() {
	var file = document.getElementById("about_pic");
	if (file.files[0]) {

		sendingAboutPic = true;

		$("#div_form_pic").hide();
		$("#div_img_loading").show();

		setTimeout(function(){
			imgAboutSendInfoSucess = function (objReturn) {
				sendingAboutPic = false;

				$("#div_img_loading").hide();
				$("#div_form_pic").show();

				p = objReturn.indexOf('success:');
				if (p >= 0) {
					photoName = objReturn.substr(p+8,objReturn.length);
					$("#img_about").attr("filename",photoName);
					$("#img_about").attr("src","./img/about/"+photoName);
				} else {
					modalWindow({
						title: "Ops!",
						icon: ICO_ERROR,
						content: "Não foi possível enviar a imagem agora.",
						button1:{caption:"OK",action:"modalHide()"}
					});
				}
			};
			imgAboutSend.open("post", URL_SENDABOUTP, true);
			imgAboutSend.send(new FormData(document.getElementById("faboutpic")));
		},1000);
	}
}

/* Inicia o envio da foto do frases */
function startUploadFrasesPic() {
	var file = document.getElementById("frases_pic");
	if (file.files[0]) {

		sendingFrasesPic = true;

		$("#div_form_pic").hide();
		$("#div_img_loading").show();

		setTimeout(function(){
			imgFrasesSendInfoSucess = function (objReturn) {
				sendingFrasesPic = false;

				$("#div_img_loading").hide();
				$("#div_form_pic").show();

				p = objReturn.indexOf('success:');
				if (p >= 0) {
					photoName = objReturn.substr(p+8,objReturn.length);
					$("#img_frases").attr("filename",photoName);
					$("#img_frases").attr("src","./img/frases/"+photoName);
				} else {
					modalWindow({
						title: "Ops!",
						icon: ICO_ERROR,
						content: "Não foi possível enviar a imagem agora.",
						button1:{caption:"OK",action:"modalHide()"}
					});
				}
			};
			imgFrasesSend.open("post", URL_SENDFRASESP, true);
			imgFrasesSend.send(new FormData(document.getElementById("ffrasespic")));
		},1000);
	}
}

/* Inicia o envio da foto do artigo */
function startUploadArticlePic() {
	var file = document.getElementById("article_pic");
	if (file.files[0]) {

		sendingArticlePic = true;

		$("#div_form_pic").hide();
		$("#div_img_loading").show();

		setTimeout(function(){
			imgArticleSendInfoSucess = function (objReturn) {
				sendingArticlePic = false;

				$("#div_img_loading").hide();
				$("#div_form_pic").show();

				p = objReturn.indexOf('success:');
				if (p >= 0) {
					photoName = objReturn.substr(p+8,objReturn.length);
					$("#img_article").attr("filename",photoName);
					$("#img_article").attr("src","./img/articles/"+photoName);
				} else {
					modalWindow({
						title: "Ops!",
						icon: ICO_ERROR,
						content: "Não foi possível enviar a imagem agora.",
						button1:{caption:"OK",action:"modalHide()"}
					});
				}
			};
			imgArticleSend.open("post", URL_SENDARTICLEP, true);
			imgArticleSend.send(new FormData(document.getElementById("farticlepic")));
		},1000);
	}	
}

/* Inicia o envio da foto das dicas */
function startUploadTipsPic() {
	var file = document.getElementById("tips_pic");
	if (file.files[0]) {

		sendingTipsPic = true;

		$("#div_form_pic").hide();
		$("#div_img_loading").show();

		setTimeout(function(){
			imgTipsSendInfoSucess = function (objReturn) {
				sendingTipsPic = false;

				$("#div_img_loading").hide();
				$("#div_form_pic").show();

				p = objReturn.indexOf('success:');
				if (p >= 0) {
					photoName = objReturn.substr(p+8,objReturn.length);
					$("#img_tips").attr("filename",photoName);
					$("#img_tips").attr("src","./img/tips/"+photoName);
				} else {
					modalWindow({
						title: "Ops!",
						icon: ICO_ERROR,
						content: "Não foi possível enviar a imagem agora.",
						button1:{caption:"OK",action:"modalHide()"}
					});
				}
			};
			imgTipsSend.open("post", URL_SENDTIPSP, true);
			imgTipsSend.send(new FormData(document.getElementById("ftipspic")));
		},1000);
	}
}