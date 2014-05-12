<?php
namespace Becee\Models;
class FilesManager
{
	private $pdo = NULL;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

    public function uploadImage($file, $filename, $foldername, $max_size="900000")
    {
		$folder = '../media/upload/'. $foldername . '/';
		$taille = filesize($file['tmp_name']);
		$extensions = array('.png', '.gif', '.jpg', '.jpeg');
		$extension = strrchr($file['name'], '.'); 

		if(!in_array($extension, $extensions)) 
		{
		     $erreur = '<br/>Unsupported file format ('.$extension.$filename.$foldername.') <br/>';
		}
		if($taille>$max_size)
		{
		     $erreur = '<br/>File is too big <br/>';
		}
		if(!isset($erreur)) 
		{

		     $filename = strtr($filename, 
		          'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
		          'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
		     $filename = preg_replace('/([^.a-z0-9]+)/i', '-', $filename);
		     if (imagepng(imagecreatefromstring(file_get_contents($file['tmp_name'])), $filename . '.png')) { 
			     if(move_uploaded_file($file['tmp_name'], getcwd() . '/' . $folder . $filename . '.png'))
			     {
			          echo '<br/>Upload completed successfully ! <br/>';
			          return 'upload/' . $foldername . '/' . $filename . '.png';
			     }
			}
		     echo '<br/>Upload failed ! <br/>';
		}
		else
		{
		     echo $erreur;
		}
		echo "<br/>Default image will be set. You'll have to retry in your manager panel. ";
	}

}