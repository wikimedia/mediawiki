<?php
/**
 *
 *
 * Created on Sep 7, 2006
 *
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * @ingroup API
 */
abstract class ApiQueryGeneratorBase extends ApiQueryBase {

	private $mGeneratorPageSet = null;

	/**
	 * Switch this module to generator mode. By default, generator mode is
	 * switched off and the module acts like a normal query module.
	 * @since 1.21 requires pageset parameter
	 * @param ApiPageSet $generatorPageSet ApiPageSet object that the module will get
	 *        by calling getPageSet() when in generator mode.
	 */
	public function setGeneratorMode( ApiPageSet $generatorPageSet ) {
		if ( $generatorPageSet === null ) {
			ApiBase::dieDebug( __METHOD__, 'Required parameter missing - $generatorPageSet' );
		}
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
		if ( $this->mGeneratorPageSet !== null ) {
			return $this->mGeneratorPageSet;
		}

		return parent::getPageSet();
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
	 * @param string|array $paramValue Parameter value
	 */
	protected function setContinueEnumParameter( $paramName, $paramValue ) {
		if ( $this->mGeneratorPageSet !== null ) {
			$this->getContinuationManager()->addGeneratorContinueParam( $this, $paramName, $paramValue );
		} else {
			parent::setContinueEnumParameter( $paramName, $paramValue );
		}
	}

	/**
	 * @see ApiBase::getHelpFlags()
	 *
	 * Corresponding messages: api-help-flag-generator
	 */
	protected function getHelpFlags() {
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
