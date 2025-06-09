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

	/** @inheritDoc */
	protected function getTableName() {
		return 'existencelinks';
	}

	/** @inheritDoc */
	protected function getFromField() {
		return 'exl_from';
	}

	/** @inheritDoc */
	protected function getNamespaceField() {
		throw new \LogicException( 'This table has no namespace field' );
	}

	/** @inheritDoc */
	protected function getTitleField() {
		throw new \LogicException( 'This table has no title field' );
	}

	/** @inheritDoc */
	protected function getFromNamespaceField() {
		return null;
	}

	/** @inheritDoc */
	protected function getTargetIdField() {
		return 'exl_target_id';
	}

	protected function linksTargetNormalizationStage(): int {
		return SCHEMA_COMPAT_NEW;
	}
}
