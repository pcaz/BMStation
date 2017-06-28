<?php
namespace ClientBundle\Service;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Spipu\Html2Pdf\Html2Pdf;


class HtmlToPDF extends Extension {

	public function __construct()
	{

	}
	
	public function load(array $configs, ContainerBuilder $container)
	{
		$loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
		$loader->load('service.yml');
	}
	
	private $pdf;
	private $orientation='P';
	private $format='A4';
	private $lang='fr';
	private $unicode=true;
	private $encoding='UTF-8';
	private $margin=array(10,15,10,15);
	private $pdfa=false;
	
	
	public function create($orientation=null, $format=null, $lang=null, $unicode=null, $encoding=null, $margin=null,$pdfa=null)
	{
		
		$this->pdf=new Html2Pdf( 
				$orientation ? $orientation : $this->orientation,
				$format ? $format : $this->format,
				$lang ? $lang : $this->lang,
				$unicode ? $unicode : $this->unicode,
				$encoding ? $encoding : $this->encoding,
				$margin ? $margin : $this->margin,
				$pdfa ? $pdfa : $this->pdfa
				);
    }
    
    public function generatePDF($template, $name)
    {
    	$this->pdf->writeHTML($template);
    	return $this->pdf->Output($name.'.pdf');
    }
}