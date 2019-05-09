<?php

namespace MediaWiki\Rest\PathTemplateMatcher;

use Exception;

class PathConflict extends Exception {
	public $newTemplate;
	public $newUserData;
	public $existingTemplate;
	public $existingUserData;

	public function __construct( $template, $userData, $existingNode ) {
		$this->newTemplate = $template;
		$this->newUserData = $userData;
		$this->existingTemplate = $existingNode['template'];
		$this->existingUserData = $existingNode['userData'];
		parent::__construct( "Unable to add path template \"$template\" since it conflicts " .
			"with the existing template \"{$this->existingTemplate}\"" );
	}
}
