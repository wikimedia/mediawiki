<?php

namespace MediaWiki\Deferred\LinksUpdate;

use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * An abstract base class for tables that link to local titles.
 *
 * @stable to extend
 * @since 1.38
 */
abstract class TitleLinksTable extends LinksTable {
	/**
	 * Convert a link ID to a PageReferenceValue
	 *
	 * @param mixed $linkId
	 * @return PageReferenceValue
	 */
	abstract protected function makePageReferenceValue( $linkId ): PageReferenceValue;

	/**
	 * Get the link target id (DB key) field name
	 *
	 * @return string
	 */
	abstract protected function getTargetIdField();

	/**
	 * Convert a link ID to a Title
	 *
	 * @stable to override
	 * @param mixed $linkId
	 * @return Title
	 */
	protected function makeTitle( $linkId ): Title {
		return Title::newFromPageReference( $this->makePageReferenceValue( $linkId ) );
	}

	/**
	 * Given an iterator over link IDs, remove links which go to the same
	 * title, leaving only one link per title.
	 *
	 * @param iterable<mixed> $linkIds
	 * @return iterable<mixed>
	 */
	abstract protected function deduplicateLinkIds( $linkIds );

	/**
	 * Get link IDs for a given set type, filtering out duplicate links to the
	 * same title.
	 *
	 * @param int $setType
	 * @return iterable<mixed>
	 */
	protected function getDeduplicatedLinkIds( $setType ) {
		$linkIds = $this->getLinkIDs( $setType );
		// Only the CHANGED set type should have duplicates
		if ( $setType === self::CHANGED ) {
			$linkIds = $this->deduplicateLinkIds( $linkIds );
		}
		return $linkIds;
	}

	/**
	 * Get a link set as an array of Title objects. This is memory-inefficient.
	 *
	 * @deprecated since 1.38, hard-deprecated since 1.43
	 * @param int $setType
	 * @return Title[]
	 */
	public function getTitleArray( $setType ) {
		wfDeprecated( __METHOD__, '1.43' );
		$linkIds = $this->getDeduplicatedLinkIds( $setType );
		$titles = [];
		foreach ( $linkIds as $linkId ) {
			$titles[] = $this->makeTitle( $linkId );
		}
		return $titles;
	}

	/**
	 * Get a link set as an iterator over PageReferenceValue objects.
	 *
	 * @param int $setType
	 * @return iterable<PageReferenceValue>
	 * @phan-return \Traversable
	 */
	public function getPageReferenceIterator( $setType ) {
		$linkIds = $this->getDeduplicatedLinkIds( $setType );
		foreach ( $linkIds as $linkId ) {
			yield $this->makePageReferenceValue( $linkId );
		}
	}

	/** @inheritDoc */
	protected function fetchExistingRows(): IResultWrapper {
		return $this->getReplicaDB()->newSelectQueryBuilder()
			->select( $this->getExistingFields() )
			->from( $this->getTableName() )
			->join( 'linktarget', null, [ $this->getTargetIdField() . '=lt_id' ] )
			->where( $this->getFromConds() )
			->caller( __METHOD__ )
			->fetchResultSet();
	}
}
