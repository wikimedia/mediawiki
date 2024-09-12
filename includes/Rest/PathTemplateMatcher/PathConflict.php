<?php

namespace MediaWiki\Rest\PathTemplateMatcher;

use Exception;

/**
 * @newable
 */
class PathConflict extends Exception {
	/** @var string */
	public $newTemplate;
	/** @var mixed */
	public $newUserData;
	/** @var string */
	public $existingTemplate;
	/** @var mixed */
	public $existingUserData;

	/**
	 * @stable to call
	 *
	 * @param string $template
	 * @param mixed $userData
	 * @param array $existingNode
	 */
	public function __construct( $template, $userData, $existingNode ) {
		$this->newTemplate = $template;
		$this->newUserData = $userData;
		$this->existingTemplate = $existingNode['template'];
		$this->existingUserData = $existingNode['userData'];
		parent::__construct( "Unable to add path template \"$template\" since it conflicts " .
			"with the existing template \"{$this->existingTemplate}\"" );
	}
}
