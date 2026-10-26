/**
	Scritps gerais (públicos) e de login
*/
var MESSAGE_ERROR = "divErrorBox";
var MESSAGE_INFO  = "divInfoBox";
var visibleBox    = "";
var ENTER_KEY     = 13;
var ICO_ALERT     = 0;
var ICO_WARNING   = 1;
var ICO_ASK       = 2;
var ICO_ERROR     = 3;
var TIMEOUT_MESSAGES = 400;

/* Caminhos dos web services */
var URL_LOGIN = "./ws/wslogin.php";

/* Administra os erros do Ajax */ 
var lastAjaxErrorURL = "";
var lastAjaxErrorMsg = "";

/* Controla os erros dos envios de ajax */
function ajaxErrorEvent() {
	/* Conteúdo padrão da mensagem de erro */
	errorMsg = "Não foi possível concluir a operação, tente novamente por favor.";
	errorAction = "modalHide()";
	
	/* Erro na tentativa de login: */
	if (lastAjaxErrorURL == URL_LOGIN) {
		errorMsg = "Não foi possível logar-se agora.<br />Tente mais tarde por favor.";
		errorAction = "enableLoginForm()";
	/* Erro na mudança do nome de usuário: */
	} else if (lastAjaxErrorURL == URL_CHANGENAME) {
		errorMsg = "Não foi possível alterar o nome agora.<br />Tente mais tarde por favor.";
		errorAction = "modalHide()";
	/* Erro na mudança de senha */
	} else if (lastAjaxErrorURL == URL_CHANGEPASS) {
		errorMsg = "Não foi possível alterar a senha agora.<br />Tente mais tarde por favor.";
		errorAction = "modalHide()";
	/* Erro na criação do novo álbum */
	} else if (lastAjaxErrorURL == URL_NEWALBUM) {
		errorMsg = "Não foi possível criar o novo álbum.<br />Tente mais tarde por favor.";
		errorAction = "modalHide()";
	/* Erro na criação do novo álbum */
	} else if (lastAjaxErrorURL == URL_EDITPHOTOS) {
		errorMsg = "Não foi possível editar as imagens do álbum.<br />Tente mais tarde por favor.";
		errorAction = "modalHide()";
		$("#dloadingreview").addClass("oculto");
		$("#dsavereview").removeClass("oculto");
	/* Erro na exclusão do novo álbum */
	} else if (lastAjaxErrorURL == URL_DELALBUM) {
		errorMsg = "Não foi possível eliminar o álbum agora.<br />Tente mais tarde por favor.";
		errorAction = "modalHide()";
	/* Erro na exclusão da foto */
	} else if (lastAjaxErrorURL == URL_DELPICTURE) {
		errorMsg = "Não foi possível excluir a imagem agora.<br />Tente mais tarde por favor.";
		errorAction = "modalHide()";
	/* Erro na definição de capa */
	} else if (lastAjaxErrorURL == URL_ALBUMCOVER) {
		errorMsg = "Não foi possível alterar a capa agora.<br />Tente mais tarde por favor.";
		errorAction = "modalHide()";
	/* Erro na alteração das configurações */
	} else if (lastAjaxErrorURL == URL_SAVECONFIG) {
		$("#btSaveConfig").removeClass("oculto");
		$("#saveConfigLoading").addClass("oculto");
		errorMsg = "Não foi possível alterar as configurações agora.<br />Tente mais tarde por favor.";
		errorAction = "modalHide()";
	}

	/* Joga a mensagem na tela: */
	modalWindow({
		title: "Ops! :(", 
		icon: ICO_ERROR,
		content: errorMsg,
		button1:{caption:"OK",action:errorAction}
	});
}

/* Mostra uma mensagem na página */
function alertBox(type,message) {
	show = function() {
		/* Crio esta função dinamicamente para poder reusar duas vezes mais abaixo */
		prefix = "";
		if (type == MESSAGE_ERROR) /* Se mensagem de erro adiciona o ícone de erro */
			prefix = "<span class='glyphicon glyphicon-remove-circle'></span>";

		$("#"+type).html(prefix + " " + message);
		$("#"+type).fadeIn("normal",function(){
			visibleBox = type;
		});	
	};

	if (visibleBox=="")
		/* Chama direto a função se não havia mensagem anterior a vista */
		show();
	else /* Se já havia uma mensagem visível, oculta ela primeiro */
		$("#"+visibleBox).fadeOut("normal",show);
}

/* Verifica se página possui caracterização de inicio de upload imediato */
function autoStartUpload() {
	return (window.location.search.indexOf("startupload=1") >= 0);
}

/* Ao clicar no botão de login da home page */
function buttonLoginClick(obj) {
	/* Lê a senha */
	pass = $.trim($("#fieldPassword").val());
	/* Se nada de senha, mensagem de erro */
	if (pass=="")
		alertBox(MESSAGE_ERROR,"Informe sua senha antes de continuar:");
	else {
		/* Se senha informada, mostra o loading... */
		$("#buttonLogin").html("<span class='glyphicon glyphicon-ok-circle'></span> Verificando...</button>");
		$("#buttonLogin").attr("disabled","true");
		$("#fieldPassword").attr("disabled","true");
		$("#imgLoading").removeClass("oculto");

		/* O que acontece quando chegar a resposta do servidor */
		withSuccess = function(data) {
			/* Em caso de sucesso */
			if (data.feedback=="success") {
				window.location = "./home.php";
			
				/*modalWindow({
					title: "Bem vindo", 
					icon: ICO_ALERT,
					content: "Sessão: " + data.usersession + "<br />Nome: " + data.username + "<br />Admin: " + data.useradmin,
					button1:{caption:"OK",action:"enableLoginForm()"}
				});*/
		
			} else if (data.feedback=="fail") {
				/* Retorno de falha no processamento da senha */
				modalWindow({
					title: "Ops!", 
					icon: ICO_ERROR,
					content: data.errorname,
					button1:{caption:"OK",action:"enableLoginForm()"}
				});
			} else {
				/* Erro genérico não identificado de conexão ou servidor */
				modalWindow({
					title: "Ops!", 
					icon: ICO_ERROR,
					content: "Não foi possível logar-se agora.<br />Tente mais tarde por favor.",
					button1:{caption:"OK",action:"enableLoginForm()"}
				});
			}
		};

		/* Chama a rotina que realiza o login no servidor */
		$.ajax({
		  type: "POST",
		  url: URL_LOGIN,
		  data: {ps:pass},
		  success: withSuccess,
		  dataType: "json"
		});
	}
}

/* Prepara o form de login para novo preenchimento */
function enableLoginForm() {
	modalHide();
	$("#buttonLogin").removeAttr("disabled");
	$("#fieldPassword").removeAttr("disabled");
	$("#fieldPassword").val("");
	$("#buttonLogin").html("<span class='glyphicon glyphicon-ok-circle'></span> Entrar");
	$("#imgLoading").addClass("oculto");
	$("#fieldPassword").focus();
}

/* Extrai o valor de um campo da query da url da pagina */
function extractGetFieldURL(fieldName) {
	content = "";
	queryString = window.location.search;
	p = queryString.indexOf(fieldName);
	if (p > 0) {
		content = queryString.substring(p+fieldName.length+1,queryString.length);
		p = content.indexOf("&");
		if (p >= 0) {
			content = content.substring(0,p);
		}
	}
	return content;
}

/* Exibe ou oculta um conteúdo de ajuda */
function helpDisplay(obj) {
	myStatus = $("#"+$(obj).attr("for")).attr("display");
	if (myStatus=="false") {
		$("#"+$(obj).attr("for")).slideDown();
		$("#"+$(obj).attr("for")).attr("display","true");
	} else {
		$("#"+$(obj).attr("for")).slideUp();
		$("#"+$(obj).attr("for")).attr("display","false");
	}
}

/* Esconde a janela modal */
function modalHide() {
	$("#modalWindow").modal("hide");
}

/* Informa se é a home page do site */
function homePage() {
	return ((window.location.pathname=="/gallery/") || (window.location.pathname=="/gallery/site/") || (window.location.pathname=="/") || (window.location.pathname.indexOf("index.php") >= 0));
}

/* Mostra uma tela de carregamento */
function loadingWindow(title, whenReady) {
	/* O setTimeout se faz necessário para dar tempo do modal atual sumir antes de novo processamento */
	modalHide();
	$("#loadingTitle").html(title);
	$("#loadingWindow").modal({backdrop: "static"});
	$("#loadingWindow").on('shown.bs.modal', whenReady);
	
/*	setTimeout(function() {
		$("#loadingTitle").html(title);
		$("#loadingWindow").modal({backdrop: "static"});
		setTimeout(whenReady, TIMEOUT_MESSAGES);
	 }, TIMEOUT_MESSAGES); */
}

/* Desativa os botões da janela modal e exibe um loading */
function modalWindowDisable() {
	/* Exibe o loading da modal */
	$("#sModalLoading").removeClass("oculto");
	/* Desativa os botões da modal */
	$("#modalButton1").attr("disabled","true");
	$("#modalButton2").attr("disabled","true");
	$("#modalButton3").attr("disabled","true");
}

/* Ativa os botões da janela modal e exibe um loading */
function modalWindowEnable() {
	/* Exibe o loading da modal */
	$("#sModalLoading").addClass("oculto");
	/* Desativa os botões da modal */
	$("#modalButton1").removeAttr("disabled");
	$("#modalButton2").removeAttr("disabled");
	$("#modalButton3").removeAttr("disabled");
}

/* Abre uma janela modal para caixas de diálogo ou outras exibições */
function modalWindow(params) {
	/*** Exemplo de uso *******************************************/
	/*	modalWindow({                                             */
	/*		title: "Testing",                                     */
	/*		content: "Estou testando...",                         */
	/*      icon: ICO_WARNING,                                    */
	/*		button1:{caption:"Confirma",action:"alert('batata')"} */
	/*	});                                                       */
	/**************************************************************/


	/* Estas verificações impedem o erro de 'undefined': */
	/* Título da janela */
	title = (params.title) ? params.title : "";
	/* Conteúdo HTML da janela */
	content = (params.content) ? params.content : "";
	/* Se usuário pode fechar a janela clicando fora do modal */
	canclose = (params.canClose) ? true : "static";
	/* Icone escolhido para o titulo da janela */
	icon = (params.icon) ? params.icon : "";
	
	/* Traduz o glyphicon necessário para o icone escolhido */
	iconHTML = "";
	if (icon == ICO_ALERT)
		iconHTML = "<span class='glyphicon glyphicon-info-sign'></span> ";
	else if (icon == ICO_WARNING)
		iconHTML = "<span class='glyphicon glyphicon-exclamation-sign'></span> ";
	else if (icon == ICO_ASK)
		iconHTML = "<span class='glyphicon glyphicon-question-sign'></span> ";
	else if (icon == ICO_ERROR)
		iconHTML = "<span class='glyphicon glyphicon-remove-sign'></span> ";
	
	/* Adiciona os conteúdos ao html do modal */
	$("#modalTitle").html(iconHTML + title);
	$("#modalContent").html(content);
	
	/* Configura os botões de ação */
	if (params.button1) {
		$("#modalButton1").html(params.button1.caption);
		$("#modalButton1").attr("onclick",params.button1.action);
		$("#modalButton1").removeClass("oculto");
	} else {
		$("#modalButton1").addClass("oculto");
	}
	if (params.button2) {
		$("#modalButton2").html(params.button2.caption);
		$("#modalButton2").attr("onclick",params.button2.action);
		$("#modalButton2").removeClass("oculto");
	} else {
		$("#modalButton2").addClass("oculto");
	}
	if (params.button3) {
		$("#modalButton3").html(params.button3.caption);
		$("#modalButton3").attr("onclick",params.button3.action);
		$("#modalButton3").removeClass("oculto");
	} else {
		$("#modalButton3").addClass("oculto");
	}
	
	/* Se nenhum botão foi definido, aciona por default um botão "Ok" para fechar a tela */
	if ((!params.button1) && (!params.button2) && (!params.button3)) {
		$("#modalButton1").html("OK");
		$("#modalButton1").attr("onclick","modalHide()");
		$("#modalButton1").removeClass("oculto");
	}


	/* Esconde o loading do modal */
	$("#sModalLoading").addClass("oculto");
	/* Reativa os botões da modal */
	$("#modalButton1").removeAttr("disabled");
	$("#modalButton2").removeAttr("disabled");
	$("#modalButton3").removeAttr("disabled");
	/* Apresenta o modal */
	$("#modalWindow").modal({backdrop: canclose});
}

/* Liga os eventos da página */
function prepareEvents() {
	/* Eventos da home page (login) */
	if (homePage()) {
		/* Botão de login da home */
		$("#buttonLogin").click(buttonLoginClick);
		/* Ao teclar ENTER no campo de senha */
		$("#fieldPassword").keydown(function(event){
			if (event.which == ENTER_KEY) {
				event.preventDefault();
				buttonLoginClick();
			}
		});
	}
}

/* Admnistra os erros do ajax */
$(document).ajaxError(function( event, jqxhr, settings, thrownError) {
	lastAjaxErrorURL = settings.url;
	lastAjaxErrorMsg = thrownError;
	ajaxErrorEvent();
});

/* Inicia os scripts da página */
$(document).ready(function(){
	prepareEvents(); /* Liga os eventos */
	if (homePage()) {/* Se home, foca o campo da senha */
		visibleBox = MESSAGE_INFO;
		$("#fieldPassword").focus();
	} else if (autoStartUpload()) {
		setTimeout(function(){
			askSendPictures(extractGetFieldURL("idalbum"));
		},500);
	}
});