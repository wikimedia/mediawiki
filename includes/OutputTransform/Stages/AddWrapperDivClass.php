<?php

namespace MediaWiki\OutputTransform\Stages;

use Language;
use MediaWiki\Html\Html;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\OutputTransform\ContentTextTransformStage;
use MediaWiki\Parser\ParserOutput;
use ParserOptions;

/**
 * Wrap the output in a div with the provided class name
 * @internal
 */
class AddWrapperDivClass extends ContentTextTransformStage {

	private LanguageFactory $langFactory;
	private Language $contentLang;

	public function __construct( LanguageFactory $langFactory, Language $contentLang ) {
		$this->langFactory = $langFactory;
		$this->contentLang = $contentLang;
	}

	public function shouldRun( ParserOutput $po, ?ParserOptions $popts, array $options = [] ): bool {
		return ( $options['wrapperDivClass'] ?? '' ) !== '' && !( $options['unwrap'] ?? false );
	}

	protected function transformText( string $text, ParserOutput $po, ?ParserOptions $popts, array &$options ): string {
		$wrapperDivClass = $options['wrapperDivClass'];
		$pageLang = $this->getLanguageWithFallbackGuess( $po );
		return Html::rawElement( 'div', [
			'class' => 'mw-content-' . $pageLang->getDir() . ' ' . $wrapperDivClass,
			'lang' => $pageLang->toBcp47Code(),
			'dir' => $pageLang->getDir(),
		], $text );
	}

	private function getLanguageWithFallbackGuess( ParserOutput $po ): Language {
		$pageLang = $po->getLanguage();
		if ( $pageLang ) {
			return $this->langFactory->getLanguage( $pageLang );
		}
		return $this->contentLang;
	}
}
