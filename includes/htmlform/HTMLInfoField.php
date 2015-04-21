<?php

/**
 * An information field (text blob), not a proper input.
 */
class HTMLInfoField extends HTMLFormField {
	public function __construct( $info ) {
		$info['nodata'] = true;

		parent::__construct( $info );
	}

	public function getInputHTML( $value ) {
		return !empty( $this->mParams['raw'] ) ? $value : htmlspecialchars( $value );
	}

	public function getInputOOUI( $value ) {
		if ( !empty( $this->mParams['raw'] ) ) {
			$value = new OOUI\HtmlSnippet( $value );
		}

		return new OOUI\LabelWidget( array(
			'label' => $value,
		) );
	}

	public function getTableRow( $value ) {
		if ( !empty( $this->mParams['rawrow'] ) ) {
			return $value;
		}

		return parent::getTableRow( $value );
	}

	/**
	 * @param string $value
	 * @return string
	 * @since 1.20
	 */
	public function getDiv( $value ) {
		if ( !empty( $this->mParams['rawrow'] ) ) {
			return $value;
		}

		return parent::getDiv( $value );
	}

	/**
	 * @param string $value
	 * @return string
	 * @since 1.20
	 */
	public function getRaw( $value ) {
		if ( !empty( $this->mParams['rawrow'] ) ) {
			return $value;
		}

		return parent::getRaw( $value );
	}

	protected function needsLabel() {
		return false;
	}
}
