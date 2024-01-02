<?php

namespace MediaWiki\OutputTransform\Stages;

use Language;
use MediaWiki\Linker\Linker;
use MediaWiki\OutputTransform\ContentTextTransformStage;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Tidy\TidyDriverBase;
use Parser;
use ParserOptions;
use RequestContext;

/**
 * Inject table of contents (or empty string if there's no sections)
 * @internal
 */
class HandleTOCMarkers extends ContentTextTransformStage {

	private TidyDriverBase $tidy;

	public function __construct( TidyDriverBase $tidy ) {
		$this->tidy = $tidy;
	}

	public function shouldRun( ParserOutput $po, ?ParserOptions $popts, array $options = [] ): bool {
		return !( $options['allowTOC'] ?? true ) || ( $options['injectTOC'] ?? true );
	}

	protected function transformText( string $text, ParserOutput $po, ?ParserOptions $popts, array &$options ): string {
		if ( ( $options['allowTOC'] ?? true ) && ( $options['injectTOC'] ?? true ) ) {
			return $this->injectTOC( $text, $po, $options );
		}
		if ( !( $options['allowTOC'] ?? true ) ) {
			return Parser::replaceTableOfContentsMarker( $text, '' );
		}
		return $text;
	}

	private function injectTOC( string $text, ParserOutput $po, array $options ): string {
		$lang = $this->resolveUserLanguage( $options );
		$numSections = count( $po->getSections() );
		$tocData = $po->getTOCData();
		if ( $numSections === 0 ) {
			$toc = '';
		} else {
			$toc = Linker::generateTOC( $tocData, $lang );
			$toc =
				$this->tidy->tidy( $toc, [ Sanitizer::class, 'armorFrenchSpaces' ] );
		}

		return Parser::replaceTableOfContentsMarker( $text, $toc );
	}

	/**
	 * Extracts the userLanguage from the $options array, with a fallback on skin language and request
	 * context language
	 * @param array $options
	 * @return Language
	 */
	private function resolveUserLanguage( array $options ): Language {
		$userLang = $options['userLang'] ?? null;
		$skin = $options['skin'] ?? null;
		if ( ( !$userLang ) && $skin ) {
			// TODO: We probably don't need a full Skin option here
			$userLang = $skin->getLanguage();
		}
		if ( !$userLang ) {
			// T348853 passing either userLang or skin will be mandatory in the future
			$userLang = RequestContext::getMain()->getLanguage();
		}
		return $userLang;
	}
}
