<?php

namespace MediaWiki\Widget;

/**
 * Widget to select multiple users.
 *
 * @copyright 2017 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license MIT
 */
class UsersMultiselectWidget extends TagMultiselectWidget {

	/**
	 * @param array $config Configuration options
	 */
	public function __construct( array $config = [] ) {
		parent::__construct( $config );
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.UsersMultiselectWidget';
	}

	public function getConfig( &$config ) {
		return parent::getConfig( $config );
	}

}
