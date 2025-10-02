<?php

// phpcs:disable PSR2.Methods.MethodDeclaration.Underscore

/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\StubObject;

use MediaWiki\Context\RequestContext;
use MediaWiki\Language\Language;

/**
 * Stub object for the user language. Assigned to the $wgLang global.
 */
class StubUserLang extends StubObject {

	public function __construct() {
		parent::__construct( 'wgLang' );
	}

	/**
	 * @return Language
	 */
	public function _newObject() {
		return RequestContext::getMain()->getLanguage();
	}
}
