<?php

namespace MediaWiki\Widget;

use OOUI\TextInputWidget;

/**
 * User input widget.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license MIT
 */
class UserInputWidget extends TextInputWidget {
	/** @var bool */
	protected $excludeNamed;

	/** @var bool */
	protected $excludeTemp;

	/**
	 * @param array $config Configuration options
	 */
	public function __construct( array $config = [] ) {
		parent::__construct( $config );

		if ( isset( $config['excludenamed'] ) ) {
			$this->excludeNamed = $config['excludenamed'];
		}

		if ( isset( $config['excludetemp'] ) ) {
			$this->excludeTemp = $config['excludetemp'];
		}

		// Initialization
		$this->addClasses( [ 'mw-widget-userInputWidget' ] );
	}

	/** @inheritDoc */
	protected function getJavaScriptClassName() {
		return 'mw.widgets.UserInputWidget';
	}

	/** @inheritDoc */
	public function getConfig( &$config ) {
		$config['$overlay'] = true;

		if ( $this->excludeNamed !== null ) {
			$config['excludenamed'] = $this->excludeNamed;
		}

		if ( $this->excludeTemp !== null ) {
			$config['excludetemp'] = $this->excludeTemp;
		}

		return parent::getConfig( $config );
	}
}
