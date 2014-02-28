<?php

/**
 * A field that will contain a date
 */
class HTMLDateField extends HTMLTextField {
	function getSize() {
		return isset( $this->mParams['size'] ) ? $this->mParams['size'] : 10;
	}

	public function getAttributes( array $list ) {
		$list = array_diff( $list, array( 'min', 'max' ) );
		$ret = parent::getAttributes( $list );

		if ( in_array( 'placeholder', $list ) && !isset( $ret['placeholder'] ) ) {
			$ret['placeholder'] = $this->msg( 'htmlform-date-placeholder' )->text();
		}

		if ( in_array( 'min', $list ) && isset( $this->mParams['min'] ) ) {
			$min = $this->parseDate( $this->mParams['min'] );
			if ( $min ) {
				$ret['min'] = $this->formatDate( $min );
				// Because Html::expandAttributes filters it out
				$ret['data-min'] = $ret['min'];
			}
		}
		if ( in_array( 'max', $list ) && isset( $this->mParams['max'] ) ) {
			$max = $this->parseDate( $this->mParams['max'] );
			if ( $max ) {
				$ret['max'] = $this->formatDate( $max );
				// Because Html::expandAttributes filters it out
				$ret['data-max'] = $ret['max'];
			}
		}

		return $ret;
	}

	function loadDataFromRequest( $request ) {
		if ( !$request->getCheck( $this->mName ) ) {
			return $this->getDefault();
		}

		$value = $request->getText( $this->mName );
		$date = $this->parseDate( $value );
		return $date ? $this->formatDate( $date ) : $value;
	}

	function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );

		if ( $p !== true ) {
			return $p;
		}

		$date = $this->parseDate( $value );
		if ( !$date ) {
			return $this->msg( 'htmlform-date-invalid' )->parseAsBlock();
		}

		if ( isset( $this->mParams['min'] ) ) {
			$min = $this->parseDate( $this->mParams['min'] );
			if ( $min && $date < $min ) {
				return $this->msg( 'htmlform-date-toolow', $this->formatDate( $min ) )
					->parseAsBlock();
			}
		}

		if ( isset( $this->mParams['max'] ) ) {
			$max = $this->parseDate( $this->mParams['max'] );
			if ( $max && $date > $max ) {
				return $this->msg( 'htmlform-date-toohigh', $this->formatDate( $max ) )
					->parseAsBlock();
			}
		}

		return true;
	}

	protected function parseDate( $value ) {
		$value = trim( $value );

		/* @todo: Language should probably provide a "parseDate" method of some sort. */
		try {
			$date = new DateTime( "$value T00:00:00+0000", new DateTimeZone( 'GMT' ) );
			return $date->getTimestamp();
		} catch ( Exception $ex ) {
			return 0;
		}
	}

	protected function formatDate( $value ) {
		// For now just use Y-m-d. At some point someone may want to add a
		// config option.
		return gmdate( 'Y-m-d', $value );
	}

}
