<?php

/**
 * Radio checkbox fields.
 */
class HTMLRadioField extends HTMLFormField {
	/**
	 * @param array $params
	 *   In adition to the usual HTMLFormField parameters, this can take the following fields:
	 *   - flatlist: If given, the options will be displayed on a single line (wrapping to following
	 *     lines if necessary), rather than each one on a line of its own. This is desirable mostly
	 *     for very short lists of concisely labelled options.
	 */
	public function __construct( $params ) {
		parent::__construct( $params );

		if ( isset( $params['flatlist'] ) ) {
			$this->mClass .= ' mw-htmlform-flatlist';
		}
	}

	public function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );

		if ( $p !== true ) {
			return $p;
		}

		if ( !is_string( $value ) && !is_int( $value ) ) {
			return $this->msg( 'htmlform-required' );
		}

		$validOptions = HTMLFormField::flattenOptions( $this->getOptions() );

		if ( in_array( strval( $value ), $validOptions, true ) ) {
			return true;
		} else {
			return $this->msg( 'htmlform-select-badoption' );
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
	public function getInputHTML( $value ) {
		$html = $this->formatOptions( $this->getOptions(), strval( $value ) );

		return $html;
	}

	public function getInputOOUI( $value ) {
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
		] + OOUI\Element::configFromHtmlAttributes(
			$this->getAttributes( [ 'disabled', 'tabindex' ] )
		) );
	}

	public function formatOptions( $options, $value ) {
		global $wgUseMediaWikiUIEverywhere;

		$html = '';

		$attribs = $this->getAttributes( [ 'disabled', 'tabindex' ] );
		$elementFunc = [ Html::class, $this->mOptionsLabelsNotFromMessage ? 'rawElement' : 'element' ];

		# @todo Should this produce an unordered list perhaps?
		foreach ( $options as $label => $info ) {
			if ( is_array( $info ) ) {
				$html .= Html::rawElement( 'h1', [], $label ) . "\n";
				$html .= $this->formatOptions( $info, $value );
			} else {
				$id = Sanitizer::escapeIdForAttribute( $this->mID . "-$info" );
				$classes = [ 'mw-htmlform-flatlist-item' ];
				if ( $wgUseMediaWikiUIEverywhere || $this->mParent instanceof VFormHTMLForm ) {
					$classes[] = 'mw-ui-radio';
				}
				$radio = Xml::radio( $this->mName, $info, $info === $value, $attribs + [ 'id' => $id ] );
				$radio .= '&#160;' . call_user_func( $elementFunc, 'label', [ 'for' => $id ], $label );

				$html .= ' ' . Html::rawElement(
					'div',
					[ 'class' => $classes ],
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
