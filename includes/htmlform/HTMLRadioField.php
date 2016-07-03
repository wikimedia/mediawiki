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

	function getInputOOUI( $value ) {
		$options = [];
		foreach ( $this->getOptions() as $label => $data ) {
			$options[] = [
				'data' => $data,
				'label' => $this->mOptionsLabelsNotFromMessage ? new OOUI\HtmlSnippet( $label ) : $label,
			];
		}

		return new OOUI\RadioSelectInputWidget( [
			'name' => $this->mName,
			'id' => $this->mID,
			'value' => $value,
			'options' => $options,
			'classes' => 'mw-htmlform-flatlist-item',
		] + OOUI\Element::configFromHtmlAttributes(
			$this->getAttributes( [ 'disabled', 'tabindex' ] )
		) );
	}

	function formatOptions( $options, $value ) {
		$html = '';

		$attribs = $this->getAttributes( [ 'disabled', 'tabindex' ] );
		$elementFunc = [ 'Html', $this->mOptionsLabelsNotFromMessage ? 'rawElement' : 'element' ];

		# @todo Should this produce an unordered list perhaps?
		foreach ( $options as $label => $info ) {
			if ( is_array( $info ) ) {
				$html .= Html::rawElement( 'h1', [], $label ) . "\n";
				$html .= $this->formatOptions( $info, $value );
			} else {
				$id = Sanitizer::escapeId( $this->mID . "-$info" );
				$radio = Xml::radio( $this->mName, $info, $info === $value, $attribs + [ 'id' => $id ] );
				$radio .= '&#160;' . call_user_func( $elementFunc, 'label', [ 'for' => $id ], $label );

				$html .= ' ' . Html::rawElement(
					'div',
					[ 'class' => 'mw-htmlform-flatlist-item mw-ui-radio' ],
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
