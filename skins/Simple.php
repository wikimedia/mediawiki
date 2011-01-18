<?php
/**
 * Simple: A lightweight skin with a simple white-background sidebar and no
 * top bar.
 *
 * @file
 * @ingroup Skins
 */

if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

/** */
require_once( dirname(__FILE__) . '/MonoBook.php' );

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @ingroup Skins
 */
class SkinSimple extends SkinTemplate {
	var $skinname = 'simple', $stylename = 'simple',
		$template = 'MonoBookTemplate', $useHeadElement = true;

	function setupSkinUserCss( OutputPage $out ){
		parent::setupSkinUserCss( $out );

		$out->addStyle( 'simple/main.css', 'screen' );
	}

	function reallyGenerateUserStylesheet() {
		global $wgUser;
		$s = '';
		if( $wgUser->getOption( 'highlightbroken' ) ) {
			$s .= "a.new, #quickbar a.new { text-decoration: line-through; }\n";
		} else {
			$s .= <<<CSS
a.new, #quickbar a.new,
a.stub, #quickbar a.stub {
	color: inherit;
	text-decoration: inherit;
}
a.new:after, #quickbar a.new:after {
	content: "?";
	color: #CC2200;
	text-decoration: $underline;
}
a.stub:after, #quickbar a.stub:after {
	content: "!";
	color: #772233;
	text-decoration: $underline;
}
CSS;
		}
		return $s;
	}
}
