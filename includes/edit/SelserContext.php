<?php

namespace MediaWiki\Edit;

use Content;
use UnexpectedValueException;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\Core\SelserData;

/**
 * Value object representing contextual information needed by Parsoid for selective serialization ("selser") of
 * modified HTML.
 *
 * @see SelserData
 *
 * @since 1.40
 */
class SelserContext {
	private PageBundle $pageBundle;

	private int $revId;

	private ?Content $content;

	/**
	 * @param PageBundle $pageBundle
	 * @param int $revId
	 * @param Content|null $content
	 */
	public function __construct( PageBundle $pageBundle, int $revId, ?Content $content = null ) {
		if ( !$revId && !$content ) {
			throw new UnexpectedValueException(
				'If $revId is 0, $content must be given. ' .
				'If we can\'t load the content from a revision, we have to stash it.'
			);
		}

		$this->pageBundle = $pageBundle;
		$this->revId = $revId;
		$this->content = $content;
	}

	/**
	 * @return PageBundle
	 */
	public function getPageBundle(): PageBundle {
		return $this->pageBundle;
	}

	/**
	 * @return int
	 */
	public function getRevisionID(): int {
		return $this->revId;
	}

	/**
	 * @return Content|null
	 */
	public function getContent(): ?Content {
		return $this->content;
	}

}
