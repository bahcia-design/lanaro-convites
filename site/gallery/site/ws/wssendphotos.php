<?php
	/*@header("Content-Type: text/html; charset=UTF-8");*/

	@session_start();
	require_once("../mainlib.php");
	
	function salvaFotoEnvio($arquivo, $i, $pasta, $albumID, $categoryName, $dbConnection, $uploadConfig) {
		$res = array("id"=>0,"filename"=>"","error"=>"");
		if (isset($arquivo["name"][$i])&&($arquivo["name"][$i]!="")) {
			$maxFileSize = ($uploadConfig["max_pic_file_size"] * 1024 * 1024);

			/* Tamanho em megas do arquivo */
			if($arquivo["size"][$i] <= $maxFileSize) {
				/* Para verificar as dimensões da imagem*/
				$tamanhos = getimagesize($arquivo["tmp_name"][$i]);
				if ($tamanhos[0] <= $uploadConfig["max_pic_width"]) {
					if ($tamanhos[1] <= $uploadConfig["max_pic_height"]) {
						/* Pega extensão do arquivo*/
						preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $arquivo["name"][$i], $ext);

						/* Gera um nome único para a imagem*/
						$filename = md5(uniqid(time())) . strtolower($ext[0]);

						/* Caminho de onde a imagem ficará*/
						$pasta = getcwd() . "/../" . $pasta . "/";
						$pasamin = $pasta . "thumb/";
						$imagem_dir = $pasta . $filename;
						
						/* Checa se pasta destino existe:*/
						if (is_dir($pasta)) {
							/* Checa se img upload existe */
							if (file_exists($arquivo["tmp_name"][$i])) {
								/* Faz o upload da imagem*/
								move_uploaded_file($arquivo["tmp_name"][$i], $imagem_dir);

								/* Verifica se conseguiu copiar a imagem com sucesso */
								if (file_exists($imagem_dir)) {
									/* Trabalha a miniatura da imagem (se jpg) */
									if (strpos($imagem_dir,".jpg")) {
										list($larg_orig, $alt_orig) = getimagesize($imagem_dir);
										$maxdim = $uploadConfig["pic_thumb_width"];

										/*
											Obtem um corte sempre quadrado e centralizado da imagem para as miniaturas
										*/
										
										$dst_x = 0;
										$dst_y = 0;
										$src_x = 0;
										$src_y = 0;
										
										$dst_dim = $larg_orig;
										
										if ($larg_orig > $alt_orig) {
											$src_x = floor(($larg_orig - $alt_orig) / 2);
											$dst_dim = $alt_orig;
										} else if ($alt_orig > $larg_orig) {
											$src_y = floor(($alt_orig - $larg_orig) / 2);
											$dst_dim = $larg_orig;
										}
										
										/*$razao_orig = $larg_orig/$alt_orig;
										if($razao_orig < 1)
										{
											$larg = $maxdim * $razao_orig;
											$alt  = $maxdim;
										} 
										else 
										{
											$alt  = $maxdim / $razao_orig;
											$larg = $maxdim;
										}
										$imagem_nova = imagecreatetruecolor($larg, $alt);
										$imagem = imagecreatefromjpeg($imagem_dir);
										imagecopyresampled($imagem_nova, $imagem, 0, 0, 0, 0, $larg, $alt, $larg_orig, $alt_orig);*/
										
										$imagem_nova = imagecreatetruecolor($maxdim, $maxdim);
										$imagem = imagecreatefromjpeg($imagem_dir);
										imagecopyresampled($imagem_nova, $imagem, $dst_x, $dst_y, $src_x, $src_y, $maxdim, $maxdim, $dst_dim, $dst_dim);
										imagejpeg($imagem_nova, $pasamin . $filename);
									} else {
										/* Se não for jpg, apenas copia a imagem original */
										copy($imagem_dir, $pasamin . $filename);
									}
								
									/* Salva o registro no banco de dados*/
									$res["id"] = insertNewPicture($dbConnection, $albumID, $filename, $arquivo["name"][$i], $categoryName);
									$res["filename"] = $filename;
								}
							} else {
								$dbConnection->close();
								$res["error"]=$arquivo["name"][$i]." não foi encontrado.";
								/*die("erro:".$arquivo["name"][$i]." não foi encontrado.");*/
							}
						} else {
							$dbConnection->close();
							$res["error"]="$pasta não existe";
							/*die("erro:$pasta não existe.");*/
						}
					} else {
						$res["error"]="Altura maior que " . $uploadConfig["max_pic_height"] . " em " . $arquivo["name"][$i];
					}
				} else {
					$res["error"]="Largura maior que " . $uploadConfig["max_pic_width"] . " em " . $arquivo["name"][$i];
				}
			} else {
				$res["error"]="Tamanho maior que " . $uploadConfig["max_pic_file_size"] . "mb em " . $arquivo["name"][$i];
			}
		}
		return $res;
	}	
	
	/* Conexão com Banco de Dados */
	$dbConnection = new connMySQL(getDatabaseConfig());
	/* Dados do usuário logado */
	$userData = getLoggedUserData($dbConnection);
	/* Se achou o usuário continua: */
	if ($userData["user_name"]!="") {
		$pasta = "photos";
		$albumID = getField("albumid");
		$categoryName = getField("category");
		$errors = "";
		
		/* Dados do álbum */
		$albumData = getAlbumData($dbConnection, $albumID);
		
		/* Verifica se achou o álbum corretamente */
		if ($albumData["album_id"] > 0) {
			/* Obtem as configurações de upload e redimensionamento */
			$uploadConfig = getAllConfigs($dbConnection);

			$res = "";
			$i = 0;
			$c = 0;
			$primeira = "";
			
			while (isset($_FILES["f_photos"]) && isset($_FILES["f_photos"]["name"]) && isset($_FILES["f_photos"]["name"][$i])) {
				$f = salvaFotoEnvio($_FILES["f_photos"], $i, $pasta, $albumID, $categoryName, $dbConnection, $uploadConfig);
				if ($f["id"] > 0) {
					if ($primeira=="")
						$primeira = $f["filename"];

					if ($res!="")
						$res.=",";

					$res .= $f["id"];
					$c++;
				} else {
					if ($f["error"]=="")
						$f["error"] = "Erro indefinido com " . $_FILES["f_photos"]["name"][$i];
					
					if ($errors!="")
						$errors .= "|";
					$errors .= $f["error"];
				}
				$i++;
			}

			/* Verifica se atualiza a capa do album */
			if (($primeira != "") && ($albumData["album_cover"]==""))
				updateAlbumCover($dbConnection, $albumID, $primeira);

			$dbConnection->close();

			/* Verifica se houve sucesso no upload de alguma imagem: */
			if ($res!="") {
				$res = "success:".$res."";
				die($res);
			} else {
				if ($errors=="")
					$errors = "Erro indefinido. Verifique as configurações do PHP de sua hospedagem. <a href='./phpconf.php' target='_blank'>Clique aqui</a> para acessar as configurações.";
				die("erro:Houve algum problema no envio das imagens. $errors");
			}
		} else {
			$dbConnection->close();
			die("erro:Album inválido");
		}
	} else {
		$dbConnection->close();
		die("erro:Falha de login");
	}
?>