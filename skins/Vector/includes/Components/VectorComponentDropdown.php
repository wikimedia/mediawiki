<?php
namespace MediaWiki\Skins\Vector\Components;

/**
 * VectorComponentDropdown component
 */
class VectorComponentDropdown implements VectorComponent {
	/** @var string */
	private $id;
	/** @var string */
	private $label;
	/** @var string */
	private $class;
	/** @var string */
	private $tooltip;
	/** @var string|null */
	private $icon;

	/**
	 * @param string $id
	 * @param string $label
	 * @param string $class
	 * @param string|null $icon
	 * @param string $tooltip
	 */
	public function __construct( string $id, string $label, string $class = '', $icon = null, string $tooltip = '' ) {
		$this->id = $id;
		$this->label = $label;
		$this->class = $class;
		$this->icon = $icon;
		$this->tooltip = $tooltip;
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		// FIXME: Stop hardcoding button and icon styles, this assumes all dropdowns with icons are icon buttons
		// Not the case for the language dropdown, page tools, etc
		$icon = $this->icon;
		$headingClass = $icon ?
			'mw-ui-button mw-ui-quiet mw-ui-icon-element ' : '';

		return [
			'id' => $this->id,
			'label' => $this->label,
			'heading-class' => $headingClass,
			'icon' => $this->icon,
			'html-vector-menu-heading-attributes' => '',
			'html-vector-menu-checkbox-attributes' => '',
			'class' => $this->class,
			'html-tooltip' => $this->tooltip,
			'checkbox-class' => '',
		];
	}
}
