<?php

namespace MediaWiki\Deferred\LinksUpdate;

use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputLinkTypes;

/**
 * pagelinks
 */
class PageLinksTable extends GenericPageLinksTable {

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

	protected function getTableName() {
		return 'pagelinks';
	}

	protected function getFromField() {
		return 'pl_from';
	}

	protected function getNamespaceField() {
		return 'pl_namespace';
	}

	protected function getTitleField() {
		return 'pl_title';
	}

	protected function getFromNamespaceField() {
		return 'pl_from_namespace';
	}

	protected function getTargetIdField() {
		return 'pl_target_id';
	}

	/**
	 * Normalization stage of the links table (see T222224)
	 */
	protected function linksTargetNormalizationStage(): int {
		return SCHEMA_COMPAT_NEW;
	}
}
