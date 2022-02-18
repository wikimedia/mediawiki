<?php

namespace MediaWiki\CommentFormatter;

use MediaWiki\Linker\LinkTarget;

/**
 * An object to represent one of the inputs to a batch formatting operation.
 *
 * @since 1.38
 * @newable
 */
class CommentItem {
	/**
	 * @var string
	 * @internal
	 */
	public $comment;

	/**
	 * @var LinkTarget|null
	 * @internal
	 */
	public $selfLinkTarget;

	/**
	 * @var bool|null
	 * @internal
	 */
	public $samePage;

	/**
	 * @var string|false|null
	 * @internal
	 */
	public $wikiId;

	/**
	 * @param string $comment The comment to format
	 */
	public function __construct( string $comment ) {
		$this->comment = $comment;
	}

	/**
	 * Set the self-link target.
	 *
	 * @param LinkTarget $selfLinkTarget The title used for fragment-only
	 *   and section links, formerly $title.
	 * @return $this
	 */
	public function selfLinkTarget( LinkTarget $selfLinkTarget ) {
		$this->selfLinkTarget = $selfLinkTarget;
		return $this;
	}

	/**
	 * Set the same-page flag.
	 *
	 * @param bool $samePage If true, self links are rendered with a fragment-
	 *   only URL. Formerly $local.
	 * @return $this
	 */
	public function samePage( $samePage = true ) {
		$this->samePage = $samePage;
		return $this;
	}

	/**
	 * ID of the wiki to link to (if not the local wiki), as used by WikiMap.
	 * This is used to render comments which are loaded from a foreign wiki.
	 * This only affects links which are syntactically internal -- it has no
	 * effect on interwiki links.
	 *
	 * @param string|false|null $wikiId
	 * @return $this
	 */
	public function wikiId( $wikiId ) {
		$this->wikiId = $wikiId;
		return $this;
	}
}
