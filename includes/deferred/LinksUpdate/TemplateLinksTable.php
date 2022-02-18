<?php

namespace MediaWiki\Deferred\LinksUpdate;

use ParserOutput;

/**
 * templatelinks
 *
 * @since 1.38
 */
class TemplateLinksTable extends GenericPageLinksTable {
	public function setParserOutput( ParserOutput $parserOutput ) {
		$this->newLinks = $parserOutput->getTemplates();
	}

	protected function getTableName() {
		return 'templatelinks';
	}

	protected function getFromField() {
		return 'tl_from';
	}

	protected function getNamespaceField() {
		return 'tl_namespace';
	}

	protected function getTitleField() {
		return 'tl_title';
	}

	protected function getFromNamespaceField() {
		return 'tl_from_namespace';
	}
}
