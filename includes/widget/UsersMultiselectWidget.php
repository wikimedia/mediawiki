<?php
/**
 * MediaWiki Widgets – UsersMultiselectWidget class.
 *
 * @copyright 2017 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
namespace MediaWiki\Widget;

use \OOUI\TextInputWidget;

/**
 * Widget to select multiple users.
 */
class UsersMultiselectWidget extends \OOUI\Widget {

	protected $usersArray = [];
	protected $inputName = null;
	protected $inputPlaceholder = null;

	/**
	 * @param array $config Configuration options
	 * @param array $config['users'] Array of usernames to use as preset data
	 * @param array $config['placeholder'] Placeholder message for input
	 * @param array $config['name'] Name attribute (used in forms)
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

		$textarea = new TextInputWidget( [
			'name' => $this->inputName,
			'multiline' => true,
			'value' => implode( "\n", $this->usersArray ),
			'rows' => 25,
		] );
		$this->prependContent( $textarea );
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.UsersMultiselectWidget';
	}

	public function getConfig( &$config ) {
		if ( $this->usersArray !== null ) {
			$config['data'] = $this->usersArray;
		}
		if ( $this->inputName !== null ) {
			$config['name'] = $this->inputName;
		}
		if ( $this->inputPlaceholder !== null ) {
			$config['placeholder'] = $this->inputPlaceholder;
		}

		return parent::getConfig( $config );
	}

}
