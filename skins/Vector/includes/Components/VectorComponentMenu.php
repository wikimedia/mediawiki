<?php
namespace MediaWiki\Skins\Vector\Components;

use Countable;

/**
 * VectorComponentMenu component
 */
class VectorComponentMenu implements VectorComponent, Countable {
	/** @var array */
	private $data;

	/**
	 * @param array $data
	 */
	public function __construct( array $data ) {
		$this->data = $data;
	}

	/**
	 * Counts how many items the menu has.
	 *
	 * @return int
	 */
	public function count(): int {
		$htmlItems = $this->data['html-items'] ?? '';
		return substr_count( $htmlItems, '<li' );
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		return $this->data + [
			'class' => '',
			'label' => '',
			'html-tooltip' => '',
			'label-class' => '',
			'heading-class' => '',
			'html-before-portal' => '',
			'html-items' => '',
			'html-after-portal' => '',
		];
	}
}
