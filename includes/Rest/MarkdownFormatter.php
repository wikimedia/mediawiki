<?php

namespace MediaWiki\Rest;

use MediaWiki\Parser\Sanitizer;
use MediaWiki\Title\Title;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageSpecifier;

/**
 * Text formatter that outputs Markdown.
 *
 * This is based on a rough, best-effort conversion from Wikitext.
 * It supports links and emphasis. It does not support block-level constructs.
 *
 * The conversion attempts to be idempotent, so messages that are
 * already in Markdown don't get mangled.
 */
class MarkdownFormatter implements ITextFormatter {

	/**
	 * @param ITextFormatter $formatter A formatter using FORMAT_TEXT
	 *        or FORMAT_PLAIN.
	 * @param string $baseUrl Template for page URLs, with $1 as the
	 *        placeholder for the page title, like $wgArticlePath.
	 *
	 * @internal For use by ServiceWiring only
	 */
	public function __construct(
		private ITextFormatter $formatter,
		private string $baseUrl
	) {
	}

	public function getLangCode(): string {
		return $this->formatter->getLangCode();
	}

	public function format( MessageSpecifier $mv ): string {
		$text = $this->formatter->format( $mv );

		if ( !str_ends_with( $mv->getKey(), '.md' ) ) {
			// TODO: first try to append .md and check if the message exists (T443523).
			$text = $this->wikitextToMarkdown( $text );
		}

		return $text;
	}

	/**
	 * Converts wikitext links and emphasis to Markdown. Text that contains
	 * no wikitext link or emphasis syntax is returned unchanged.
	 * Idempotent, exising Markdown is not processed again.
	 */
	private function wikitextToMarkdown( string $text ): string {
		// Internal links: [[Target]] and [[Target|label]].
		// A following "(" means this is already Markdown, e.g. "[[x]](url)".
		$text = preg_replace_callback(
			'/\[\[([^\[\]|]+)(?:\|([^\[\]]*))?\]\](?!\()/',
			function ( array $m ): string {
				$target = trim( $m[1] );
				$label = isset( $m[2] ) && trim( $m[2] ) !== '' ? $m[2] : $target;
				$url = strtr(
					$this->getLocalUrl( $target ),
					[ '(' => '%28', ')' => '%29' ]
				);

				return "[$label]($url)";
			},
			$text
		);

		// External links with a label: [https://example.org label].
		$text = preg_replace(
			'/\[((?:https?:)?\/\/[^\s\[\]]+)\s+([^\[\]]+)\]/',
			'[$2]($1)',
			$text
		);

		// Bracketed URLs without a label. A following "(" means this is the
		// label of a Markdown link, e.g. "[https://example.org](url)".
		// Autolinks require an absolute URI, so protocol-relative URLs are
		// left alone.
		$text = preg_replace(
			'/\[(https?:\/\/[^\s\[\]<>]+)\](?!\()/',
			'<$1>',
			$text
		);

		// Naked URLs. Skipped when preceded by "<" (already an autolink),
		// "(" (Markdown link destination), "[" (URL used as a link label),
		// or a quote or "=" (HTML attribute).
		$text = preg_replace_callback(
			'/(?<![<(\[\w\/"\'=])https?:\/\/[^\s<>\[\]]+/',
			static function ( array $m ): string {
				// A single trailing punctuation character belongs to the
				// sentence, not the URL. Parentheses always count as URL.
				preg_match( '/^(.*?)([.,;:!?\'"]?)$/', $m[0], $parts );

				return "<$parts[1]>$parts[2]";
			},
			$text
		);

		// Emphasis: longest apostrophe run first.
		$text = preg_replace( "/'''''(.+?)'''''/", '***$1***', $text );
		$text = preg_replace( "/'''(.+?)'''/", '**$1**', $text );
		$text = preg_replace( "/''(.+?)''/", '*$1*', $text );

		return $text;
	}

	/**
	 * Return the URL for a wiki page title.
	 * @see Title::getLocalURL()
	 *
	 * @param string $title The name of the page to link to.
	 *
	 * @return string
	 */
	private function getLocalUrl( string $title ): string {
		$parts = explode( '#', $title, 2 );
		$base = $parts[0];
		$fragment = $parts[1] ?? null;

		// XXX: If it turns out we need interwiki prefixes here, we
		// will have to go through Title. Compare LinkRenderer::getLinkURL().
		$dbkey = str_replace( ' ', '_', $base );

		$url = str_replace(
			'$1',
			wfUrlencode( $dbkey ),
			$this->baseUrl
		);

		if ( $fragment !== null ) {
			$url .= '#' . Sanitizer::escapeIdForLink( $fragment );
		}

		return $url;
	}
}
