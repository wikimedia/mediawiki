<?php
namespace MediaWiki\Skins\Vector\Components;

/**
 * VectorComponentPinnableContainer component
 * To be used with PinnableContainer/Pinned or PinnableContainer/Unpinned templates.
 */
class VectorComponentPinnableContainer implements VectorComponent {
	/** @var string */
	private $id;
	/** @var bool */
	private $isPinned;

	/**
	 * @param string $id
	 * @param bool $isPinned
	 */
	public function __construct( string $id, bool $isPinned = true ) {
		$this->id = $id;
		$this->isPinned = $isPinned;
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		return [
			'id' => $this->id,
			'is-pinned' => $this->isPinned,
		];
	}
}
