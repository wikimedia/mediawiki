<?php

namespace MediaWiki\Content;

use MediaWiki\Html\Html;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * Service for syntax highlighting.
 *
 * @since 1.47
 * @ingroup Content
 */
class CodeHighlighter {

	/** @var CodeHighlightProvider[]|null */
	private ?array $providers = null;

	public function __construct(
		private readonly ObjectFactory $objectFactory,
		private readonly array $providerSpecs,
	) {
	}

	/**
	 * @return CodeHighlightProvider[]
	 */
	private function getProviders(): array {
		$this->providers ??= array_map(
			fn ( $spec ): CodeHighlightProvider => $this->objectFactory->createObject( $spec, [
				'assertClass' => CodeHighlightProvider::class,
			] ),
			$this->providerSpecs
		);
		return $this->providers;
	}

	/**
	 * Highlight the given code based on the given language.
	 *
	 * @param string $code Code to be syntax-highlighted
	 * @param CodeHighlighterOptions $options Options for syntax highlighting.
	 * @return CodeHighlighterOutput Syntax highlighting output.
	 */
	public function highlight( string $code, CodeHighlighterOptions $options ): CodeHighlighterOutput {
		foreach ( $this->getProviders() as $provider ) {
			if ( $provider->isSupportedLanguage( $options->language ) ) {
				return $provider->highlight( $code, $options );
			}
		}

		// No highlighter supports this language. Fall back to unhighlighted code.
		// Advanced options like line numbers, highlighting and copy buttons are not supported in fallback.
		if ( $options->inline ) {
			$html = Html::element( 'code',
				[ 'dir' => $options->dir, 'class' => implode( ' ', $options->classes ) ],
				$code
			);
		} else {
			$html = Html::element( 'pre',
				[ 'dir' => $options->dir, 'class' => 'mw-code ' . implode( ' ', $options->classes ) ],
				"\n" . $code . "\n"
			) . "\n";
		}

		return new CodeHighlighterOutput( $html );
	}

}
