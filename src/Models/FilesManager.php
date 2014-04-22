<?php
namespace Becee\Models;
class FilesManager
{
	private $pdo = NULL;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

    public function uploadImage($file, $foldername, $max_size="900000")
    {
		$folder = '../media/upload/'. $foldername . '/';
		$filename = basename($file['name']);
		$taille = filesize($file['tmp_name']);
		$extensions = array('.png', '.gif', '.jpg', '.jpeg');
		$extension = strrchr($file['name'], '.'); 
		//Début des vérifications de sécurité...
		if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
		{
		     $erreur = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg, txt ou doc...';
		}
		if($taille>$max_size)
		{
		     $erreur = 'Le fichier est trop gros...';
		}
		if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
		{
		     //On formate le nom du fichier ici...
		     $filename = strtr($filename, 
		          'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
		          'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
		     $filename = preg_replace('/([^.a-z0-9]+)/i', '-', $filename);
		     if(move_uploaded_file($file['tmp_name'], getcwd() . '/' . $folder . $filename)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
		     {
		          echo 'Upload completed successfully ! FROM: ',$file['tmp_name'], ' TO: ', $folder . $filename;
		          return $folder . $filename;
		     }
		     else //Sinon (la fonction renvoie FALSE).
		     {
		          echo 'Upload failed !';
		     }
		}
		else
		{
		     echo $erreur;
		}
	}

}
