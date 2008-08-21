<?php
/**
 * See docs/skin.txt
 *
 * @todo document
 * @file
 * @ingroup Skins
 */

if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

/** */
require_once( dirname(__FILE__) . '/MonoBook.php' );

/**
 * @todo document
 * @ingroup Skins
 */
class SkinSimple extends SkinTemplate {
	function initPage( OutputPage $out ) {
		SkinTemplate::initPage( $out );
		$this->skinname  = 'simple';
		$this->stylename = 'simple';
		$this->template  = 'MonoBookTemplate';
	}

	function setupSkinUserCss( OutputPage $out ){
		$out->addStyle( 'simple/main.css', 'screen' );
		$out->addStyle( 'simple/rtl.css', '', '', 'rtl' );

	}

	function reallyGenerateUserStylesheet() {
		global $wgUser;
		$s = '';
		if (($undopt = $wgUser->getOption("underline")) != 2) {
			$underline = $undopt ? 'underline' : 'none';
			$s .= "a { text-decoration: $underline; }\n";
		}
		if ($wgUser->getOption('highlightbroken')) {
			$s .= "a.new, #quickbar a.new { text-decoration: line-through; }\n";
		} else {
			$s .= <<<END
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
END;
		}
		if ($wgUser->getOption('justify')) {
			$s .= "#article, #bodyContent { text-align: justify; }\n";
		}
		if (!$wgUser->getOption('showtoc')) {
			$s .= "#toc { display: none; }\n";
		}
		if (!$wgUser->getOption('editsection')) {
			$s .= ".editsection { display: none; }\n";
		}
		return $s;
	}
}


