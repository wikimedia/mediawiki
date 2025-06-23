<?php

namespace MediaWiki\Deferred\LinksUpdate;

use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * Shared code for pagelinks, templatelinks and existencelinks. These tables
 * all link to an arbitrary page identified by namespace and title.
 *
 * Link ID format: string[]:
 *   - 0: namespace ID
 *   - 1: title DB key
 *
 * @since 1.38
 */
abstract class GenericPageLinksTable extends TitleLinksTable {
	/**
	 * A 2d array representing the new links, with the namespace ID in the
	 * first key, the DB key in the second key, and the value arbitrary.
	 *
	 * @var array
	 */
	protected $newLinks = [];

	/**
	 * The existing links in the same format as self::$newLinks, or null if it
	 * has not been loaded yet.
	 *
	 * @var array|null
	 */
	private $existingLinks;

	/**
	 * Get the namespace field name
	 *
	 * @return string
	 */
	abstract protected function getNamespaceField();

	/**
	 * Get the title (DB key) field name
	 *
	 * @return string
	 */
	abstract protected function getTitleField();

	/**
	 * Get the link target id (DB key) field name
	 *
	 * @return string
	 */
	abstract protected function getTargetIdField();

	/**
	 * @return string|null
	 */
	abstract protected function getFromNamespaceField();

	protected function getExistingFields() {
		if ( $this->linksTargetNormalizationStage() & SCHEMA_COMPAT_WRITE_OLD ) {
			return [
				'ns' => $this->getNamespaceField(),
				'title' => $this->getTitleField()
			];
		}
		return [
			'ns' => 'lt_namespace',
			'title' => 'lt_title',
		];
	}

	/**
	 * Get existing links as an associative array
	 *
	 * @return array
	 */
	private function getExistingLinks() {
		if ( $this->existingLinks === null ) {
			$this->existingLinks = [];
			foreach ( $this->fetchExistingRows() as $row ) {
				$this->existingLinks[$row->ns][$row->title] = 1;
			}
		}

		return $this->existingLinks;
	}

	protected function fetchExistingRows(): IResultWrapper {
		$queryBuilder = $this->getDB()->newSelectQueryBuilder()
			->select( $this->getExistingFields() )
			->from( $this->getTableName() )
			->where( $this->getFromConds() );

		// This read is for updating, it's conceptually better to use the write config
		if ( !( $this->linksTargetNormalizationStage() & SCHEMA_COMPAT_WRITE_OLD ) ) {
			$queryBuilder->join( 'linktarget', null, [ $this->getTargetIdField() . '=lt_id' ] );
		}

		return $queryBuilder
			->caller( __METHOD__ )
			->fetchResultSet();
	}

	protected function getNewLinkIDs() {
		foreach ( $this->newLinks as $ns => $links ) {
			foreach ( $links as $dbk => $unused ) {
				yield [ $ns, (string)$dbk ];
			}
		}
	}

	protected function getExistingLinkIDs() {
		foreach ( $this->getExistingLinks() as $ns => $links ) {
			foreach ( $links as $dbk => $unused ) {
				yield [ $ns, (string)$dbk ];
			}
		}
	}

	protected function isExisting( $linkId ) {
		[ $ns, $dbk ] = $linkId;
		return isset( $this->getExistingLinks()[$ns][$dbk] );
	}

	protected function isInNewSet( $linkId ) {
		[ $ns, $dbk ] = $linkId;
		return isset( $this->newLinks[$ns][$dbk] );
	}

	protected function insertLink( $linkId ) {
		$row = [];
		$fromNamespaceField = $this->getFromNamespaceField();
		if ( $fromNamespaceField !== null ) {
			$row[$fromNamespaceField] = $this->getSourcePage()->getNamespace();
		}
		if ( $this->linksTargetNormalizationStage() & SCHEMA_COMPAT_WRITE_OLD ) {
			$row[$this->getNamespaceField()] = $linkId[0];
			$row[$this->getTitleField()] = $linkId[1];
		}
		if ( $this->linksTargetNormalizationStage() & SCHEMA_COMPAT_WRITE_NEW ) {
			$row[$this->getTargetIdField()] = $this->linkTargetLookup->acquireLinkTargetId(
				$this->makeTitle( $linkId ),
				$this->getDB()
			);
		}
		$this->insertRow( $row );
	}

	protected function deleteLink( $linkId ) {
		if ( $this->linksTargetNormalizationStage() & SCHEMA_COMPAT_WRITE_OLD ) {
			$this->deleteRow( [
				$this->getNamespaceField() => $linkId[0],
				$this->getTitleField() => $linkId[1]
			] );
		} elseif ( $this->linksTargetNormalizationStage() & SCHEMA_COMPAT_WRITE_NEW ) {
			$this->deleteRow( [
				$this->getTargetIdField() => $this->linkTargetLookup->acquireLinkTargetId(
					$this->makeTitle( $linkId ),
					$this->getDB()
				)
			] );
		}
	}

	protected function needForcedLinkRefresh() {
		return $this->isCrossNamespaceMove();
	}

	protected function makePageReferenceValue( $linkId ): PageReferenceValue {
		return PageReferenceValue::localReference( $linkId[0], $linkId[1] );
	}

	protected function makeTitle( $linkId ): Title {
		return Title::makeTitle( $linkId[0], $linkId[1] );
	}

	protected function deduplicateLinkIds( $linkIds ) {
		$seen = [];
		foreach ( $linkIds as $linkId ) {
			if ( !isset( $seen[$linkId[0]][$linkId[1]] ) ) {
				$seen[$linkId[0]][$linkId[1]] = true;
				yield $linkId;
			}
		}
	}
}
