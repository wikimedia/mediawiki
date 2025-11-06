<?php

/**
 * Installer-specific wikitext formatting.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Installer;

class InstallDocFormatter {
	/** @var string */
	private $text;

	public static function format( string $text ): string {
		return ( new self( $text ) )->execute();
	}

	protected function __construct( string $text ) {
		$this->text = $text;
	}

	protected function execute(): string {
		$text = $this->text;
		// Use Unix line endings, escape some wikitext stuff
		$text = str_replace( [ '<', '{{', '[[', '__', "\r" ],
			[ '&lt;', '&#123;&#123;', '&#91;&#91;', '&#95;&#95;', '' ], $text );
		// join word-wrapped lines into one
		do {
			$prev = $text;
			$text = preg_replace( "/\n([\\*#\t])([^\n]*?)\n([^\n#\\*:]+)/", "\n\\1\\2 \\3", $text );
		} while ( $text != $prev );
		// Replace tab indents with colons
		$text = preg_replace( '/^\t\t/m', '::', $text );
		$text = preg_replace( '/^\t/m', ':', $text );

		$linkStart = '<span class="config-plainlink">[';
		$linkEnd = ' $0]</span>';

		// turn (Tnnnn) into links
		$text = preg_replace(
			'/T\d+/',
			"{$linkStart}https://phabricator.wikimedia.org/$0{$linkEnd}",
			$text
		);

		// turn (bug nnnn) into links
		$text = preg_replace(
			'/bug (\d+)/',
			"{$linkStart}https://bugzilla.wikimedia.org/$1{$linkEnd}",
			$text
		);

		// add links to manual to every global variable mentioned
		return preg_replace(
			'/\$wg[a-z0-9_]+/i',
			"{$linkStart}https://www.mediawiki.org/wiki/Manual:$0{$linkEnd}",
			$text
		);
	}
}
