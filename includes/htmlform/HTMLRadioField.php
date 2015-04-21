<?php

/**
 * Radio checkbox fields.
 */
class HTMLRadioField extends HTMLFormField {
	function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );

		if ( $p !== true ) {
			return $p;
		}

		if ( !is_string( $value ) && !is_int( $value ) ) {
			return false;
		}

		$validOptions = HTMLFormField::flattenOptions( $this->getOptions() );

		if ( in_array( strval( $value ), $validOptions, true ) ) {
			return true;
		} else {
			return $this->msg( 'htmlform-select-badoption' )->parse();
		}
	}

	/**
	 * This returns a block of all the radio options, in one cell.
	 * @see includes/HTMLFormField#getInputHTML()
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	function getInputHTML( $value ) {
		$html = $this->formatOptions( $this->getOptions(), strval( $value ) );

		return $html;
	}

	/**
	 * Same as getInputHTML, but returns an OOUI object.
	 * @param string $value
	 * @return OOUI\RadioSelectWidget
	 */
	// FIXME Not implemented D:
	// function getInputOOUI( $value ) {
	// 	$attribs = $this->getAttributes( array( 'disabled', 'tabindex' ) );
	// 	return new OOUI\RadioSelectWidget( array(
	// 		'items' => $this->formatOOUIOptions( $this->getOptions(), strval( $value ) ),
	// 	) + $attribs );
	// }

	/**
	 * Same as formatOptions, but returns an OOUI object.
	 * @param array options
	 * @param string $value
	 * @return array A bunch of OOUI\RadioOptionWidget objects
	 */
	function formatOOUIOptions( $options, $value ) {
		$options = $this->getOptions();
		$optwidgets = array();

		foreach ( $options as $label => $info ) {
			if ( is_array( $info ) ) {
				// TODO add H1 elements in here somehow
				$optwidgets += $this->formatOOUIOptions( $info, $value );
			} else {
				$optwidgets[] = new OOUI\RadioOptionWidget( array(
					'id' => Sanitizer::escapeId( $this->mID . "-$info" ),
					'name' => $this->mName,
					'data' => $info,
					'label' => $label,
				) );
			}
		}

		return $optwidgets;
	}

	function formatOptions( $options, $value ) {
		$html = '';

		$attribs = $this->getAttributes( array( 'disabled', 'tabindex' ) );
		$elementFunc = array( 'Html', $this->mOptionsLabelsNotFromMessage ? 'rawElement' : 'element' );

		# @todo Should this produce an unordered list perhaps?
		foreach ( $options as $label => $info ) {
			if ( is_array( $info ) ) {
				$html .= Html::rawElement( 'h1', array(), $label ) . "\n";
				$html .= $this->formatOptions( $info, $value );
			} else {
				$id = Sanitizer::escapeId( $this->mID . "-$info" );
				$radio = Xml::radio( $this->mName, $info, $info === $value, $attribs + array( 'id' => $id ) );
				$radio .= '&#160;' . call_user_func( $elementFunc, 'label', array( 'for' => $id ), $label );

				$html .= ' ' . Html::rawElement(
					'div',
					array( 'class' => 'mw-htmlform-flatlist-item mw-ui-radio' ),
					$radio
				);
			}
		}

		return $html;
	}

	protected function needsLabel() {
		return false;
	}
}
