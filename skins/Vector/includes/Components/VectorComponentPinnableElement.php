<?php
namespace MediaWiki\Skins\Vector\Components;

/**
 * VectorComponentPinnableElement component
 */
class VectorComponentPinnableElement implements VectorComponent {
	/** @var string */
	private $id;

	/**
	 * @param string $id
	 */
	public function __construct( string $id ) {
		$this->id = $id;
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		return [
			'id' => $this->id,
		];
	}
}
