<?php
/**
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

/**
 * @defgroup Language Language
 */

namespace MediaWiki\Languages;

use HashBagOStuff;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWikiTitleCodec;
use MWException;

/**
 * A service that provides utilities to do with language names and codes.
 *
 * See https://www.mediawiki.org/wiki/Special:MyLanguage/Localisation for more information.
 *
 * @since 1.34
 * @ingroup Language
 */
class LanguageNameUtils {
	/**
	 * Return autonyms in getLanguageName(s).
	 */
	public const AUTONYMS = null;

	/**
	 * Return all known languages in getLanguageName(s).
	 */
	public const ALL = 'all';

	/**
	 * Return in getLanguageName(s) only the languages that are defined by MediaWiki.
	 */
	public const DEFINED = 'mw';

	/**
	 * Return in getLanguageName(s) only the languages for which we have at least some localisation.
	 */
	public const SUPPORTED = 'mwfile';

	/** @var ServiceOptions */
	private $options;

	/**
	 * Cache for language names
	 * @var HashBagOStuff|null
	 */
	private $languageNameCache;

	/**
	 * Cache for validity of language codes
	 * @var array
	 */
	private $validCodeCache = [];

	public const CONSTRUCTOR_OPTIONS = [
		'ExtraLanguageNames',
		'UsePigLatinVariant',
	];

	/** @var HookRunner */
	private $hookRunner;

	/**
	 * @param ServiceOptions $options
	 * @param HookContainer $hookContainer
	 */
	public function __construct( ServiceOptions $options, HookContainer $hookContainer ) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/**
	 * Checks whether any localisation is available for that language tag in MediaWiki
	 * (MessagesXx.php or xx.json exists).
	 *
	 * @param string $code Language tag (in lower case)
	 * @return bool Whether language is supported
	 */
	public function isSupportedLanguage( string $code ) : bool {
		if ( !$this->isValidBuiltInCode( $code ) ) {
			return false;
		}

		if ( $code === 'qqq' ) {
			// Special code for internal use, not supported even though there is a qqq.json
			return false;
		}

		return is_readable( $this->getMessagesFileName( $code ) ) ||
			is_readable( $this->getJsonMessagesFileName( $code ) );
	}

	/**
	 * Returns true if a language code string is of a valid form, whether or not it exists. This
	 * includes codes which are used solely for customisation via the MediaWiki namespace.
	 *
	 * @param string $code
	 *
	 * @return bool
	 */
	public function isValidCode( string $code ) : bool {
		if ( !isset( $this->validCodeCache[$code] ) ) {
			// People think language codes are HTML-safe, so enforce it.  Ideally we should only
			// allow a-zA-Z0-9- but .+ and other chars are often used for {{int:}} hacks.  See bugs
			// T39564, T39587, T38938.
			$this->validCodeCache[$code] =
				// Protect against path traversal
				strcspn( $code, ":/\\\000&<>'\"" ) === strlen( $code ) &&
				!preg_match( MediaWikiTitleCodec::getTitleInvalidRegex(), $code );
		}
		return $this->validCodeCache[$code];
	}

	/**
	 * Returns true if a language code is of a valid form for the purposes of internal customisation
	 * of MediaWiki, via Messages*.php or *.json.
	 *
	 * @param string $code
	 * @return bool
	 */
	public function isValidBuiltInCode( string $code ) : bool {
		return (bool)preg_match( '/^[a-z0-9-]{2,}$/', $code );
	}

	/**
	 * Returns true if a language code is an IETF tag known to MediaWiki.
	 *
	 * @param string $tag
	 *
	 * @return bool
	 */
	public function isKnownLanguageTag( string $tag ) : bool {
		// Quick escape for invalid input to avoid exceptions down the line when code tries to
		// process tags which are not valid at all.
		if ( !$this->isValidBuiltInCode( $tag ) ) {
			return false;
		}

		if ( isset( Data\Names::$names[$tag] ) || $this->getLanguageName( $tag, $tag ) !== '' ) {
			return true;
		}

		return false;
	}

	/**
	 * Get an array of language names, indexed by code.
	 * @param null|string $inLanguage Code of language in which to return the names
	 *   Use self::AUTONYMS for autonyms (native names)
	 * @param string $include One of:
	 *   self::ALL all available languages
	 *   self::DEFINED only if the language is defined in MediaWiki or wgExtraLanguageNames
	 *     (default)
	 *   self::SUPPORTED only if the language is in self::DEFINED *and* has a message file
	 * @return array Language code => language name (sorted by key)
	 */
	public function getLanguageNames( $inLanguage = self::AUTONYMS, $include = self::DEFINED ) {
		$cacheKey = $inLanguage === self::AUTONYMS ? 'null' : $inLanguage;
		$cacheKey .= ":$include";
		if ( !$this->languageNameCache ) {
			$this->languageNameCache = new HashBagOStuff( [ 'maxKeys' => 20 ] );
		}

		$ret = $this->languageNameCache->get( $cacheKey );
		if ( !$ret ) {
			$ret = $this->getLanguageNamesUncached( $inLanguage, $include );
			$this->languageNameCache->set( $cacheKey, $ret );
		}
		return $ret;
	}

	/**
	 * Uncached helper for getLanguageNames
	 * @param null|string $inLanguage As getLanguageNames
	 * @param string $include As getLanguageNames
	 * @return array Language code => language name (sorted by key)
	 */
	private function getLanguageNamesUncached( $inLanguage, $include ) {
		// If passed an invalid language code to use, fallback to en
		if ( $inLanguage !== self::AUTONYMS && !$this->isValidCode( $inLanguage ) ) {
			$inLanguage = 'en';
		}

		$names = [];

		if ( $inLanguage !== self::AUTONYMS ) {
			# TODO: also include for self::AUTONYMS, when this code is more efficient
			$this->hookRunner->onLanguageGetTranslatedLanguageNames( $names, $inLanguage );
		}

		$mwNames = $this->options->get( 'ExtraLanguageNames' ) + Data\Names::$names;
		if ( $this->options->get( 'UsePigLatinVariant' ) ) {
			// Pig Latin (for variant development)
			$mwNames['en-x-piglatin'] = 'Igpay Atinlay';
		}

		foreach ( $mwNames as $mwCode => $mwName ) {
			# - Prefer own MediaWiki native name when not using the hook
			# - For other names just add if not added through the hook
			if ( $mwCode === $inLanguage || !isset( $names[$mwCode] ) ) {
				$names[$mwCode] = $mwName;
			}
		}

		if ( $include === self::ALL ) {
			ksort( $names );
			return $names;
		}

		$returnMw = [];
		$coreCodes = array_keys( $mwNames );
		foreach ( $coreCodes as $coreCode ) {
			$returnMw[$coreCode] = $names[$coreCode];
		}

		if ( $include === self::SUPPORTED ) {
			$namesMwFile = [];
			# We do this using a foreach over the codes instead of a directory loop so that messages
			# files in extensions will work correctly.
			foreach ( $returnMw as $code => $value ) {
				if ( is_readable( $this->getMessagesFileName( $code ) ) ||
					is_readable( $this->getJsonMessagesFileName( $code ) )
				) {
					$namesMwFile[$code] = $names[$code];
				}
			}

			ksort( $namesMwFile );
			return $namesMwFile;
		}

		ksort( $returnMw );
		# self::DEFINED option; default if it's not one of the other two options
		# (self::ALL/self::SUPPORTED)
		return $returnMw;
	}

	/**
	 * @param string $code The code of the language for which to get the name
	 * @param null|string $inLanguage Code of language in which to return the name (self::AUTONYMS
	 *   for autonyms)
	 * @param string $include See getLanguageNames(), except this defaults to self::ALL instead of
	 *   self::DEFINED
	 * @return string Language name or empty
	 * @since 1.20
	 */
	public function getLanguageName( $code, $inLanguage = self::AUTONYMS, $include = self::ALL ) {
		$code = strtolower( $code );
		$array = $this->getLanguageNames( $inLanguage, $include );
		return $array[$code] ?? '';
	}

	/**
	 * Get the name of a file for a certain language code
	 * @param string $prefix Prepend this to the filename
	 * @param string $code Language code
	 * @param string $suffix Append this to the filename
	 * @throws MWException
	 * @return string $prefix . $mangledCode . $suffix
	 */
	public function getFileName( $prefix, $code, $suffix = '.php' ) {
		if ( !$this->isValidBuiltInCode( $code ) ) {
			throw new MWException( "Invalid language code \"$code\"" );
		}

		return $prefix . str_replace( '-', '_', ucfirst( $code ) ) . $suffix;
	}

	/**
	 * @param string $code
	 * @return string
	 */
	public function getMessagesFileName( $code ) {
		global $IP;
		$file = $this->getFileName( "$IP/languages/messages/Messages", $code, '.php' );
		$this->hookRunner->onLanguage__getMessagesFileName( $code, $file );
		return $file;
	}

	/**
	 * @param string $code
	 * @return string
	 * @throws MWException
	 */
	public function getJsonMessagesFileName( $code ) {
		global $IP;

		if ( !$this->isValidBuiltInCode( $code ) ) {
			throw new MWException( "Invalid language code \"$code\"" );
		}

		return "$IP/languages/i18n/$code.json";
	}
}
