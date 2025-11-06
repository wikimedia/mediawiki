<?php
/**
 * A block restriction object of type 'Page'.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Block\Restriction;

use MediaWiki\Title\Title;

class PageRestriction extends AbstractRestriction {

	/**
	 * @inheritDoc
	 */
	public const TYPE = 'page';

	/**
	 * @inheritDoc
	 */
	public const TYPE_ID = 1;

	/**
	 * @var Title|false|null
	 */
	protected $title;

	/**
	 * @inheritDoc
	 */
	public function matches( Title $title ) {
		if ( !$this->getTitle() ) {
			return false;
		}

		return $title->equals( $this->getTitle() );
	}

	/**
	 * @since 1.33
	 * @param Title $title
	 * @return self
	 */
	public function setTitle( Title $title ) {
		$this->title = $title;

		return $this;
	}

	/**
	 * @since 1.33
	 * @return Title|false
	 */
	public function getTitle() {
		// If the title does not exist, set to false to prevent multiple database
		// queries.
		$this->title ??= Title::newFromID( $this->value ) ?? false;

		return $this->title;
	}

	/**
	 * @inheritDoc
	 */
	public static function newFromRow( \stdClass $row ) {
		/** @var self $restriction */
		$restriction = parent::newFromRow( $row );
		'@phan-var self $restriction';

		// If the page_namespace and the page_title were provided, add the title to
		// the restriction.
		if ( isset( $row->page_namespace ) && isset( $row->page_title ) ) {
			// Clone the row so it is not mutated.
			$row = clone $row;
			$row->page_id = $row->ir_value;
			$title = Title::newFromRow( $row );
			$restriction->setTitle( $title );
		}

		return $restriction;
	}

	/**
	 * @internal
	 * @since 1.36
	 * @param string|Title $title
	 * @return self
	 */
	public static function newFromTitle( $title ) {
		if ( is_string( $title ) ) {
			$title = Title::newFromText( $title );
		}

		$restriction = new self( 0, $title->getArticleID() );
		// @phan-suppress-next-line PhanTypeMismatchArgumentNullable Title is always valid
		$restriction->setTitle( $title );

		return $restriction;
	}
}
