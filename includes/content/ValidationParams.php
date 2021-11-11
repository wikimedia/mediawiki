<?php
namespace MediaWiki\Content;

use MediaWiki\Page\PageIdentity;

/**
 * @since 1.38
 * An object to hold validation params.
 */
class ValidationParams {
	/** @var PageIdentity */
	private $pageIdentity;

	/** @var int */
	private $flags;

	/** @var int */
	private $parentRevId;

	public function __construct( PageIdentity $pageIdentity, int $flags, int $parentRevId = -1 ) {
		$this->pageIdentity = $pageIdentity;
		$this->flags = $flags;
		$this->parentRevId = $parentRevId;
	}

	/**
	 *
	 * @return PageIdentity
	 */
	public function getPageIdentity(): PageIdentity {
		return $this->pageIdentity;
	}

	/**
	 *
	 * @return int
	 */
	public function getFlags(): int {
		return $this->flags;
	}

	/**
	 * @deprecated since 1.38. Born soft-deprecated as we will move usage of it
	 * to MultiContentSaveHook in ProofreadPage (only one place of usage).
	 *
	 * @return int
	 */
	public function getParentRevisionId(): int {
		return $this->parentRevId;
	}
}
