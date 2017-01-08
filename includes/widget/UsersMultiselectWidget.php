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

	/**
	 * @param array $config Configuration options
	 * @param array $config['users'] Array of usernames to use as preset data
	 * @param array $config['placeholder'] Placeholder message for input
	 * @param array $config['name'] Name attribute (used in forms)
	 */
	public function __construct( array $config = [] ) {
		// Parent constructor
		parent::__construct( $config );

		// Properties
		$this->config = $config;
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.UsersMultiselectWidget';
	}

	public function getConfig( &$config ) {
		if ( isset( $this->config['users'] ) ) {
			$config['data'] = $this->config['users'];
		}
		if ( isset( $this->config['name'] ) ) {
			$config['name'] = $this->config['name'];
		}
		if ( isset( $this->config['placeholder'] ) ) {
			$config['placeholder'] = $this->config['placeholder'];
		}

		// Because of using autocompete (constantly changing menu), we need to
		// allow adding usernames, which do not present in the menu.
		$config['allowArbitrary'] = true;
		return parent::getConfig( $config );
	}

}
