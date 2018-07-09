<?php

namespace MediaWiki\Widget;

use OOUI\MultilineTextInputWidget;

/**
 * Widget to select multiple users.
 *
 * @copyright 2017 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license MIT
 */
class UsersMultiselectWidget extends \OOUI\Widget {

	protected $usersArray = [];
	protected $inputName = null;
	protected $inputPlaceholder = null;

	/**
	 * @param array $config Configuration options
	 *   - array $config['users'] Array of usernames to use as preset data
	 *   - array $config['placeholder'] Placeholder message for input
	 *   - array $config['name'] Name attribute (used in forms)
	 */
	public function __construct( array $config = [] ) {
		parent::__construct( $config );

		// Properties
		if ( isset( $config['default'] ) ) {
			$this->usersArray = $config['default'];
		}
		if ( isset( $config['name'] ) ) {
			$this->inputName = $config['name'];
		}
		if ( isset( $config['placeholder'] ) ) {
			$this->inputPlaceholder = $config['placeholder'];
		}

		$textarea = new MultilineTextInputWidget( [
			'name' => $this->inputName,
			'value' => implode( "\n", $this->usersArray ),
			'rows' => 10,
		] );
		$this->prependContent( $textarea );
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.UsersMultiselectWidget';
	}

	public function getConfig( &$config ) {
		if ( $this->usersArray !== null ) {
			$config['selected'] = $this->usersArray;
		}
		if ( $this->inputName !== null ) {
			$config['name'] = $this->inputName;
		}
		if ( $this->inputPlaceholder !== null ) {
			$config['placeholder'] = $this->inputPlaceholder;
		}

		$config['$overlay'] = true;
		return parent::getConfig( $config );
	}

}
