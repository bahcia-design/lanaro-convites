<?php
    /*require "./gallery/site/mainlib.php";*/
	/*include "../"; /* Biblioteca principal */

	/* Obtém os dados dos álbuns
	Campos:
		"album_id"			ID do álbum
		"album_name"		Nome do álbum
		"album_description"	Descrição do álbum
		"album_cover"		Imagem de capa do álbum
		
	Exemplo:
		$albuns = getAlbuns();
		foreach ($albuns as $album) {
			echo "<p><img src='../photos/thumb/" . $album["album_cover"] . "' /> " . $album["album_name"] . "</p>";
		}
	*/
	function getAlbuns() {
		$res = array();
		$con = new connMySQL(getDatabaseConfig());
		$res = getAllAlbuns($con);
		$con->close();
		return $res;
	}
	
	/* Obtém os dados de um álbum 
	Campos:
		"album_id"			ID do álbum
		"album_name"		Nome do álbum
		"album_description"	Descrição do álbum
		"album_cover"		Imagem de capa do álbum
	Exemplo:
		$album = getAlbum(235);
		echo "<p><img src='../photos/thumb/" . $album["album_cover"] . "' /> " . $album["album_name"] . "</p>";
	*/
	function getAlbum($idAlbum) {
		$res = array();
		$con = new connMySQL(getDatabaseConfig());
		$res = getAlbumData($con, $idAlbum);
		$con->close();
		return $res;
	}
	
	
	
	

	/* Obtém as fotos um álbum pelo ID
	Camplos:
		"pic_id"			ID do álbum
		"album_id"			ID da imagem
		"pic_name"			Nome dado pelo usuário
		"pic_filename"		Nome físico do arquivo na hospedagem
		"pic_originalname"	Nome original do arquivo enviado
	Exemplo:
		$pictures = getPictures(235);
		foreach ($pictures as $pic) {
			echo "<p><img src='../photos/thumb/" . $pic["pic_filename"] . "' /> " . $pic["pic_name"] . "</p>";
		}
	*/
	function getPictures($idAlbum) {
		$res = array();
		$con = new connMySQL(getDatabaseConfig());
		$res = getAlbumPictures($con, $idAlbum);
		$con->close();
		return $res;
	}
?>