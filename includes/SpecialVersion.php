<?php
/**#@+
 * Give information about the version of MediaWiki, PHP, the DB and extensions
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 *
 * @bug 2019, 4531
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

/**
 * constructor
 */
function wfSpecialVersion() {
	$version = new SpecialVersion;
	$version->execute();
}

class SpecialVersion {
	/**
	 * main()
	 */
	function execute() {
		global $wgOut;

		$wgOut->addHTML( '<div dir="ltr">' );
		$wgOut->addWikiText(
			$this->MediaWikiCredits() .
			$this->extensionCredits() .
			$this->wgHooks()
		);
		$wgOut->addHTML( $this->IPInfo() );
		$wgOut->addHTML( '</div>' );
	}

	/**#@+
	 * @access private
	 */

	/**
	 * @static
	 */
<?php
/**
 * Copyright (C) 2004 Gabriel Wicke <wicke@wikidev.net>
 * http://wikidev.net/
 * Based on PageHistory and SpecialExport
 *
 * License: GPL (http://www.gnu.org/copyleft/gpl.html)
 *
 * @author Gabriel Wicke <wicke@wikidev.net>
 * @package MediaWiki
 */

/** */
require_once( 'Revision.php' );

/**
 * @todo document
 * @package MediaWiki
 */
class RawPage {
	var $mArticle, $mTitle, $mRequest;
	var $mOldId, $mGen, $mCharset;
	var $mSmaxage, $mMaxage;
	var $mContentType, $mExpandTemplates;

	function RawPage( &$article, $request = false ) {
		global $wgRequest, $wgInputEncoding, $wgSquidMaxage, $wgJsMimeType;

		$allowedCTypes = array('text/x-wiki', $wgJsMimeType, 'text/css', 'application/x-zope-edit');
		$this->mArticle =& $article;
		$this->mTitle =& $article->mTitle;

		if ( $request === false ) {
			$this->mRequest =& $wgRequest;
		} else {
			$this->mRequest = $request;
		}

		$ctype = $this->mRequest->getVal( 'ctype' );
		$smaxage = $this->mRequest->getIntOrNull( 'smaxage', $wgSquidMaxage );
		$maxage = $this->mRequest->getInt( 'maxage', $wgSquidMaxage );
		$this->mExpandTemplates = $this->mRequest->getVal( 'templates' ) === 'expand';
		
		$oldid = $this->mRequest->getInt( 'oldid' );
		switch ( $wgRequest->getText( 'direction' ) ) {
			case 'next':
				# output next revision, or nothing if there isn't one
				if ( $oldid ) {
					$oldid = $this->mTitle->getNextRevisionId( $oldid );
				}
				$oldid = $oldid ? $oldid : -1;
				break;
			case 'prev':
				# output previous revision, or nothing if there isn't one
				if ( ! $oldid ) {
					# get the current revision so we can get the penultimate one
					$this->mArticle->getTouched();
					$oldid = $this->mArticle->mLatest;
				}
				$prev = $this->mTitle->getPreviousRevisionId( $oldid );
				$oldid = $prev ? $prev : -1 ;
				break;
			case 'cur':
				$oldid = 0;
				break;
		}
		$this->mOldId = $oldid;
		
		# special case for 'generated' raw things: user css/js
		$gen = $this->mRequest->getVal( 'gen' );

		if($gen == 'css') {
			$this->mGen = $gen;
			if( is_null( $smaxage ) ) $smaxage = $wgSquidMaxage;
			if($ctype == '') $ctype = 'text/css';
		} elseif ($gen == 'js') {
			$this->mGen = $gen;
			if( is_null( $smaxage ) ) $smaxage = $wgSquidMaxage;
			if($ctype == '') $ctype = $wgJsMimeType;
		} else {
			$this->mGen = false;
		}
		$this->mCharset = $wgInputEncoding;
		$this->mSmaxage = intval( $smaxage );
		$this->mMaxage = $maxage;
		if ( $ctype == '' or ! in_array( $ctype, $allowedCTypes ) ) {
			$this->mContentType = 'text/x-wiki';
		} else {
			$this->mContentType = $ctype;
		}
	}

	function view() {
		global $wgOut, $wgScript;

		if( isset( $_SERVER['SCRIPT_URL'] ) ) {
			# Normally we use PHP_SELF to get the URL to the script
			# as it was called, minus the query string.
			#
			# Some sites use Apache rewrite rules to handle subdomains,
			# and have PHP set up in a weird way that causes PHP_SELF
			# to contain the rewritten URL instead of the one that the
			# outside world sees.
			#
			# If in this mode, use SCRIPT_URL instead, which mod_rewrite
			# provides containing the "before" URL.
			$url = $_SERVER['SCRIPT_URL'];
		} else {
			$url = $_SERVER['PHP_SELF'];
		}
		
		$ua = @$_SERVER['HTTP_USER_AGENT'];
		if( strcmp( $wgScript, $url ) && strpos( $ua, 'MSIE' ) !== false ) {
			# Internet Explorer will ignore the Content-Type header if it
			# thinks it sees a file extension it recognizes. Make sure that
			# all raw requests are done through the script node, which will
			# have eg '.php' and should remain safe.
			#
			# We used to redirect to a canonical-form URL as a general
			# backwards-compatibility / good-citizen nice thing. However
			# a lot of servers are set up in buggy ways, resulting in
			# redirect loops which hang the browser until the CSS load
			# times out.
			#
			# Just return a 403 Forbidden and get it over with.
			wfHttpError( 403, 'Forbidden',
				'Raw pages must be accessed through the primary script entry point.' );
			return;
		}

		header( "Content-type: ".$this->mContentType.'; charset='.$this->mCharset );
		# allow the client to cache this for 24 hours
		header( 'Cache-Control: s-maxage='.$this->mSmaxage.', max-age='.$this->mMaxage );
		echo $this->getRawText();
		$wgOut->disable();
	}

	function getRawText() {
		global $wgUser, $wgOut;
		if($this->mGen) {
			$sk = $wgUser->getSkin();
			$sk->initPage($wgOut);
			if($this->mGen == 'css') {
				return $sk->getUserStylesheet();
			} else if($this->mGen == 'js') {
				return $sk->getUserJs();
			}
		} else {
			return $this->getArticleText();
		}
	}

	function getArticleText() {
		if( $this->mTitle ) {
			$text = '';

			// If it's a MediaWiki message we can just hit the message cache
			if ( $this->mTitle->getNamespace() == NS_MEDIAWIKI ) {
				$text = wfMsgForContentNoTrans( $this->mTitle->getDbkey() );
			} else {
				// Get it from the DB
				$rev = Revision::newFromTitle( $this->mTitle, $this->mOldId );
				if ( $rev ) {
					$lastmod = wfTimestamp( TS_RFC2822, $rev->getTimestamp() );
					header( "Last-modified: $lastmod" );
					$text = $rev->getText();
				} else
					$text = '';
			}

			return $this->parseArticleText( $text );
		}

		# Bad title or page does not exist
		if( $this->mContentType == 'text/x-wiki' ) {
			# Don't return a 404 response for CSS or JavaScript;
			# 404s aren't generally cached and it would create
			# extra hits when user CSS/JS are on and the user doesn't
			# have the pages.
			header( "HTTP/1.0 404 Not Found" );
		}
		return '';
	}

	function parseArticleText( $text ) {
		if ( $text === '' )
			return '';
		else
			if ( $this->mExpandTemplates ) {
				global $wgTitle;

				$parser = new Parser();
				$parser->Options( new ParserOptions() ); // We don't want this to be user-specific
				$parser->Title( $wgTitle );
				$parser->OutputType( OT_HTML );

				return $parser->replaceVariables( $text );
			} else
				return $text;
	}
}
?>
	function MediaWikiCredits() {
		global $wgVersion;

		$dbr =& wfGetDB( DB_SLAVE );

		$ret =
		"__NOTOC__
		This wiki is powered by '''[http://www.mediawiki.org/ MediaWiki]''',
		copyright (C) 2001-2006 Magnus Manske, Brion Vibber, Lee Daniel Crocker,
		Tim Starling, Erik Möller, Gabriel Wicke and others.

		MediaWiki is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License as published by
		the Free Software Foundation; either version 2 of the License, or
		(at your option) any later version.

		MediaWiki is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		You should have received [{{SERVER}}{{SCRIPTPATH}}/COPYING a copy of the GNU General Public License]
		along with this program; if not, write to the Free Software
		Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
		or [http://www.gnu.org/copyleft/gpl.html read it online]

		* [http://www.mediawiki.org/ MediaWiki]: $wgVersion
		* [http://www.php.net/ PHP]: " . phpversion() . " (" . php_sapi_name() . ")
		* " . $dbr->getSoftwareLink() . ": " . $dbr->getServerVersion();

		return str_replace( "\t\t", '', $ret );
	}

	function extensionCredits() {
		global $wgExtensionCredits, $wgExtensionFunctions, $wgParser, $wgSkinExtensionFunction;

		if ( ! count( $wgExtensionCredits ) && ! count( $wgExtensionFunctions ) && ! count( $wgSkinExtensionFunction ) )
			return '';

		$extensionTypes = array(
			'specialpage' => 'Special pages',
			'parserhook' => 'Parser hooks',
			'variable' => 'Variables',
			'other' => 'Other',
		);
		wfRunHooks( 'SpecialVersionExtensionTypes', array( &$this, &$extensionTypes ) );

		$out = "\n* Extensions:\n";
		foreach ( $extensionTypes as $type => $text ) {
			if ( count( @$wgExtensionCredits[$type] ) ) {
				$out .= "** $text:\n";

				usort( $wgExtensionCredits[$type], array( $this, 'compare' ) );

				foreach ( $wgExtensionCredits[$type] as $extension ) {
					wfSuppressWarnings();
					$out .= $this->formatCredits(
						$extension['name'],
						$extension['version'],
						$extension['author'],
						$extension['url'],
						$extension['description']
					);
					wfRestoreWarnings();
				}
			}
		}

		if ( count( $wgExtensionFunctions ) ) {
			$out .= "** Extension functions:\n";
			$out .= '***' . $this->listToText( $wgExtensionFunctions ) . "\n";
		}

		if ( $cnt = count( $tags = $wgParser->getTags() ) ) {
			for ( $i = 0; $i < $cnt; ++$i )
				$tags[$i] = "&lt;{$tags[$i]}&gt;";
			$out .= "** Parser extension tags:\n";
			$out .= '***' . $this->listToText( $tags ). "\n";
		}

		if ( count( $wgSkinExtensionFunction ) ) {
			$out .= "** Skin extension functions:\n";
			$out .= '***' . $this->listToText( $wgSkinExtensionFunction ) . "\n";
		}

		return $out;
	}

	function compare( $a, $b ) {
		if ( $a['name'] === $b['name'] )
			return 0;
		else
			return LanguageUtf8::lc( $a['name'] ) > LanguageUtf8::lc( $b['name'] ) ? 1 : -1;
	}

	function formatCredits( $name, $version = null, $author = null, $url = null, $description = null) {
		$ret = '*** ';
		if ( isset( $url ) )
			$ret .= "[$url ";
		$ret .= "''$name";
		if ( isset( $version ) )
			$ret .= " (version $version)";
		$ret .= "''";
		if ( isset( $url ) )
			$ret .= ']';
		if ( isset( $description ) )
			$ret .= ', ' . $description;
		if ( isset( $description ) && isset( $author ) )
			$ret .= ', ';
		if ( isset( $author ) )
			$ret .= ' by ' . $this->listToText( (array)$author );

		return "$ret\n";
	}

	/**
	 * @return string
	 */
	function wgHooks() {
		global $wgHooks;

		if ( count( $wgHooks ) ) {
			$myWgHooks = $wgHooks;
			ksort( $myWgHooks );
			
			$ret = "* Hooks:\n";
			foreach ($myWgHooks as $hook => $hooks)
				$ret .= "** $hook: " . $this->listToText( $hooks ) . "\n";
			
			return $ret;
		} else
			return '';
	}

	/**
	 * @static
	 *
	 * @return string
	 */
	function IPInfo() {
		$ip =  str_replace( '--', ' - ', htmlspecialchars( wfGetIP() ) );
		return "<!-- visited from $ip -->\n";
	}

	/**
	 * @param array $list
	 * @return string
	 */
	function listToText( $list ) {
		$cnt = count( $list );

	    if ( $cnt == 1 )
			// Enforce always returning a string
			return (string)$this->arrayToString( $list[0] );
	    else {
			$t = array_slice( $list, 0, $cnt - 1 );
			$one = array_map( array( &$this, 'arrayToString' ), $t );
			$two = $this->arrayToString( $list[$cnt - 1] );
			
			return implode( ', ', $one ) . " and $two";
	    }
	}

	/**
	 * @static
	 *
	 * @param mixed $list Will convert an array to string if given and return
	 *                    the paramater unaltered otherwise
	 * @return mixed
	 */
	function arrayToString( $list ) {
		if ( ! is_array( $list ) )
			return $list;
		else {
			$class = get_class( $list[0] );
			return "($class, {$list[1]})";
		}
	}

	/**#@-*/
}

/**#@-*/
?>
