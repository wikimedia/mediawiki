<?php

/**
 * A size filter field for use on query-type special pages. It looks a bit like:
 *
 *    (o) Min size  ( ) Max size:  [       ] bytes
 *
 * Minimum size limits are represented using a positive integer, while maximum
 * size limits are represented using a negative integer.
 */
class HTMLSizeFilterField extends HTMLIntField {
	public function getSize() {
		return $this->mParams['size'] ?? 9;
	}

	public function getInputHTML( $value ) {
		$attribs = [];
		if ( !empty( $this->mParams['disabled'] ) ) {
			$attribs['disabled'] = 'disabled';
		}

		$html = Xml::radioLabel(
			$this->msg( 'minimum-size' )->text(),
			$this->mName . '-mode',
			'min',
			$this->mID . '-mode-min',
			$value >= 0,
			$attribs
		);
		$html .= "\u{00A0}" . Xml::radioLabel(
			$this->msg( 'maximum-size' )->text(),
			$this->mName . '-mode',
			'max',
			$this->mID . '-mode-max',
			$value < 0,
			$attribs
		);
		$html .= "\u{00A0}" . parent::getInputHTML( $value ? abs( $value ) : '' );
		$html .= "\u{00A0}" . $this->msg( 'pagesize' )->parse();

		return $html;
	}

	protected function getInputWidget( $params ) {
		$this->mParent->getOutput()->addModuleStyles( 'mediawiki.widgets.SizeFilterWidget.styles' );

		// negative numbers represent "max", positive numbers represent "min"
		$value = $params['value'];

		$params['value'] = $value ? abs( $value ) : '';

		return new MediaWiki\Widget\SizeFilterWidget( [
			'selectMin' => $value >= 0,
			'textinput' => $params,
			'radioselectinput' => [
				'name' => $this->mName . '-mode',
				'disabled' => !empty( $this->mParams['disabled'] ),
			],
		] );
	}

	protected function getOOUIModules() {
		return [ 'mediawiki.widgets.SizeFilterWidget' ];
	}

	/**
	 * @param WebRequest $request
	 *
	 * @return string|int
	 */
	public function loadDataFromRequest( $request ) {
		$size = $request->getInt( $this->mName );
		if ( !$size ) {
			return $this->getDefault();
		}
		$size = abs( $size );

		// negative numbers represent "max", positive numbers represent "min"
		if ( $request->getVal( $this->mName . '-mode' ) === 'max' ) {
			return -$size;
		} else {
			return $size;
		}
	}

	protected function needsLabel() {
		return false;
	}
}
