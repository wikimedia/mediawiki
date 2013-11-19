<?php
/**
 * Double field with a dropdown list constructed from a system message in the format
 *     * Optgroup header
 *     ** <option value>
 *     * New Optgroup header
 * Plus a text field underneath for an additional reason.  The 'value' of the field is
 * "<select>: <extra reason>", or "<extra reason>" if nothing has been selected in the
 * select dropdown.
 * @todo FIXME: If made 'required', only the text field should be compulsory.
 */
class HTMLSelectAndOtherField extends HTMLSelectField {

	function __construct( $params ) {
		if ( array_key_exists( 'other', $params ) ) {
		} elseif ( array_key_exists( 'other-message', $params ) ) {
			$params[ 'other' ] = wfMessage( $params[ 'other-message' ] )->plain();
		} else {
			$params[ 'other' ] = null;
		}

		if ( array_key_exists( 'options', $params ) ) {
			# Options array already specified
		} elseif ( array_key_exists( 'options-message', $params ) ) {
			# Generate options array from a system message
			$params[ 'options' ] = self::parseMessage( wfMessage( $params[ 'options-message' ] )->inContentLanguage()->plain(), $params[ 'other' ] );
		} else {
			# Sulk
			throw new MWException( 'HTMLSelectAndOtherField called without any options' );
		}
		$this->mFlatOptions = self::flattenOptions( $params[ 'options' ] );

		parent::__construct( $params );
	}

	/**
	 * Build a drop-down box from a textual list.
	 *
	 * @param string $string message text
	 * @param string $otherName name of "other reason" option
	 *
	 * @return Array
	 * TODO: this is copied from Xml::listDropDown(), deprecate/avoid duplication?
	 */
	public static function parseMessage( $string, $otherName = null ) {
		if ( $otherName === null ) {
			$otherName = wfMessage( 'htmlform-selectorother-other' )->plain();
		}

		$optgroup = false;
		$options = array( $otherName => 'other' );

		foreach ( explode( "\n", $string ) as $option ) {
			$value = trim( $option );
			if ( $value == '' ) {
				continue;
			} elseif ( substr( $value, 0, 1 ) == '*' && substr( $value, 1, 1 ) != '*' ) {
				# A new group is starting...
				$value = trim( substr( $value, 1 ) );
				$optgroup = $value;
			} elseif ( substr( $value, 0, 2 ) == '**' ) {
				# groupmember
				$opt = trim( substr( $value, 2 ) );
				if ( $optgroup === false ) {
					$options[ $opt ] = $opt;
				} else {
					$options[ $optgroup ][ $opt ] = $opt;
				}
			} else {
				# groupless reason list
				$optgroup = false;
				$options[ $option ] = $option;
			}
		}

		return $options;
	}

	function getInputHTML( $value ) {
		$select = parent::getInputHTML( $value[ 1 ] );

		$textAttribs = array(
			'id' => $this->mID . '-other',
			'size' => $this->getSize(),
		);

		if ( $this->mClass !== '' ) {
			$textAttribs[ 'class' ] = $this->mClass;
		}

		foreach ( array( 'required', 'autofocus', 'multiple', 'disabled' ) as $param ) {
			if ( isset( $this->mParams[ $param ] ) ) {
				$textAttribs[ $param ] = '';
			}
		}

		$textbox = Html::input( $this->mName . '-other', $value[ 2 ], 'text', $textAttribs );

		return "$select<br />\n$textbox";
	}

	/**
	 * @param  $request WebRequest
	 *
	 * @return Array("<overall message>","<select value>","<text field value>")
	 */
	function loadDataFromRequest( $request ) {
		if ( $request->getCheck( $this->mName ) ) {

			$list = $request->getText( $this->mName );
			$text = $request->getText( $this->mName . '-other' );

			if ( $list == 'other' ) {
				$final = $text;
			} elseif ( ! in_array( $list, $this->mFlatOptions ) ) {
				# User has spoofed the select form to give an option which wasn't
				# in the original offer.  Sulk...
				$final = $text;
			} elseif ( $text == '' ) {
				$final = $list;
			} else {
				$final = $list . $this->msg( 'colon-separator' )->inContentLanguage()->text() . $text;
			}

		} else {
			$final = $this->getDefault();

			$list = 'other';
			$text = $final;
			foreach ( $this->mFlatOptions as $option ) {
				$match = $option . $this->msg( 'colon-separator' )->inContentLanguage()->text();
				if ( strpos( $text, $match ) === 0 ) {
					$list = $option;
					$text = substr( $text, strlen( $match ) );
					break;
				}
			}
		}
		return array( $final, $list, $text );
	}

	function getSize() {
		return isset( $this->mParams[ 'size' ] ) ? $this->mParams[ 'size' ] : 45;
	}

	function validate( $value, $alldata ) {
		# HTMLSelectField forces $value to be one of the options in the select
		# field, which is not useful here.  But we do want the validation further up
		# the chain
		$p = parent::validate( $value[ 1 ], $alldata );

		if ( $p !== true ) {
			return $p;
		}

		if ( isset( $this->mParams[ 'required' ] ) && $this->mParams[ 'required' ] !== false && $value[ 1 ] === '' ) {
			return $this->msg( 'htmlform-required' )->parse();
		}

		return true;
	}
}