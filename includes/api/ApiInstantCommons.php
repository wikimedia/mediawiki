<?php
/**
 * @author suuch (mediawiki @ suuch . com)
 * In the public domain. At least in Ghana.
 */


if (!defined('MEDIAWIKI')) {
	// Eclipse helper - will be ignored in production
	require_once ('ApiBase.php');
}

class ApiInstantCommons extends ApiBase {
	var $arrOutput = array();
   	var $resParser;
   	var $strXmlData;
	
	public function __construct($main, $action) {
		parent :: __construct($main, $action);
	}	

	/**
	 * InstantCommons execution happens in the following steps:
	 */
	public function execute() {
		$media = $maint = $meta = null;
		extract($this->extractRequestParams());
		$data = array();
		$data = $this->fetchImage($media);		
		if($data!=NULL){
			$this->getResult()->addValue('instantcommons', 'image', $data);
		}
	}


	/**
	 * Override the parent to generate help messages for all available query modules.
	 */
	public function makeHelpMsg() {

		// Use parent to make default message for the query module
		$msg = parent :: makeHelpMsg();

		// Make sure the internal object is empty
		// (just in case a sub-module decides to optimize during instantiation)
		$this->mPageSet = null;

		$astriks = str_repeat('--- ', 8);
		$msg .= "\n$astriks InstantCommons: Prop  $astriks\n\n";
		$msg .= "\n See http://meta.wikimedia.org/wiki/InstantCommons\n\n";
		return $msg;
	}

	private function makeHelpMsgHelper($moduleList, $paramName) {

		$moduleDscriptions = array ();

		foreach ($moduleList as $moduleName => $moduleClass) {
			$msg = "* $paramName=$moduleName *";
			$module = new $moduleClass ($this, $moduleName, null);
			$msg2 = $module->makeHelpMsg();
			if ($msg2 !== false)
				$msg .= $msg2;
			if ($module instanceof ApiInstantCommonsGeneratorBase)
				$msg .= "Generator:\n  This module may be used as a generator\n";
			$moduleDscriptions[] = $msg;
		}

		return implode("\n", $moduleDscriptions);
	}

	protected function getAllowedParams() {
		return array (
			'media' => null,
			'maint' => null,
			'meta' => null,
		);
	}
	protected function getParamDescription() {
		return array (
			'media' => 'Get properties for the media',
			'maint' => 'Which maintenance actions to perform',
			'meta' => 'Which meta data to get about this site',			
		);
	}
	
	protected function getDescription() {
		return array (
			'InstantCommons API InstantCommons is an API feature of MediaWiki to ' .
			'allow the usage of any uploaded media file from the Wikimedia Commons ' .
			'in any MediaWiki installation world-wide. InstantCommons-enabled wikis ' .
			'cache Commons content so that it is only downloaded once, and subsequent ' .
			'pageviews load the locally existing copy.'
		);
	}

	protected function getExamples() {
		return array (
			'api.php?action=instantcommons&media=Image:MusekeBannerl.jpg',
			'api.php?action=instantcommons&media=Image:MusekeBannerl.jpg&maint=update', //performs update on this media
			'api.php?action=instantcommons&media=Image:MusekeBannerl.jpg&maint=delete', //performs delete on this media
			'api.php?action=instantcommons&maint=update', //TODO: performs update on all commons media
			'api.php?action=instantcommons&maint=delete', //TODO: performs delete on all commons imedia
			'api.php?action=instantcommons&maint=both', //TODO: performs update/delete on all commons media
			'api.php?action=instantcommons&maint=pending', //TODO: return a GD temp image
		);
	}

	public function getVersion() {
		$psModule = new ApiPageSet($this);
		$vers = array ();
		$vers[] = __CLASS__ . ': $Id: ApiInstantCommons.php 17074 2006-10-27 05:27:43Z suuch $';
		$vers[] = $psModule->getVersion();
		return $vers;
	}
	
	/**
	 * Fetch the media from the commons server in the background.
	 * Save it as a local media (but noting its source in the appropriate media table)
	 * @fileName is a fully qualified mediawiki object name (e.g. Image:sing.png)
	 * @return an associative array containing file properties in property=>value pairs
	 */
	public function fetchImage($fileName){		
		global $wgScriptPath;		
		$nt = Title::newFromText( $fileName );		
		if(is_object($nt)){		
			$image = new Image ($nt);			
			if($image->exists()){
				$image->url = substr(strstr($image->repo->url, $wgScriptPath), strlen($wgScriptPath)).'/'.$image->repo->getHashPath($image->name).$image->name;				
				$image->metadata = addslashes($image->metadata);				
				$ari=(array)$image;				
				//unset non-string elements		
				foreach($ari as $property=>$value){
					if(is_object($value)){
						unset($ari[$property]);
					}
				}								
				return $ari;			
			}else{
				return array('error'=>1, 'description'=>'File not found'); //file not found			
			}
		}
		else
		{
			return array('error'=>2, 'description'=>'Not a valid title'); //not a valid title			
		}			
	}
	

  
   function parse($strInputXML) {  
           $this->resParser = xml_parser_create ();
           xml_set_object($this->resParser,$this);
           xml_set_element_handler($this->resParser, "tagOpen", "tagClosed");
          
           xml_set_character_data_handler($this->resParser, "tagData");
      
           $this->strXmlData = xml_parse($this->resParser,$strInputXML );
           if(!$this->strXmlData) {
               die(sprintf("XML error: %s at line %d",
           xml_error_string(xml_get_error_code($this->resParser)),
           xml_get_current_line_number($this->resParser)));
           }
                          
           xml_parser_free($this->resParser);
          
           return $this->arrOutput;
   }
   function tagOpen($parser, $name, $attrs) {
       $tag=array("name"=>$name,"attrs"=>$attrs);
       array_push($this->arrOutput,$tag);
   }
  
   function tagData($parser, $tagData) {
       if(trim($tagData)) {
           if(isset($this->arrOutput[count($this->arrOutput)-1]['tagData'])) {
               $this->arrOutput[count($this->arrOutput)-1]['tagData'] .= $tagData;
           }
           else {
               $this->arrOutput[count($this->arrOutput)-1]['tagData'] = $tagData;
           }
       }
   }
  
   function tagClosed($parser, $name) {
       $this->arrOutput[count($this->arrOutput)-2]['children'][] = $this->arrOutput[count($this->arrOutput)-1];
       array_pop($this->arrOutput);
   }
	
}
?>
