<?php
/**
 * MediaWiki Widgets – UsersMultiselectWidget class.
 *
 * @copyright 2017 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
namespace MediaWiki\Widget;

/**
 * Widget to select multiple users.
 */
class UsersMultiselectWidget extends \OOUI\Widget {

	protected $usersArray = null;
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
		if ( isset( $config['users'] ) ) {
			$this->usersArray = $config['users'];
		}
		if ( isset( $config['name'] ) ) {
			$this->inputName = $config['name'];
		}
		if ( isset( $config['placeholder'] ) ) {
			$this->inputPlaceholder = $config['placeholder'];
		}
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

		// Because of using autocompete (constantly changing menu), we need to
		// allow adding usernames, which do not present in the menu.
		$config['allowArbitrary'] = true;
		return parent::getConfig( $config );
	}

}
