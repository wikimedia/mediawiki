<?php
/**
 * A codec for MediaWiki page titles.
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
 * @author Daniel Kinzler
 */
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageReference;
use Wikimedia\IPUtils;

/**
 * A codec for MediaWiki page titles.
 *
 * @note Normalization and validation is applied while parsing, not when formatting.
 * It's possible to construct a TitleValue with an invalid title, and use MediaWikiTitleCodec
 * to generate an (invalid) title string from it. TitleValues should be constructed only
 * via parseTitle() or from a (semi)trusted source, such as the database.
 *
 * @see https://www.mediawiki.org/wiki/Requests_for_comment/TitleValue
 * @since 1.23
 */
class MediaWikiTitleCodec implements TitleFormatter, TitleParser {
	/** @var Language */
	protected $language;

	/** @var GenderCache */
	protected $genderCache;

	/** @var string[] */
	protected $localInterwikis;

	/** @var InterwikiLookup */
	protected $interwikiLookup;

	/** @var NamespaceInfo */
	protected $nsInfo;

	/**
	 * The code here can throw MalformedTitleException, which cannot be created in
	 * unit tests (see T281935). Until that changes, we use this helper callback
	 * that can be overridden in unit tests to return a mock instead.
	 *
	 * @var callable
	 */
	private $createMalformedTitleException;

	/**
	 * @param Language $language The language object to use for localizing namespace names,
	 *   capitalization, etc.
	 * @param GenderCache $genderCache The gender cache for generating gendered namespace names
	 * @param string[]|string $localInterwikis
	 * @param InterwikiLookup $interwikiLookup
	 * @param NamespaceInfo $nsInfo
	 */
	public function __construct(
		Language $language,
		GenderCache $genderCache,
		$localInterwikis,
		InterwikiLookup $interwikiLookup,
		NamespaceInfo $nsInfo
	) {
		$this->language = $language;
		$this->genderCache = $genderCache;
		$this->localInterwikis = (array)$localInterwikis;
		$this->interwikiLookup = $interwikiLookup;
		$this->nsInfo = $nsInfo;

		// Default callback is to return a real MalformedTitleException,
		// callback signature matches constructor
		$this->createMalformedTitleException = static function (
			$errorMessage,
			$titleText = null,
			$errorMessageParameters = []
		): MalformedTitleException {
			return new MalformedTitleException( $errorMessage, $titleText, $errorMessageParameters );
		};
	}

	/**
	 * @internal
	 * @param callable $callback
	 */
	public function overrideCreateMalformedTitleExceptionCallback( callable $callback ) {
		// @codeCoverageIgnoreStart
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new RuntimeException( __METHOD__ . ' can only be used in tests' );
		}
		// @codeCoverageIgnoreEnd
		$this->createMalformedTitleException = $callback;
	}

	/**
	 * @see TitleFormatter::getNamespaceName()
	 *
	 * @param int $namespace
	 * @param string $text
	 *
	 * @throws InvalidArgumentException If the namespace is invalid
	 * @return string Namespace name with underscores (not spaces), e.g. 'User_talk'
	 */
	public function getNamespaceName( $namespace, $text ) {
		if ( $this->language->needsGenderDistinction() &&
			$this->nsInfo->hasGenderDistinction( $namespace )
		) {
			// NOTE: we are assuming here that the title text is a user name!
			$gender = $this->genderCache->getGenderOf( $text, __METHOD__ );
			$name = $this->language->getGenderNsText( $namespace, $gender );
		} else {
			$name = $this->language->getNsText( $namespace );
		}

		if ( $name === false ) {
			throw new InvalidArgumentException( 'Unknown namespace ID: ' . $namespace );
		}

		return $name;
	}

	/**
	 * @see TitleFormatter::formatTitle()
	 *
	 * @param int|bool $namespace The namespace ID (or false, if the namespace should be ignored)
	 * @param string $text The page title. Should be valid. Only minimal normalization is applied.
	 *        Underscores will be replaced.
	 * @param string $fragment The fragment name (may be empty).
	 * @param string $interwiki The interwiki name (may be empty).
	 *
	 * @throws InvalidArgumentException If the namespace is invalid
	 * @return string
	 */
	public function formatTitle( $namespace, $text, $fragment = '', $interwiki = '' ) {
		$out = '';
		if ( $interwiki !== '' ) {
			$out = $interwiki . ':';
		}

		if ( $namespace != 0 ) {
			try {
				$nsName = $this->getNamespaceName( $namespace, $text );
			} catch ( InvalidArgumentException $e ) {
				// See T165149. Awkward, but better than erroneously linking to the main namespace.
				$nsName = $this->language->getNsText( NS_SPECIAL ) . ":Badtitle/NS{$namespace}";
			}

			$out .= $nsName . ':';
		}
		$out .= $text;

		if ( $fragment !== '' ) {
			$out .= '#' . $fragment;
		}

		$out = str_replace( '_', ' ', $out );

		return $out;
	}

	/**
	 * Parses the given text and constructs a TitleValue.
	 *
	 * @param string $text The text to parse
	 * @param int $defaultNamespace Namespace to assume per default (usually NS_MAIN)
	 *
	 * @throws MalformedTitleException
	 * @return TitleValue
	 */
	public function parseTitle( $text, $defaultNamespace = NS_MAIN ) {
		// Convert things like &eacute; &#257; or &#x3017; into normalized (T16952) text
		$filteredText = Sanitizer::decodeCharReferencesAndNormalize( $text );

		// NOTE: this is an ugly kludge that allows this class to share the
		// code for parsing with the old Title class. The parser code should
		// be refactored to avoid this.
		$parts = $this->splitTitleString( $filteredText, $defaultNamespace );

		return new TitleValue(
			$parts['namespace'],
			$parts['dbkey'],
			$parts['fragment'],
			$parts['interwiki']
		);
	}

	/**
	 * Given a namespace and title, return a TitleValue if valid, or null if invalid.
	 *
	 * @param int $namespace
	 * @param string $text
	 * @param string $fragment
	 * @param string $interwiki
	 *
	 * @return TitleValue|null
	 */
	public function makeTitleValueSafe( $namespace, $text, $fragment = '', $interwiki = '' ) {
		if ( !$this->nsInfo->exists( $namespace ) ) {
			return null;
		}

		$canonicalNs = $this->nsInfo->getCanonicalName( $namespace );
		$fullText = $canonicalNs == '' ? $text : "$canonicalNs:$text";
		if ( strval( $interwiki ) != '' ) {
			$fullText = "$interwiki:$fullText";
		}
		if ( strval( $fragment ) != '' ) {
			$fullText .= '#' . $fragment;
		}

		try {
			$parts = $this->splitTitleString( $fullText );
		} catch ( MalformedTitleException $e ) {
			return null;
		}

		return new TitleValue(
			$parts['namespace'], $parts['dbkey'], $parts['fragment'], $parts['interwiki'] );
	}

	/**
	 * @see TitleFormatter::getText()
	 *
	 * @param LinkTarget|PageReference $title
	 *
	 * @return string
	 */
	public function getText( $title ) {
		if ( $title instanceof LinkTarget ) {
			return $title->getText();
		} elseif ( $title instanceof PageReference ) {
			return strtr( $title->getDBKey(), '_', ' ' );
		} else {
			throw new InvalidArgumentException( '$title has invalid type: ' . get_class( $title ) );
		}
	}

	/**
	 * @see TitleFormatter::getText()
	 *
	 * @param LinkTarget|PageReference $title
	 *
	 * @return string
	 * @suppress PhanUndeclaredProperty
	 */
	public function getPrefixedText( $title ) {
		if ( $title instanceof LinkTarget ) {
			if ( !isset( $title->prefixedText ) ) {
				$title->prefixedText = $this->formatTitle(
					$title->getNamespace(),
					$title->getText(),
					'',
					$title->getInterwiki()
				);
			}
			return $title->prefixedText;
		} elseif ( $title instanceof PageReference ) {
			$title->assertWiki( PageReference::LOCAL );
			return $this->formatTitle(
				$title->getNamespace(),
				$this->getText( $title )
			);
		} else {
			throw new InvalidArgumentException( '$title has invalid type: ' . get_class( $title ) );
		}
	}

	/**
	 * @since 1.27
	 * @see TitleFormatter::getPrefixedDBkey()
	 * @param LinkTarget|PageReference $target
	 * @return string
	 */
	public function getPrefixedDBkey( $target ) {
		if ( $target instanceof LinkTarget ) {
			return strtr( $this->formatTitle(
				$target->getNamespace(),
				$target->getDBkey(),
				'',
				$target->getInterwiki()
			), ' ', '_' );
		} elseif ( $target instanceof PageReference ) {
			$target->assertWiki( PageReference::LOCAL );
			return strtr( $this->formatTitle(
				$target->getNamespace(),
				$target->getDBkey()
			), ' ', '_' );
		} else {
			throw new InvalidArgumentException( '$title has invalid type: ' . get_class( $target ) );
		}
	}

	/**
	 * @see TitleFormatter::getText()
	 *
	 * @param LinkTarget|PageReference $title
	 *
	 * @return string
	 */
	public function getFullText( $title ) {
		if ( $title instanceof LinkTarget ) {
			return $this->formatTitle(
				$title->getNamespace(),
				$title->getText(),
				$title->getFragment(),
				$title->getInterwiki()
			);
		} elseif ( $title instanceof PageReference ) {
			$title->assertWiki( PageReference::LOCAL );
			return $this->formatTitle(
				$title->getNamespace(),
				$this->getText( $title )
			);
		} else {
			throw new InvalidArgumentException( '$title has invalid type: ' . get_class( $title ) );
		}
	}

	/**
	 * Validates, normalizes and splits a title string.
	 * This is the "source of truth" for title validity.
	 *
	 * This function removes illegal characters, splits off the interwiki and
	 * namespace prefixes, sets the other forms, and canonicalizes
	 * everything.
	 *
	 * @todo this method is only exposed as a temporary measure to ease refactoring.
	 * It was copied with minimal changes from Title::secureAndSplit().
	 *
	 * @todo This method should be split up and an appropriate interface
	 * defined for use by the Title class.
	 *
	 * @param string $text
	 * @param int $defaultNamespace
	 *
	 * @internal
	 * @throws MalformedTitleException If $text is not a valid title string.
	 * @return array A map with the fields 'interwiki', 'fragment', 'namespace', and 'dbkey'.
	 */
	public function splitTitleString( $text, $defaultNamespace = NS_MAIN ) {
		$dbkey = str_replace( ' ', '_', $text );

		# Initialisation
		$parts = [
			'interwiki' => '',
			'local_interwiki' => false,
			'fragment' => '',
			'namespace' => (int)$defaultNamespace,
			'dbkey' => $dbkey,
		];

		# Strip Unicode bidi override characters.
		# Sometimes they slip into cut-n-pasted page titles, where the
		# override chars get included in list displays.
		$dbkey = preg_replace( '/[\x{200E}\x{200F}\x{202A}-\x{202E}]+/u', '', $dbkey );

		if ( $dbkey === null ) {
			# Regex had an error. Most likely this is caused by invalid UTF-8
			$exception = ( $this->createMalformedTitleException )( 'title-invalid-utf8', $text );
			throw $exception;
		}

		# Clean up whitespace
		# Note: use of the /u option on preg_replace here will cause
		# input with invalid UTF-8 sequences to be nullified out in PHP 5.2.x,
		# conveniently disabling them.
		$dbkey = preg_replace(
			'/[ _\xA0\x{1680}\x{180E}\x{2000}-\x{200A}\x{2028}\x{2029}\x{202F}\x{205F}\x{3000}]+/u',
			'_',
			$dbkey
		);
		$dbkey = trim( $dbkey, '_' );

		if ( strpos( $dbkey, UtfNormal\Constants::UTF8_REPLACEMENT ) !== false ) {
			# Contained illegal UTF-8 sequences or forbidden Unicode chars.
			$exception = ( $this->createMalformedTitleException )( 'title-invalid-utf8', $text );
			throw $exception;
		}

		$parts['dbkey'] = $dbkey;

		# Initial colon indicates main namespace rather than specified default
		# but should not create invalid {ns,title} pairs such as {0,Project:Foo}
		if ( $dbkey !== '' && $dbkey[0] == ':' ) {
			$parts['namespace'] = NS_MAIN;
			$dbkey = substr( $dbkey, 1 ); # remove the colon but continue processing
			$dbkey = trim( $dbkey, '_' ); # remove any subsequent whitespace
		}

		if ( $dbkey == '' ) {
			$exception = ( $this->createMalformedTitleException )( 'title-invalid-empty', $text );
			throw $exception;
		}

		# Namespace or interwiki prefix
		$prefixRegexp = "/^(.+?)_*:_*(.*)$/S";
		do {
			$m = [];
			if ( preg_match( $prefixRegexp, $dbkey, $m ) ) {
				$p = $m[1];
				$ns = $this->language->getNsIndex( $p );
				if ( $ns !== false ) {
					# Ordinary namespace
					$dbkey = $m[2];
					$parts['namespace'] = $ns;
					# For Talk:X pages, check if X has a "namespace" prefix
					if ( $ns === NS_TALK && preg_match( $prefixRegexp, $dbkey, $x ) ) {
						if ( $this->language->getNsIndex( $x[1] ) ) {
							# Disallow Talk:File:x type titles...
							$exception = ( $this->createMalformedTitleException )(
								'title-invalid-talk-namespace',
								$text
							);
							throw $exception;
						} elseif ( $this->interwikiLookup->isValidInterwiki( $x[1] ) ) {
							# Disallow Talk:Interwiki:x type titles...
							$exception = ( $this->createMalformedTitleException )(
								'title-invalid-talk-namespace',
								$text
							);
							throw $exception;
						}
					}
				} elseif ( $this->interwikiLookup->isValidInterwiki( $p ) ) {
					# Interwiki link
					$dbkey = $m[2];
					$parts['interwiki'] = $this->language->lc( $p );

					# Redundant interwiki prefix to the local wiki
					foreach ( $this->localInterwikis as $localIW ) {
						if ( strcasecmp( $parts['interwiki'], $localIW ) == 0 ) {
							if ( $dbkey == '' ) {
								# Empty self-links should point to the Main Page, to ensure
								# compatibility with cross-wiki transclusions and the like.
								$mainPage = Title::newMainPage();
								return [
									'interwiki' => $mainPage->getInterwiki(),
									'local_interwiki' => true,
									'fragment' => $mainPage->getFragment(),
									'namespace' => $mainPage->getNamespace(),
									'dbkey' => $mainPage->getDBkey(),
								];
							}
							$parts['interwiki'] = '';
							# local interwikis should behave like initial-colon links
							$parts['local_interwiki'] = true;

							# Do another namespace split...
							continue 2;
						}
					}

					# If there's an initial colon after the interwiki, that also
					# resets the default namespace
					if ( $dbkey !== '' && $dbkey[0] == ':' ) {
						$parts['namespace'] = NS_MAIN;
						$dbkey = substr( $dbkey, 1 );
						$dbkey = trim( $dbkey, '_' );
					}
				}
				# If there's no recognized interwiki or namespace,
				# then let the colon expression be part of the title.
			}
			break;
		} while ( true );

		$fragment = strstr( $dbkey, '#' );
		if ( $fragment !== false ) {
			$parts['fragment'] = str_replace( '_', ' ', substr( $fragment, 1 ) );
			$dbkey = substr( $dbkey, 0, strlen( $dbkey ) - strlen( $fragment ) );
			# remove whitespace again: prevents "Foo_bar_#"
			# becoming "Foo_bar_"
			$dbkey = preg_replace( '/_*$/', '', $dbkey );
		}

		# Reject illegal characters.
		$rxTc = self::getTitleInvalidRegex();
		$matches = [];
		if ( preg_match( $rxTc, $dbkey, $matches ) ) {
			$exception = ( $this->createMalformedTitleException )( 'title-invalid-characters', $text, [ $matches[0] ] );
			throw $exception;
		}

		# Pages with "/./" or "/../" appearing in the URLs will often be un-
		# reachable due to the way web browsers deal with 'relative' URLs.
		# Also, they conflict with subpage syntax.  Forbid them explicitly.
		if (
			strpos( $dbkey, '.' ) !== false &&
			(
				$dbkey === '.' || $dbkey === '..' ||
				strpos( $dbkey, './' ) === 0 ||
				strpos( $dbkey, '../' ) === 0 ||
				strpos( $dbkey, '/./' ) !== false ||
				strpos( $dbkey, '/../' ) !== false ||
				substr( $dbkey, -2 ) == '/.' ||
				substr( $dbkey, -3 ) == '/..'
			)
		) {
			$exception = ( $this->createMalformedTitleException )( 'title-invalid-relative', $text );
			throw $exception;
		}

		# Magic tilde sequences? Nu-uh!
		if ( strpos( $dbkey, '~~~' ) !== false ) {
			$exception = ( $this->createMalformedTitleException )( 'title-invalid-magic-tilde', $text );
			throw $exception;
		}

		# Limit the size of titles to 255 bytes. This is typically the size of the
		# underlying database field. We make an exception for special pages, which
		# don't need to be stored in the database, and may edge over 255 bytes due
		# to subpage syntax for long titles, e.g. [[Special:Block/Long name]]
		$maxLength = ( $parts['namespace'] !== NS_SPECIAL ) ? 255 : 512;
		if ( strlen( $dbkey ) > $maxLength ) {
			$exception = ( $this->createMalformedTitleException )(
				'title-invalid-too-long',
				$text,
				[ Message::numParam( $maxLength ) ]
			);
			throw $exception;
		}

		# Normally, all wiki links are forced to have an initial capital letter so [[foo]]
		# and [[Foo]] point to the same place.  Don't force it for interwikis, since the
		# other site might be case-sensitive.
		if ( $parts['interwiki'] === '' && $this->nsInfo->isCapitalized( $parts['namespace'] ) ) {
			$dbkey = $this->language->ucfirst( $dbkey );
		}

		# Can't make a link to a namespace alone... "empty" local links can only be
		# self-links with a fragment identifier.
		if ( $dbkey == '' && $parts['interwiki'] === '' && $parts['namespace'] !== NS_MAIN ) {
			$exception = ( $this->createMalformedTitleException )( 'title-invalid-empty', $text );
			throw $exception;
		}

		// Allow IPv6 usernames to start with '::' by canonicalizing IPv6 titles.
		// IP names are not allowed for accounts, and can only be referring to
		// edits from the IP. Given '::' abbreviations and caps/lowercaps,
		// there are numerous ways to present the same IP. Having sp:contribs scan
		// them all is silly and having some show the edits and others not is
		// inconsistent. Same for talk/userpages. Keep them normalized instead.
		if ( $parts['namespace'] === NS_USER || $parts['namespace'] === NS_USER_TALK ) {
			$dbkey = IPUtils::sanitizeIP( $dbkey );
			// IPUtils::sanitizeIP return null only for bad input
			'@phan-var string $dbkey';
		}

		// Any remaining initial :s are illegal.
		if ( $dbkey !== '' && $dbkey[0] == ':' ) {
			$exception = ( $this->createMalformedTitleException )( 'title-invalid-leading-colon', $text );
			throw $exception;
		}

		// Fill fields
		$parts['dbkey'] = $dbkey;

		// Check to ensure that the return value can be used to construct a TitleValue.
		// All issues should in theory be caught above, this is here to enforce consistency.
		try {
			TitleValue::assertValidSpec(
				$parts['namespace'],
				$parts['dbkey'],
				$parts['fragment'],
				$parts['interwiki']
			);
		} catch ( InvalidArgumentException $ex ) {
			$exception = ( $this->createMalformedTitleException )( 'title-invalid', $text, [ $ex->getMessage() ] );
			throw $exception;
		}

		return $parts;
	}

	/**
	 * Returns a simple regex that will match on characters and sequences invalid in titles.
	 * Note that this doesn't pick up many things that could be wrong with titles, but that
	 * replacing this regex with something valid will make many titles valid.
	 * Previously Title::getTitleInvalidRegex()
	 *
	 * @return string Regex string
	 * @since 1.25
	 */
	public static function getTitleInvalidRegex() {
		static $rxTc = false;
		if ( !$rxTc ) {
			# Matching titles will be held as illegal.
			$rxTc = '/' .
				# Any character not allowed is forbidden...
				'[^' . Title::legalChars() . ']' .
				# URL percent encoding sequences interfere with the ability
				# to round-trip titles -- you can't link to them consistently.
				'|%[0-9A-Fa-f]{2}' .
				# XML/HTML character references produce similar issues.
				'|&[A-Za-z0-9\x80-\xff]+;' .
				'/S';
		}

		return $rxTc;
	}
}
