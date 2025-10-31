<?php

namespace MediaWiki\HTMLForm\Field;

use MediaWiki\Widget\ToggleSwitchWidget;

class HTMLToggleSwitchField extends HTMLCheckField {

	/**
	 * Get the OOUI version of this field.
	 *
	 * @since 1.41
	 * @param string $value
	 * @return ToggleSwitchWidget
	 */
	public function getInputOOUI( $value ) {
		if ( !empty( $this->mParams['invert'] ) ) {
			$value = !$value;
		}

		$attr = $this->getTooltipAndAccessKeyOOUI();
		$attr['id'] = $this->mID;
		$attr['name'] = $this->mName;

		$attr += \OOUI\Element::configFromHtmlAttributes(
			$this->getAttributes( [ 'disabled', 'tabindex' ] )
		);

		if ( $this->mClass !== '' ) {
			$attr['classes'] = [ $this->mClass ];
		}

		// For the underlaying CheckboxInputWidget
		$attr['selected'] = $value;
		$attr['value'] = '1';

		return new ToggleSwitchWidget( $attr );
	}

	/**
	 * @inheritDoc
	 */
	protected function shouldInfuseOOUI() {
		// Always infuse, as we want a toggle widget when JS is enabled.
		return true;
	}

	/** @inheritDoc */
	protected function getOOUIModules() {
		return [ 'mediawiki.widgets.ToggleSwitchWidget' ];
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLToggleSwitchField::class, 'HTMLToggleSwitchField' );
