<?php

namespace MediaWiki\Deferred\LinksUpdate;

use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputLinkTypes;

/**
 * References like {{#ifexist:Title}} that cause the parser output to change
 * when the existence of the page changes, but are not shown in
 * Special:WhatLinksHere.
 *
 * @since 1.45
 * @phan-file-suppress PhanPluginNeverReturnMethod -- for getNamespaceField
 */
class ExistenceLinksTable extends GenericPageLinksTable {

	public function setParserOutput( ParserOutput $parserOutput ) {
		$this->newLinks = [];
		foreach (
			$parserOutput->getLinkList( ParserOutputLinkTypes::EXISTENCE )
			as [ 'link' => $link ]
		) {
			$this->newLinks[$link->getNamespace()][$link->getDBkey()] = true;
		}
	}

	protected function getTableName() {
		return 'existencelinks';
	}

	protected function getFromField() {
		return 'exl_from';
	}

	protected function getNamespaceField() {
		throw new \LogicException( 'This table has no namespace field' );
	}

	protected function getTitleField() {
		throw new \LogicException( 'This table has no title field' );
	}

	protected function getFromNamespaceField() {
		return null;
	}

	protected function getTargetIdField() {
		return 'exl_target_id';
	}

	protected function linksTargetNormalizationStage(): int {
		return SCHEMA_COMPAT_NEW;
	}
}
