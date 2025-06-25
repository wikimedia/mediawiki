<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Html\Html;
use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\OutputTransform\ContentTextTransformStage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use Psr\Log\LoggerInterface;

/**
 * Wrap the output in a div with the provided class name
 * @internal
 */
class AddWrapperDivClass extends ContentTextTransformStage {

	private LanguageFactory $langFactory;
	private Language $contentLang;

	public function __construct(
		ServiceOptions $options, LoggerInterface $logger, LanguageFactory $langFactory,
		Language $contentLang
	) {
		parent::__construct( $options, $logger );
		$this->langFactory = $langFactory;
		$this->contentLang = $contentLang;
	}

	public function shouldRun( ParserOutput $po, ?ParserOptions $popts, array $options = [] ): bool {
		return ( $options['wrapperDivClass'] ?? $po->getWrapperDivClass() ) !== '' && !( $options['unwrap'] ?? false );
	}

	protected function transformText( string $text, ParserOutput $po, ?ParserOptions $popts, array &$options ): string {
		$wrapperDivClass = $options['wrapperDivClass'] ?? $po->getWrapperDivClass();
		$pageLang = $this->getLanguageWithFallbackGuess( $po );
		$extraAttrs = [];
		$parsoidVersion = $po->getExtensionData( 'core:parsoid-version' );
		$htmlVersion = $po->getExtensionData( 'core:html-version' );
		if ( $parsoidVersion !== null ) {
			$extraAttrs['data-mw-parsoid-version'] = $parsoidVersion;
		}
		if ( $htmlVersion !== null ) {
			$extraAttrs['data-mw-html-version'] = $htmlVersion;
		}
		return Html::rawElement( 'div', [
			'class' => 'mw-content-' . $pageLang->getDir() . ' ' . $wrapperDivClass,
			'lang' => $pageLang->toBcp47Code(),
			'dir' => $pageLang->getDir(),
		] + $extraAttrs, $text );
	}

	private function getLanguageWithFallbackGuess( ParserOutput $po ): Language {
		$pageLang = $po->getLanguage();
		if ( $pageLang ) {
			return $this->langFactory->getLanguage( $pageLang );
		}
		return $this->contentLang;
	}
}
