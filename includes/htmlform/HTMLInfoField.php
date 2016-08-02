<?php

/**
 * An information field (text blob), not a proper input.
 */
class HTMLInfoField extends HTMLFormField {
	/**
	 * @param array $info
	 *   In adition to the usual HTMLFormField parameters, this can take the following fields:
	 *   - default: the value (text) of the field. Unlike other form field types, HTMLInfoField can
	 *     take a closure as a default value, which will be evaluated with $info as its only parameter.
	 *   - raw: if true, the value won't be escaped.
	 *   - rawrow: if true, the usual wrapping of form fields (e.g. into a table row + cell when
	 *     display mode is table) will not happen and the value must contain it already.
	 */
	public function __construct( $info ) {
		$info['nodata'] = true;

		parent::__construct( $info );
	}

	function getDefault() {
		if ( isset( $this->mDefault ) ) {
			if ( $this->mDefault instanceof Closure ) {
				return call_user_func( $this->mDefault, $this->mParams );
			} else {
				return $this->mDefault;
			}
		} else {
			return null;
		}
	}

	public function getInputHTML( $value ) {
		return !empty( $this->mParams['raw'] ) ? $value : htmlspecialchars( $value );
	}

	public function getInputOOUI( $value ) {
		if ( !empty( $this->mParams['raw'] ) ) {
			$value = new OOUI\HtmlSnippet( $value );
		}

		return new OOUI\LabelWidget( [
			'label' => $value,
		] );
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
