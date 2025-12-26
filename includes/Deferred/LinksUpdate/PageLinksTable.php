<?php

namespace MediaWiki\Deferred\LinksUpdate;

use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputLinkTypes;

/**
 * pagelinks
 */
class PageLinksTable extends GenericPageLinksTable {
	public const VIRTUAL_DOMAIN = 'virtual-pagelinks';

	public function setParserOutput( ParserOutput $parserOutput ) {
		// Convert the format of the local links
		$this->newLinks = [];
		foreach (
			$parserOutput->getLinkList( ParserOutputLinkTypes::LOCAL )
			as [ 'link' => $link, 'pageid' => $pageid ]
		) {
			$this->newLinks[$link->getNamespace()][$link->getDBkey()] = $pageid;
		}
	}

	/** @inheritDoc */
	protected function getTableName() {
		return 'pagelinks';
	}

	/** @inheritDoc */
	protected function getFromField() {
		return 'pl_from';
	}

	/** @inheritDoc */
	protected function getNamespaceField() {
		return 'pl_namespace';
	}

	/** @inheritDoc */
	protected function getTitleField() {
		return 'pl_title';
	}

	/** @inheritDoc */
	protected function getFromNamespaceField() {
		return 'pl_from_namespace';
	}

	/** @inheritDoc */
	protected function getTargetIdField() {
		return 'pl_target_id';
	}

	/** @inheritDoc */
	protected function getVirtualDomain(): string {
		return self::VIRTUAL_DOMAIN;
	}
}
