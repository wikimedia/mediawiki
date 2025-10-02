<?php

// phpcs:disable MediaWiki.Commenting.FunctionComment.ObjectTypeHintReturn
// phpcs:disable PSR2.Methods.MethodDeclaration.Underscore

/**
 * Delayed loading of deprecated global objects.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\StubObject;

/**
 * Class to allow throwing wfDeprecated warnings
 * when people use globals that we do not want them to.
 */
class DeprecatedGlobal extends StubObject {
	/** @var string|false */
	protected $version;

	/**
	 * @param string $name Global name
	 * @param callable|string $callback Factory function or class name to construct
	 * @param string|false $version Version global was deprecated in
	 */
	public function __construct( $name, $callback, $version = false ) {
		parent::__construct( $name, $callback );
		$this->version = $version;
	}

	/**
	 * @return object
	 */
	public function _newObject() {
		/*
		 * Put the caller offset for wfDeprecated as 6, as
		 * that gives the function that uses this object, since:
		 *
		 * 1 = this function ( _newObject )
		 * 2 = MediaWiki\StubObject\StubObject::_unstub
		 * 3 = MediaWiki\StubObject\StubObject::_call
		 * 4 = MediaWiki\StubObject\StubObject::__call
		 * 5 = MediaWiki\StubObject\DeprecatedGlobal::<method of global called>
		 * 6 = Actual function using the global.
		 * (the same applies to _get/__get or _set/__set instead of _call/__call)
		 *
		 * Of course its theoretically possible to have other call
		 * sequences for this method, but that seems to be
		 * rather unlikely.
		 */
		wfDeprecated( '$' . $this->global, $this->version, false, 6 );
		return parent::_newObject();
	}
}
