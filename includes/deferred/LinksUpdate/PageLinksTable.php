<?php

namespace MediaWiki\Deferred\LinksUpdate;

use ParserOutput;

/**
 * pagelinks
 */
class PageLinksTable extends GenericPageLinksTable {
	public function setParserOutput( ParserOutput $parserOutput ) {
		$this->newLinks = $parserOutput->getLinks();
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
}
