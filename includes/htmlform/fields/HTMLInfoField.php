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

	public function getDefault() {
		$default = parent::getDefault();
		if ( $default instanceof Closure ) {
			$default = $default( $this->mParams );
		}
		return $default;
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

	/**
	 * @param mixed $value If not FieldLayout or subclass has been deprecated.
	 * @return OOUI\FieldLayout
	 * @since 1.32
	 */
	public function getOOUI( $value ) {
		if ( !empty( $this->mParams['rawrow'] ) ) {
			if ( !( $value instanceof OOUI\FieldLayout ) ) {
				wfDeprecated( __METHOD__ . ": 'default' parameter as a string when using" .
					"'rawrow' (must be a FieldLayout or subclass)", '1.32' );
			}
			return $value;
		}

		return parent::getOOUI( $value );
	}

	protected function needsLabel() {
		return false;
	}
}
