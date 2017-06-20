<?php
namespace AppBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\File\UploadedFile;	


/**
 * 
 * @author pascaz10
 * attention, cette classe peut générer des exceptions
 */

class Image extends Extension {

	private $targetDir;
	
	
	public function __construct($targetDir=null)
	{
		$this->targetDir = $targetDir;
	
	}
	
	public function load(array $configs, ContainerBuilder $container)
	{
		$loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
		$loader->load('service.yml');
	}
	/**
 	* uploadImage
	* 
 	* Cette function copie le fichier image dans le répertoire d'uploads
 	* 
 	* @param string $image L'image uploadée
 	* @return string $image Le nom complet de l'image copiée.
 	* @throw InvalidArgumentException Pas d'image ou le format d'image n'est pas supporté
 	*
 	*/	
 
 	public function uploadImage($image)	{
		
		$format=array('jpeg', 'png', 'gif');
	
		if (!$image && !($image instanceof UploadedFile)){
			$error="The parameter is not a file";
			throw(new InvalidArgumentException($error));
		}
	    // On vérifie l'extension du fichier (à partir du type mime)
		if(!in_array($image->guessExtension(), $format)){
			// on indique une erreur de format
			$error= "The image format ".$image->guessExtension()." is not supported";
			throw(new InvalidArgumentException($error));
		}
		// Ok, ça semble bon en ce qui concerne le format
			// On génère un nom unique avant de sauvegarder le fichier
			$fileName = md5(uniqid()).'.'.$image->guessExtension();
			// On déplace le fichier vers le répertoire Uploads
			$image->move($this->targetDir,$fileName);
		
			return $fileName;	
	}
	/**
	 * 
	 * removeImage
	 * 
	 * Cette fonction retire une image du répertoire d'upload
	 * 
	 * @param string $image Le nom du fichier (sans path)
	 * @return true si ok, false sinon
	 * @throw InvalidArgumentException Pas d'image
	 * 
	 */
	public function removeImage($image){
		if (!$image){
			$error="The parameter is null";
			throw(new InvalidArgumentException($error));
		}
		if(is_file($this->targetDir.'/'.$image)){
			return unlink($this->targetDir.'/'.$image);
		}
		return false;
	}
	
	
	/**
	 *
	 * existImage
	 * 
	 * @param string $image Le nom du fichier (sans path)
	 * @return true si ok, false sinon
	 * 
	 */
	
	public function existImage($image) {
		return is_file($this->targetDir.'/'.$image);
	}
}


