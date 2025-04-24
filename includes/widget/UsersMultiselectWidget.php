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

	/** @var bool */
	protected $excludeNamed;

	/** @var bool */
	protected $excludeTemp;

	/**
	 * @param array $config Configuration options
	 * - bool $config['ipAllowed'] Accept valid IP addresses
	 * - bool $config['ipRangeAllowed'] Accept valid IP ranges
	 * - array $config['ipRangeLimits'] Maximum allowed IP range sizes
	 * - bool $config['excludeNamed'] Exclude named accounts
	 * - bool $config['excludeTemp'] Exclude temporary accounts
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

		if ( isset( $config['excludeNamed'] ) ) {
			$this->excludeNamed = $config['excludeNamed'];
		}

		if ( isset( $config['excludeTemp'] ) ) {
			$this->excludeTemp = $config['excludeTemp'];
		}
	}

	/** @inheritDoc */
	protected function getJavaScriptClassName() {
		return 'mw.widgets.UsersMultiselectWidget';
	}

	/** @inheritDoc */
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

		if ( $this->excludeNamed !== null ) {
			$config['excludeNamed'] = $this->excludeNamed;
		}

		if ( $this->excludeTemp !== null ) {
			$config['excludeTemp'] = $this->excludeTemp;
		}

		return parent::getConfig( $config );
	}

}
