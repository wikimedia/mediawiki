<?php

namespace OOUI;

/**
 * Element with an accesskey.
 *
 * Accesskeys allow an user to go to a specific element by using
 * a shortcut combination of a browser specific keys + the key
 * set to the field.
 *
 * @abstract
 */
class AccessKeyedElement extends ElementMixin {
	/**
	 * Accesskey
	 *
	 * @var string
	 */
	protected $accessKey = null;

	public static $targetPropertyName = 'accessKeyed';

	/**
	 * @param Element $element Element being mixed into
	 * @param array $config Configuration options
	 * @param string $config['accessKey'] AccessKey. If not provided, no accesskey will be added
	 */
	public function __construct( Element $element, array $config = array() ) {
		// Parent constructor
		$target = isset( $config['accessKeyed'] ) ? $config['accessKeyed'] : $element;
		parent::__construct( $element, $target, $config );

		// Initialization
		$this->setAccessKey(
			isset( $config['accessKey'] ) ? $config['accessKey'] : null
		);
	}

	/**
	 * Set access key.
	 *
	 * @param string $accessKey Tag's access key, use empty string to remove
	 * @chainable
	 */
	public function setAccessKey( $accessKey ) {
		$accessKey = is_string( $accessKey ) && strlen( $accessKey ) ? $accessKey : null;

		if ( $this->accessKey !== $accessKey ) {
			if ( $accessKey !== null ) {
				$this->target->setAttributes( array( 'accesskey' => $accessKey ) );
			} else {
				$this->target->removeAttributes( array( 'accesskey' ) );
			}
			$this->accessKey = $accessKey;
		}

		return $this;
	}

	/**
	 * Get AccessKey.
	 *
	 * @return string Accesskey string
	 */
	public function getAccessKey() {
		return $this->accessKey;
	}

	public function getConfig( &$config ) {
		if ( $this->accessKey !== null ) {
			$config['accessKey'] = $this->accessKey;
		}
		return parent::getConfig( $config );
	}
}
