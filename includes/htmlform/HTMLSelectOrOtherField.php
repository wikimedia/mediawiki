<?php

/**
 * Select dropdown field, with an additional "other" textbox.
 */
class HTMLSelectOrOtherField extends HTMLTextField {
	function __construct( $params ) {
		if ( !in_array( 'other', $params['options'], true ) ) {
			$msg =
				isset( $params['other'] )
					? $params['other']
					: wfMessage( 'htmlform-selectorother-other' )->text();
			$params['options'][$msg] = 'other';
		}

		parent::__construct( $params );
	}

	static function forceToStringRecursive( $array ) {
		if ( is_array( $array ) ) {
			return array_map( array( __CLASS__, 'forceToStringRecursive' ), $array );
		} else {
			return strval( $array );
		}
	}

	function getInputHTML( $value ) {
		$valInSelect = false;

		if ( $value !== false ) {
			$valInSelect = in_array( $value, HTMLFormField::flattenOptions( $this->mParams['options'] ) );
		}

		$selected = $valInSelect ? $value : 'other';

		$opts = self::forceToStringRecursive( $this->mParams['options'] );

		$select = new XmlSelect( $this->mName, $this->mID, $selected );
		$select->addOptions( $opts );

		$select->setAttribute( 'class', 'mw-htmlform-select-or-other' );

		$tbAttribs = array( 'id' => $this->mID . '-other', 'size' => $this->getSize() );

		if ( !empty( $this->mParams['disabled'] ) ) {
			$select->setAttribute( 'disabled', 'disabled' );
			$tbAttribs['disabled'] = 'disabled';
		}

		$select = $select->getHTML();

		if ( isset( $this->mParams['maxlength'] ) ) {
			$tbAttribs['maxlength'] = $this->mParams['maxlength'];
		}

		if ( $this->mClass !== '' ) {
			$tbAttribs['class'] = $this->mClass;
		}

		$textbox = Html::input( $this->mName . '-other', $valInSelect ? '' : $value, 'text', $tbAttribs );

		return "$select<br />\n$textbox";
	}

	/**
	 * @param  $request WebRequest
	 *
	 * @return String
	 */
	function loadDataFromRequest( $request ) {
		if ( $request->getCheck( $this->mName ) ) {
			$val = $request->getText( $this->mName );

			if ( $val == 'other' ) {
				$val = $request->getText( $this->mName . '-other' );
			}

			return $val;
		} else {
			return $this->getDefault();
		}
	}
}
