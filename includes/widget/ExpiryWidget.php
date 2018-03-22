<?php

namespace MediaWiki\Widget;

use OOUI\Widget;

/**
 * Expiry widget.
 *
 * Allows the user to toggle between a precise time or enter a relative time,
 * regardless, the value comes in as a relative time.
 *
 * @copyright 2011-2018 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license MIT
 */
class ExpiryWidget extends Widget {

	/**
	 * @var Widget
	 */
	protected $relativeInput;

	/**
	 * @var array
	 */
	protected $config;

	/**
	 * @param Widget $relativeInput
	 * @param array $config Configuration options
	 */
	public function __construct( Widget $relativeInput, array $config = [] ) {
		global $wgExpiryWidgetNoDatePicker;

		$config['noDatePicker'] = $wgExpiryWidgetNoDatePicker;

		parent::__construct( $config );

		$this->config = $config;

		// Properties
		$this->relativeInput = $relativeInput;
		$this->relativeInput->addClasses( [ 'mw-widget-ExpiryWidget-relative' ] );

		// Initialization
		$classes = [
			'mw-widget-ExpiryWidget',
		];
		if ( $config['noDatePicker'] === false ) {
			$classes[] = 'mw-widget-ExpiryWidget-hasDatePicker';
		}
		$this
			->addClasses( $classes )
			->appendContent( $this->relativeInput );
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.ExpiryWidget';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getConfig( &$config ) {
		$config['relativeInput'] = [];
		$config['noDatePicker'] = $this->config['noDatePicker'];
		$this->relativeInput->getConfig( $config['relativeInput'] );
		return parent::getConfig( $config );
	}
}
