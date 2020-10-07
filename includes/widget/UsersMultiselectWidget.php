<?php

namespace MediaWiki\Widget;

/**
 * Widget to select multiple users.
 *
 * @copyright 2017 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license MIT
 */
class UsersMultiselectWidget extends TagMultiselectWidget {
	/** @var bool */
	protected $ipAllowed;

	/** @var bool */
	protected $ipRangeAllowed;

	/** @var array */
	protected $ipRangeLimits;

	/**
	 * @param array $config Configuration options
	 * - bool $config['ipAllowed'] Accept valid IP addresses
	 * - bool $config['ipRangeAllowed'] Accept valid IP ranges
	 * - array $config['ipRangeLimits'] Maximum allowed IP range sizes
	 */
	public function __construct( array $config = [] ) {
		parent::__construct( $config );

		if ( isset( $config['ipAllowed'] ) ) {
			$this->ipAllowed = $config['ipAllowed'];
		}

		if ( isset( $config['ipRangeAllowed'] ) ) {
			$this->ipRangeAllowed = $config['ipRangeAllowed'];
		}

		if ( isset( $config['ipRangeLimits'] ) ) {
			$this->ipRangeLimits = $config['ipRangeLimits'];
		}
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.UsersMultiselectWidget';
	}

	public function getConfig( &$config ) {
		if ( $this->ipAllowed !== null ) {
			$config['ipAllowed'] = $this->ipAllowed;
		}

		if ( $this->ipRangeAllowed !== null ) {
			$config['ipRangeAllowed'] = $this->ipRangeAllowed;
		}

		if ( $this->ipRangeLimits !== null ) {
			$config['ipRangeLimits'] = $this->ipRangeLimits;
		}

		return parent::getConfig( $config );
	}

}
