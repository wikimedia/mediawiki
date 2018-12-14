<?php

namespace MediaWiki\Widget;

/**
 * Widget to select multiple namespaces.
 *
 * @copyright 2017 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license MIT
 */
class NamespacesMultiselectWidget extends TagMultiselectWidget {

	/**
	 * @param array $config Configuration options
	 */
	public function __construct( array $config = [] ) {
		parent::__construct( $config );

		$this->addClasses( [ 'mw-widgets-namespacesMultiselectWidget' ] );
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.NamespacesMultiselectWidget';
	}

	public function getConfig( &$config ) {
		return parent::getConfig( $config );
	}

}
