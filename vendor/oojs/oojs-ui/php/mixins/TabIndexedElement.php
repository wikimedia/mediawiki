<?php

namespace OOUI;

/**
 * Element supporting "sequential focus navigation" using the 'tabindex' attribute.
 *
 * @abstract
 */
class TabIndexedElement extends ElementMixin {
	/**
	 * Tab index value.
	 *
	 * @var number|null
	 */
	protected $tabIndex = null;

	public static $targetPropertyName = 'tabIndexed';

	/**
	 * @param Element $element Element being mixed into
	 * @param array $config Configuration options
	 * @param number|null $config['tabIndex'] Tab index value. Use 0 to use default ordering, use -1 to
	 *   prevent tab focusing, use null to suppress the `tabindex` attribute. (default: 0)
	 */
	public function __construct( Element $element, array $config = array() ) {
		// Parent constructor
		$target = isset( $config['tabIndexed'] ) ? $config['tabIndexed'] : $element;
		parent::__construct( $element, $target, $config );

		// Initialization
		$this->setTabIndex( isset( $config['tabIndex'] ) ? $config['tabIndex'] : 0 );
	}

	/**
	 * Set tab index value.
	 *
	 * @param number|null $tabIndex Tab index value or null for no tab index
	 * @chainable
	 */
	public function setTabIndex( $tabIndex ) {
		$tabIndex = is_numeric( $tabIndex ) ? $tabIndex : null;

		if ( $this->tabIndex !== $tabIndex ) {
			$this->tabIndex = $tabIndex;
			$this->updateTabIndex();
		}

		return $this;
	}

	/**
	 * Update the tabIndex attribute, in case of changes to tabIndex or disabled
	 * state.
	 *
	 * @chainable
	 */
	public function updateTabIndex() {
		$disabled = $this->element->isDisabled();
		if ( $this->tabIndex !== null ) {
			$this->target->setAttributes( array(
				// Do not index over disabled elements
				'tabindex' => $disabled ? -1 : $this->tabIndex,
				// ChromeVox and NVDA do not seem to inherit this from parent elements
				'aria-disabled' => ( $disabled ? 'true' : 'false' )
			) );
		} else {
			$this->target->removeAttributes( array( 'tabindex', 'aria-disabled' ) );
		}
		return $this;
	}

	/**
	 * Get tab index value.
	 *
	 * @return number|null Tab index value
	 */
	public function getTabIndex() {
		return $this->tabIndex;
	}

	public function getConfig( &$config ) {
		if ( $this->tabIndex !== 0 ) {
			$config['tabIndex'] = $this->tabIndex;
		}
		return parent::getConfig( $config );
	}
}
