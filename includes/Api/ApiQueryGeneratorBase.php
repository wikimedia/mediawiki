<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

/**
 * @stable to extend
 *
 * @ingroup API
 */
abstract class ApiQueryGeneratorBase extends ApiQueryBase {

	/** @var ApiPageSet|null */
	private $mGeneratorPageSet = null;

	/**
	 * Switch this module to generator mode. By default, generator mode is
	 * switched off and the module acts like a normal query module.
	 * @since 1.21 requires pageset parameter
	 * @param ApiPageSet $generatorPageSet ApiPageSet object that the module will get
	 *        by calling getPageSet() when in generator mode.
	 */
	public function setGeneratorMode( ApiPageSet $generatorPageSet ) {
		$this->mGeneratorPageSet = $generatorPageSet;
	}

	/**
	 * Indicate whether the module is in generator mode
	 * @since 1.28
	 * @return bool
	 */
	public function isInGeneratorMode() {
		return $this->mGeneratorPageSet !== null;
	}

	/**
	 * Get the PageSet object to work on.
	 * If this module is generator, the pageSet object is different from other module's
	 * @return ApiPageSet
	 */
	protected function getPageSet() {
		return $this->mGeneratorPageSet ?? parent::getPageSet();
	}

	/**
	 * Overrides ApiBase to prepend 'g' to every generator parameter
	 * @param string $paramName Parameter name
	 * @return string Prefixed parameter name
	 */
	public function encodeParamName( $paramName ) {
		if ( $this->mGeneratorPageSet !== null ) {
			return 'g' . parent::encodeParamName( $paramName );
		} else {
			return parent::encodeParamName( $paramName );
		}
	}

	/**
	 * Overridden to set the generator param if in generator mode
	 * @param string $paramName Parameter name
	 * @param int|string|array $paramValue Parameter value
	 */
	protected function setContinueEnumParameter( $paramName, $paramValue ) {
		if ( $this->mGeneratorPageSet !== null ) {
			$this->getContinuationManager()->addGeneratorContinueParam( $this, $paramName, $paramValue );
		} else {
			parent::setContinueEnumParameter( $paramName, $paramValue );
		}
	}

	/** @inheritDoc */
	protected function getHelpFlags() {
		// Corresponding messages: api-help-flag-generator
		$flags = parent::getHelpFlags();
		$flags[] = 'generator';
		return $flags;
	}

	/**
	 * Execute this module as a generator
	 * @param ApiPageSet $resultPageSet All output should be appended to this object
	 */
	abstract public function executeGenerator( $resultPageSet );
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryGeneratorBase::class, 'ApiQueryGeneratorBase' );
