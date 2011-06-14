<?php

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
		$text = str_replace( array( '<', '{{', '[[', "\r" ),
			array( '&lt;', '&#123;&#123;', '&#91;&#91;', '' ), $text );
		// join word-wrapped lines into one
		do {
			$prev = $text;
			$text = preg_replace( "/\n([\\*#\t])([^\n]*?)\n([^\n#\\*:]+)/", "\n\\1\\2 \\3", $text );
		} while ( $text != $prev );
		// Replace tab indents with colons
		$text = preg_replace( '/^\t\t/m', '::', $text );
		$text = preg_replace( '/^\t/m', ':', $text );
		// turn (bug nnnn) into links
		$text = preg_replace_callback('/bug (\d+)/', array( $this, 'replaceBugLinks' ), $text );
		// add links to manual to every global variable mentioned
		$text = preg_replace_callback('/(\$wg[a-z0-9_]+)/i', array( $this, 'replaceConfigLinks' ), $text );
		return $text;
	}

	protected function replaceBugLinks( $matches ) {
		return '<span class="config-plainlink">[https://bugzilla.wikimedia.org/' .
			$matches[1] . ' bug ' . $matches[1] . ']</span>';
	}

	protected function replaceConfigLinks( $matches ) {
		return '<span class="config-plainlink">[http://www.mediawiki.org/wiki/Manual:' .
			$matches[1] . ' ' . $matches[1] . ']</span>';
	}
}
