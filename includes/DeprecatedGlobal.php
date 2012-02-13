<?php
/**
 * Class to allow throwing wfDeprecated warnings
 * when people use globals that we do not want them to.
 * (For example like $wgArticle)
 */

class DeprecatedGlobal extends StubObject {
        // The m's are to stay consistent with parent class.
	protected $mRealValue, $mVersion;

	function __construct( $name, $realValue, $version = false ) {
		parent::__construct( $name );
		$this->mRealValue = $realValue;
		$this->mVersion = $version;
	}

	function _newObject() {
		/* Put the caller offset for wfDeprecated as 6, as
		 * that gives the function that uses this object, since:
		 * 1 = this function ( _newObject )
		 * 2 = StubObject::_unstub
		 * 3 = StubObject::_call
		 * 4 = StubObject::__call
		 * 5 = DeprecatedGlobal::<method of global called>
		 * 6 = Actual function using the global.
		 * Of course its theoretically possible to have other call
		 * sequences for this method, but that seems to be
		 * rather unlikely.
		 */
		wfDeprecated( '$' . $this->mGlobal, $this->mVersion, false, 6 );
		return $this->mRealValue;
	}
}
