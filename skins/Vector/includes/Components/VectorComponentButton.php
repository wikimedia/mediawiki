<?php
namespace MediaWiki\Skins\Vector\Components;

/**
 * VectorSearchBox component
 */
class VectorComponentButton implements VectorComponent {
	/** @var string */
	private $label;
	/** @var string|null */
	private $icon;
	/** @var string|null */
	private $id;
	/** @var string|null */
	private $class;
	/** @var array|null */
	private $attributes;
	/** @var string|null */
	private $weight;
	/** @var string|null */
	private $action;
	/** @var bool|null */
	private $iconOnly;
	/** @var string|null */
	private $href;

	/**
	 * @param string $label
	 * @param string|null $icon
	 * @param string|null $id
	 * @param string|null $class
	 * @param array|null $attributes
	 * @param string|null $weight
	 * @param string|null $action
	 * @param bool|null $iconOnly
	 * @param string|null $href
	 */
	public function __construct(
		string $label,
		$icon = null,
		$id = null,
		$class = null,
		$attributes = [],
		$weight = 'normal',
		$action = 'default',
		$iconOnly = false,
		$href = null
	) {
		$this->label = $label;
		$this->icon = $icon;
		$this->id = $id;
		$this->class = $class;
		$this->attributes = $attributes;
		$this->weight = $weight;
		$this->action = $action;
		$this->iconOnly = $iconOnly;
		$this->href = $href;

		// Weight can only be normal, primary, or quiet
		if ( $this->weight != 'primary' && $this->weight != 'quiet' ) {
			$this->weight = 'normal';
		}
		// Action can only be default, progressive or destructive
		if ( $this->action != 'progressive' && $this->action != 'destructive' ) {
			$this->action = 'default';
		}
	}

	/**
	 * Constructs button classes based on the props
	 */
	private function getClasses(): string {
		$classes = 'mw-ui-button';
		switch ( $this->weight ) {
			case 'primary':
				$classes .= ' mw-ui-primary';
				break;
			case 'quiet':
				$classes .= ' mw-ui-quiet';
				break;
		}
		switch ( $this->action ) {
			case 'progressive':
				$classes .= ' mw-ui-progressive';
				break;
			case 'destructive':
				$classes .= ' mw-ui-destructive';
				break;
		}
		if ( $this->iconOnly ) {
			$classes .= ' mw-ui-icon-element';
		}
		if ( $this->class ) {
			$classes .= ' ' . $this->class;
		}
		return $classes;
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		$arrayAttributes = [];
		foreach ( $this->attributes as $key => $value ) {
			if ( $value === null ) {
				continue;
			}
			$arrayAttributes[] = [ 'key' => $key, 'value' => $value ];
		}
		return [
			'label' => $this->label,
			'icon' => $this->icon,
			'id' => $this->id,
			'class' => $this->getClasses(),
			'href' => $this->href,
			'array-attributes' => $arrayAttributes
		];
	}
}
