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
		wfDeprecated( '$' . $this->mGlobal, $this->mVersion, false, 6 );
		return $this->mRealValue;
	}
}
