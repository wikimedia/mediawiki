<?php

namespace MediaWiki\Deferred\LinksUpdate;

use LogicException;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputLinkTypes;

/**
 * templatelinks
 *
 * @since 1.38
 */
class TemplateLinksTable extends GenericPageLinksTable {
	public function setParserOutput( ParserOutput $parserOutput ) {
		// Convert the format of the template links
		$this->newLinks = [];
		foreach (
			$parserOutput->getLinkList( ParserOutputLinkTypes::TEMPLATE )
			as [ 'link' => $link, 'pageid' => $pageid ]
		) {
			$this->newLinks[$link->getNamespace()][$link->getDBkey()] = $pageid;
		}
	}

	/** @inheritDoc */
	protected function getTableName() {
		return 'templatelinks';
	}

	/** @inheritDoc */
	protected function getFromField() {
		return 'tl_from';
	}

	/** @inheritDoc */
	protected function getNamespaceField() {
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		throw new LogicException( 'not supported' );
	}

	/** @inheritDoc */
	protected function getTitleField() {
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		throw new LogicException( 'not supported' );
	}

	/** @inheritDoc */
	protected function getFromNamespaceField() {
		return 'tl_from_namespace';
	}

	/** @inheritDoc */
	protected function getTargetIdField() {
		return 'tl_target_id';
	}

	/**
	 * Normalization stage of the links table (see T222224)
	 */
	protected function linksTargetNormalizationStage(): int {
		return MIGRATION_NEW;
	}
}
