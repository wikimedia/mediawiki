<?php

/**
 * A field that will contain a numeric value
 *
 * @stable to extend
 */
class HTMLFloatField extends HTMLTextField {
	public function getSize() {
		return $this->mParams['size'] ?? 20;
	}

	public function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );

		if ( $p !== true ) {
			return $p;
		}

		$value = trim( $value );

		# https://www.w3.org/TR/html5/infrastructure.html#floating-point-numbers
		# with the addition that a leading '+' sign is ok.
		if ( !preg_match( '/^((\+|\-)?\d+(\.\d+)?(E(\+|\-)?\d+)?)?$/i', $value ) ) {
			return $this->msg( 'htmlform-float-invalid' );
		}

		# The "int" part of these message names is rather confusing.
		# They make equal sense for all numbers.
		if ( isset( $this->mParams['min'] ) ) {
			$min = $this->mParams['min'];

			if ( $min > $value ) {
				return $this->msg( 'htmlform-int-toolow', $min );
			}
		}

		if ( isset( $this->mParams['max'] ) ) {
			$max = $this->mParams['max'];

			if ( $max < $value ) {
				return $this->msg( 'htmlform-int-toohigh', $max );
			}
		}

		return true;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	protected function getInputWidget( $params ) {
		return new OOUI\NumberInputWidget( $params );
	}
}
