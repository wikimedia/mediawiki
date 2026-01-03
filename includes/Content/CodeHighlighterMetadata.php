<?php

namespace MediaWiki\Content;

use MediaWiki\Output\OutputPage;
use MediaWiki\Parser\ParserOutput;
use Wikimedia\Message\MessageSpecifier;

/**
 * Default metadata implementation for highlighted code.
 *
 * Providers can use this class directly, or extend it when they need to carry
 * additional metadata without changing CodeHighlighter callers.
 *
 * @newable
 * @stable to extend
 * @since 1.47
 * @ingroup Content
 */
class CodeHighlighterMetadata implements ICodeHighlighterMetadata {

	/**
	 * @param string[] $modules ResourceLoader modules necessary to render the highlighted code
	 * @param string[] $moduleStyles ResourceLoader style modules necessary to render the highlighted code
	 * @param string[] $categories Any tracking categories to be added to the output
	 * @param MessageSpecifier[] $warnings Any warnings from syntax highlighting
	 */
	public function __construct(
		protected array $modules = [],
		protected array $moduleStyles = [],
		protected array $categories = [],
		protected array $warnings = [],
	) {
	}

	public function addToParserOutput( ParserOutput $parserOutput ): void {
		$parserOutput->addModules( $this->modules );
		$parserOutput->addModuleStyles( $this->moduleStyles );
		foreach ( $this->categories as $category ) {
			$parserOutput->addCategory( $category );
		}
		foreach ( $this->warnings as $warning ) {
			$parserOutput->addWarningMsgVal( $warning );
		}
	}

	public function addToOutputPage( OutputPage $outputPage ): void {
		$outputPage->addModules( $this->modules );
		$outputPage->addModuleStyles( $this->moduleStyles );
	}
}
