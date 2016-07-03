<?php
/**
 * Installer-specific wikitext formatting.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

class InstallDocFormatter {
	static function format( $text ) {
		$obj = new self( $text );

		return $obj->execute();
	}

	protected function __construct( $text ) {
		$this->text = $text;
	}

	protected function execute() {
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
		$text = preg_replace(
			'/\$wg[a-z0-9_]+/i',
			"{$linkStart}https://www.mediawiki.org/wiki/Manual:$0{$linkEnd}",
			$text
		);

		return $text;
	}
}
