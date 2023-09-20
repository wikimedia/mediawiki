<?php

namespace MediaWiki\Deferred\LinksUpdate;

use MediaWiki\Config\Config;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use ParserOutput;

/**
 * templatelinks
 *
 * @since 1.38
 */
class TemplateLinksTable extends GenericPageLinksTable {
	private const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::TemplateLinksSchemaMigrationStage,
	];

	/** @var int */
	private $migrationStage;

	public function __construct( Config $config ) {
		$options = new ServiceOptions( self::CONSTRUCTOR_OPTIONS, $config );
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->migrationStage = $options->get( MainConfigNames::TemplateLinksSchemaMigrationStage );
	}

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

	protected function getTargetIdField() {
		return 'tl_target_id';
	}

	/**
	 * Normalization stage of the links table (see T222224)
	 * @return int
	 */
	protected function linksTargetNormalizationStage(): int {
		return $this->migrationStage;
	}
}
