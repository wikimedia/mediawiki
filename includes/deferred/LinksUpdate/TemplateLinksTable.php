<?php

namespace MediaWiki\Deferred\LinksUpdate;

use LogicException;
use MediaWiki\Parser\ParserOutput;

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
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		throw new LogicException( 'not supported' );
	}

	protected function getTitleField() {
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		throw new LogicException( 'not supported' );
	}

	protected function getFromNamespaceField() {
		return 'tl_from_namespace';
	}

	protected function getTargetIdField() {
		return 'tl_target_id';
	}

	/**
	 * Normalization stage of the links table (see T222224)
	 * @return int
	 */
	protected function linksTargetNormalizationStage(): int {
		return MIGRATION_NEW;
	}
}
