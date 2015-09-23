<?php

namespace OOUI;

/**
 * DOM element abstraction.
 *
 * @abstract
 */
class Element extends Tag {

	/* Static Properties */

	/**
	 * HTML tag name.
	 *
	 * This may be ignored if getTagName() is overridden.
	 *
	 * @var string
	 */
	public static $tagName = 'div';

	/**
	 * Default text direction, used for some layout calculations. Use setDefaultDir() to change.
	 *
	 * Currently only per-document directionality is supported.
	 *
	 * @var string
	 */
	public static $defaultDir = 'ltr';

	/* Properties */

	/**
	 * Element data.
	 *
	 * @var mixed
	 */
	protected $data = null;

	/**
	 * CSS classes explicitly configured for this element (as opposed to #$classes, which contains all
	 * classes for this element).
	 *
	 * @var string[]
	 */
	protected $ownClasses = array();

	/**
	 * Mixins.
	 *
	 * @var ElementMixin[] List mixed in objects.
	 */
	protected $mixins = array();

	/* Methods */

	/**
	 * @param array $config Configuration options
	 * @param string[] $config['classes'] CSS class names to add
	 * @param string $config['id'] HTML id attribute
	 * @param string $config['text'] Text to insert
	 * @param array $config['content'] Content to append (after text), strings
	 *   or Element objects. Strings will be HTML-escaped for output, use an
	 *   HtmlSnippet instance to prevent that.
	 * @param mixed $config['data'] Element data
	 */
	public function __construct( array $config = array() ) {
		// Parent constructor
		parent::__construct( $this->getTagName() );

		// Initialization
		if ( isset( $config['infusable'] ) && is_bool( $config['infusable'] ) ) {
			$this->setInfusable( $config['infusable'] );
		}
		if ( isset( $config['data'] ) ) {
			$this->setData( $config['data'] );
		}
		if ( isset( $config['classes'] ) && is_array( $config['classes'] ) ) {
			$this->ownClasses = $config['classes'];
			$this->addClasses( $this->ownClasses );
		}
		if ( isset( $config['id'] ) ) {
			$this->setAttributes( array( 'id' => $config['id'] ) );
		}
		if ( isset( $config['text'] ) ) {
			// JS compatibility
			$this->appendContent( $config['text'] );
		}
		if ( isset( $config['content'] ) ) {
			$this->appendContent( $config['content'] );
		}
	}

	/**
	 * Call a mixed-in method.
	 *
	 * This makes the methods of a mixin accessible through the element being mixed into.
	 *
	 * Triggers an error if the method is not found, as normal.
	 *
	 * @param string $method Method name
	 * @param array $arguments Method arguments
	 * @return mixed Result of method call
	 */
	public function __call( $method, $arguments ) {
		// Search mixins for methods
		foreach ( $this->mixins as $mixin ) {
			if ( method_exists( $mixin, $method ) ) {
				return call_user_func_array( array( $mixin, $method ), $arguments );
			}
		}
		// Fail normally
		trigger_error(
			'Call to undefined method ' . __CLASS__ . '::' . $method . '()',
			E_USER_ERROR
		);
	}

	/**
	 * Get a mixed-in target property.
	 *
	 * This makes the target of a mixin accessible through the element being mixed into.
	 *
	 * The target's property name is statically configured by the mixin class.
	 *
	 * Triggers a notice if the property is not found, as normal.
	 *
	 * @param string $name Property name
	 * @return Tag|null Target property or null if not found
	 */
	public function __get( $name ) {
		// Search mixins for the property
		foreach ( $this->mixins as $mixin ) {
			if ( isset( $mixin::$targetPropertyName ) && $mixin::$targetPropertyName === $name ) {
				return $mixin->target;
			}
		}
		// Fail normally
		trigger_error( 'Undefined property: ' . $name, E_USER_NOTICE );
		return null;
	}

	/**
	 * Check for existence of a mixed-in target property.
	 *
	 * @param string $name Property name
	 * @return bool Whether property exists
	 */
	public function __isset( $name ) {
		// Search mixins for the property
		foreach ( $this->mixins as $mixin ) {
			if ( isset( $mixin::$targetPropertyName ) && $mixin::$targetPropertyName === $name ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Get the HTML tag name.
	 *
	 * Override this method to base the result on instance information.
	 *
	 * @return string HTML tag name
	 */
	public function getTagName() {
		return $this::$tagName;
	}

	/**
	 * Get element data.
	 *
	 * @return mixed Element data
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * Set element data.
	 *
	 * @param mixed $data Element data
	 * @chainable
	 */
	public function setData( $data ) {
		$this->data = $data;
		return $this;
	}

	/**
	 * Check if element supports one or more methods.
	 *
	 * @param string|string[] $methods Method or list of methods to check
	 * @return boolean All methods are supported
	 */
	public function supports( $methods ) {
		$support = 0;
		$methods = (array)$methods;

		foreach ( $methods as $method ) {
			if ( method_exists( $this, $method ) ) {
				$support++;
				continue;
			}

			// Search mixins for methods
			foreach ( $this->mixins as $mixin ) {
				if ( method_exists( $mixin, $method ) ) {
					$support++;
					break;
				}
			}
		}

		return count( $methods ) === $support;
	}

	/**
	 * Mixin a class.
	 *
	 * @param ElementMixin $mixin Mixin object
	 */
	public function mixin( ElementMixin $mixin ) {
		$this->mixins[] = $mixin;
	}

	/**
	 * Add the necessary properties to the given `$config` array to allow
	 * reconstruction of this widget via its constructor.
	 * @param array &$config
	 *   An array which will be mutated to add the necessary configuration
	 *   properties.  Unless you are implementing a subclass, you should
	 *   always pass a new empty `array()`.
	 * @return array
	 *   A configuration array which can be passed to this object's
	 *   constructor to recreate it.  This is a return value to allow
	 *   the safe use of copy-by-value functions like `array_merge` in
	 *   the implementation.
	 */
	public function getConfig( &$config ) {
		foreach ( $this->mixins as $mixin ) {
			$config = $mixin->getConfig( $config );
		}
		if ( $this->data !== null ) {
			$config['data'] = $this->data;
		}
		if ( $this->ownClasses !== array() ) {
			$config['classes'] = $this->ownClasses;
		}
		return $config;
	}

	/**
	 * Create a modified version of the configuration array suitable for
	 * JSON serialization by replacing `Tag` references and
	 * `HtmlSnippet`s.
	 *
	 * @return array
	 *   A serialized configuration array.
	 */
	private function getSerializedConfig() {
		// Ensure that '_' comes first in the output.
		$config = array( '_' => true );
		$config = $this->getConfig( $config );
		// Post-process config array to turn Tag references into ID references
		// and HtmlSnippet references into a { html: 'string' } JSON form.
		$replaceElements = function( &$item ) {
			if ( $item instanceof Tag ) {
				$item->ensureInfusableId();
				$item = array( 'tag' => $item->getAttribute( 'id' ) );
			} elseif ( $item instanceof HtmlSnippet ) {
				$item = array( 'html' => (string) $item );
			}
		};
		array_walk_recursive( $config, $replaceElements );
		// Set '_' last to ensure that subclasses can't accidentally step on it.
		$config['_'] = $this->getJavaScriptClassName();
		return $config;
	}

	/**
	 * The class name of the JavaScript version of this widget
	 * @return string
	 */
	protected function getJavaScriptClassName() {
		return str_replace( 'OOUI\\', 'OO.ui.', get_class( $this ) );
	}

	protected function getGeneratedAttributes() {
		$attributesArray = parent::getGeneratedAttributes();
		// Add `data-ooui` attribute from serialized config array.
		if ( $this->infusable ) {
			$serialized = $this->getSerializedConfig();
			$attributesArray['data-ooui'] = json_encode( $serialized );
		}
		return $attributesArray;
	}

	/**
	 * Render element into HTML.
	 *
	 * @return string HTML serialization
	 */
	public function toString() {
		Theme::singleton()->updateElementClasses( $this );
		if ( $this->isInfusable() ) {
			$this->ensureInfusableId();
		}
		return parent::toString();
	}

	/**
	 * Get the direction of the user interface for a given element.
	 *
	 * Currently only per-document directionality is supported.
	 *
	 * @param Tag $element Element to check
	 * @return string Text direction, either 'ltr' or 'rtl'
	 */
	public static function getDir( Tag $element ) {
		return self::$defaultDir;
	}

	/**
	 * Set the default direction of the user interface.
	 *
	 * @return string Text direction, either 'ltr' or 'rtl'
	 */
	public static function setDefaultDir( $dir ) {
		self::$defaultDir = $dir === 'rtl' ? 'rtl' : 'ltr';
	}
}
