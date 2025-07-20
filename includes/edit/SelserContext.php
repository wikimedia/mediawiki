<?php

namespace MediaWiki\Edit;

use MediaWiki\Content\Content;
use UnexpectedValueException;
use Wikimedia\Parsoid\Core\HtmlPageBundle;
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
	private HtmlPageBundle $pageBundle;

	private int $revId;

	private ?Content $content;

	/**
	 * @param HtmlPageBundle $pageBundle
	 * @param int $revId
	 * @param Content|null $content
	 */
	public function __construct( HtmlPageBundle $pageBundle, int $revId, ?Content $content = null ) {
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

	public function getPageBundle(): HtmlPageBundle {
		return $this->pageBundle;
	}

	public function getRevisionID(): int {
		return $this->revId;
	}

	public function getContent(): ?Content {
		return $this->content;
	}

}
