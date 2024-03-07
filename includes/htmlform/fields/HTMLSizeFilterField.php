<?php

namespace MediaWiki\HTMLForm\Field;

use MediaWiki\Request\WebRequest;
use MediaWiki\Widget\SizeFilterWidget;
use Xml;

/**
 * A size filter field for use on query-type special pages. It looks a bit like:
 *
 *    (o) Min size  ( ) Max size:  [       ] bytes
 *
 * Minimum size limits are represented using a positive integer, while maximum
 * size limits are represented using a negative integer.
 *
 * @stable to extend
 *
 */
class HTMLSizeFilterField extends HTMLIntField {

	protected bool $mSelectMin = true;

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
			$this->mSelectMin,
			$attribs
		);
		$html .= "\u{00A0}" . Xml::radioLabel(
			$this->msg( 'maximum-size' )->text(),
			$this->mName . '-mode',
			'max',
			$this->mID . '-mode-max',
			!$this->mSelectMin,
			$attribs
		);
		$html .= "\u{00A0}" . parent::getInputHTML( $value ? abs( $value ) : '' );
		$html .= "\u{00A0}" . $this->msg( 'pagesize' )->parse();

		return $html;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	protected function getInputWidget( $params ) {
		$this->mParent->getOutput()->addModuleStyles( 'mediawiki.widgets.SizeFilterWidget.styles' );

		// negative numbers represent "max", positive numbers represent "min"
		$value = $params['value'];
		$params['value'] = $value ? abs( $value ) : '';

		return new SizeFilterWidget( [
			'selectMin' => $this->mSelectMin,
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
	 * @return int
	 */
	public function loadDataFromRequest( $request ) {
		$size = abs( $request->getInt( $this->mName, $this->getDefault() ) );

		// negative numbers represent "max", positive numbers represent "min"
		if ( $request->getVal( $this->mName . '-mode' ) === 'max' ) {
			$this->mSelectMin = false;
			return -$size;
		} else {
			return $size;
		}
	}

	protected function needsLabel() {
		return false;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLSizeFilterField::class, 'HTMLSizeFilterField' );
