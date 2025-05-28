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

use CLDRPluralRuleParser\Error as CLDRPluralRuleError;
use CLDRPluralRuleParser\Evaluator;
use MediaWiki\Config\ConfigException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Json\FormatJson;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\MainConfigNames;
use Psr\Log\LoggerInterface;

/**
 * Caching for the contents of localisation files.
 *
 * Including for i18n JSON files under `/languages/messages`, `Messages*.php`,
 * and `*.i18n.php`.
 *
 * An instance of this class is available using MediaWikiServices.
 *
 * The values retrieved from here are merged, containing items from extension
 * files, core messages files and the language fallback sequence (e.g. zh-cn ->
 * zh-hans -> en ). Some common errors are corrected, for example namespace
 * names with spaces instead of underscores, but heavyweight processing, such
 * as grammatical transformation, is done by the caller.
 *
 * @ingroup Language
 */
class LocalisationCache {
	public const VERSION = 5;

	/** @var ServiceOptions */
	private $options;

	/**
	 * True if re-caching should only be done on an explicit call to recache().
	 * Setting this reduces the overhead of cache freshness checking, which
	 * requires doing a stat() for every extension i18n file.
	 *
	 * @var bool
	 */
	private $manualRecache;

	/**
	 * The cache data. 2/3-d array, where the first key is the language code,
	 * the second key is the item key e.g. 'messages', and the optional third key is
	 * an item specific subkey index. Some items are not arrays, and so for those
	 * items, there are no subkeys.
	 *
	 * @var array<string,array>
	 */
	protected $data = [];

	/**
	 * The source language of cached data items. Only supports messages for now.
	 *
	 * @var array<string,array<string,array<string,string>>>
	 */
	protected $sourceLanguage = [];

	/** @var LCStore */
	private $store;
	/** @var LoggerInterface */
	private $logger;
	/** @var HookRunner */
	private $hookRunner;
	/** @var callable[] See comment for parameter in constructor */
	private $clearStoreCallbacks;
	/** @var LanguageNameUtils */
	private $langNameUtils;

	/**
	 * A 2-d associative array, code/key, where presence indicates that the item
	 * is loaded. Value arbitrary.
	 *
	 * For split items, if set, this indicates that all the subitems have been
	 * loaded.
	 *
	 * @var array<string,array<string,true>>
	 */
	private $loadedItems = [];

	/**
	 * A 3-d associative array, code/key/subkey, where presence indicates that
	 * the subitem is loaded. Only used for the split items, i.e. ,messages.
	 *
	 * @var array<string,array<string,array<string,true>>>
	 */
	private $loadedSubitems = [];

	/**
	 * An array where the presence of a key indicates that that language has been
	 * initialised. Initialisation includes checking for cache expiry and doing
	 * any necessary updates.
	 *
	 * @var array<string,true>
	 */
	private $initialisedLangs = [];

	/**
	 * An array mapping non-existent pseudo-languages to fallback languages. This
	 * is filled by initShallowFallback() when data is requested from a language
	 * that lacks a Messages*.php file.
	 *
	 * @var array<string,string>
	 */
	private $shallowFallbacks = [];

	/**
	 * An array where the keys are codes that have been re-cached by this instance.
	 *
	 * @var array<string,true>
	 */
	private $recachedLangs = [];

	/**
	 * An array indicating whether core data for a language has been loaded.
	 * If the entry for a language code $code is true,
	 * then {@link self::$data} is guaranteed to contain an array for $code,
	 * with at least an entry (possibly null) for each of the {@link self::CORE_ONLY_KEYS},
	 * and all the core-only keys will be marked as loaded in {@link self::$loadedItems} too.
	 * Additionally, there will be a 'deps' entry for $code with the dependencies tracked so far.
	 *
	 * @var array<string,bool>
	 */
	private $coreDataLoaded = [];

	/**
	 * All item keys
	 */
	public const ALL_KEYS = [
		'fallback', 'namespaceNames', 'bookstoreList',
		'magicWords', 'messages', 'rtl',
		'digitTransformTable', 'separatorTransformTable',
		'minimumGroupingDigits', 'numberingSystem', 'fallback8bitEncoding',
		'linkPrefixExtension', 'linkTrail', 'linkPrefixCharset',
		'namespaceAliases', 'dateFormats', 'jsDateFormats', 'datePreferences',
		'datePreferenceMigrationMap', 'defaultDateFormat',
		'specialPageAliases', 'imageFiles', 'preloadedMessages',
		'namespaceGenderAliases', 'digitGroupingPattern', 'pluralRules',
		'pluralRuleTypes', 'compiledPluralRules', 'formalityIndex'
	];

	/**
	 * Keys for items that can only be set in the core message files,
	 * not in extensions. Assignments to these keys in extension messages files
	 * are silently ignored.
	 *
	 * @since 1.41
	 */
	private const CORE_ONLY_KEYS = [
		'fallback', 'rtl', 'digitTransformTable', 'separatorTransformTable',
		'minimumGroupingDigits', 'numberingSystem',
		'fallback8bitEncoding', 'linkPrefixExtension',
		'linkTrail', 'linkPrefixCharset', 'datePreferences',
		'datePreferenceMigrationMap', 'defaultDateFormat', 'digitGroupingPattern',
		'formalityIndex',
	];

	/**
	 * ALL_KEYS - CORE_ONLY_KEYS. All of these can technically be set
	 * both in core and in extension messages files,
	 * though this is not necessarily useful for all these keys.
	 * Some of these keys are mergeable too.
	 *
	 * @since 1.41
	 */
	private const ALL_EXCEPT_CORE_ONLY_KEYS = [
		'namespaceNames', 'bookstoreList', 'magicWords', 'messages',
		'namespaceAliases', 'dateFormats', 'jsDateFormats', 'specialPageAliases',
		'imageFiles', 'preloadedMessages', 'namespaceGenderAliases',
		'pluralRules', 'pluralRuleTypes', 'compiledPluralRules',
	];

	/** Keys for items which can be localized. */
	public const ALL_ALIAS_KEYS = [ 'specialPageAliases' ];

	/**
	 * Keys for items which consist of associative arrays, which may be merged
	 * by a fallback sequence.
	 */
	private const MERGEABLE_MAP_KEYS = [ 'messages', 'namespaceNames',
		'namespaceAliases', 'dateFormats', 'jsDateFormats', 'imageFiles', 'preloadedMessages'
	];

	/**
	 * Keys for items which contain an array of arrays of equivalent aliases
	 * for each subitem. The aliases may be merged by a fallback sequence.
	 */
	private const MERGEABLE_ALIAS_LIST_KEYS = [ 'specialPageAliases' ];

	/**
	 * Keys for items which contain an associative array, and may be merged if
	 * the primary value contains the special array key "inherit". That array
	 * key is removed after the first merge.
	 */
	private const OPTIONAL_MERGE_KEYS = [ 'bookstoreList' ];

	/**
	 * Keys for items that are formatted like $magicWords
	 */
	private const MAGIC_WORD_KEYS = [ 'magicWords' ];

	/**
	 * Keys for items where the subitems are stored in the backend separately.
	 */
	private const SPLIT_KEYS = [ 'messages' ];

	/**
	 * Keys for items that will be prefixed with its source language code,
	 * which should be stripped out when loading from cache.
	 */
	private const SOURCE_PREFIX_KEYS = [ 'messages' ];

	/**
	 * Separator for the source language prefix.
	 */
	private const SOURCEPREFIX_SEPARATOR = ':';

	/**
	 * Keys which are loaded automatically by initLanguage()
	 */
	private const PRELOADED_KEYS = [ 'dateFormats', 'namespaceNames' ];

	private const PLURAL_FILES = [
		// Load CLDR plural rules
		MW_INSTALL_PATH . '/languages/data/plurals.xml',
		// Override or extend with MW-specific rules
		MW_INSTALL_PATH . '/languages/data/plurals-mediawiki.xml',
	];

	/**
	 * Associative array of cached plural rules. The key is the language code,
	 * the value is an array of plural rules for that language.
	 *
	 * @var array<string,array<int,string>>|null
	 */
	private static $pluralRules = null;

	/**
	 * Associative array of cached plural rule types. The key is the language
	 * code, the value is an array of plural rule types for that language. For
	 * example, $pluralRuleTypes['ar'] = ['zero', 'one', 'two', 'few', 'many'].
	 * The index for each rule type matches the index for the rule in
	 * $pluralRules, thus allowing correlation between the two. The reason we
	 * don't just use the type names as the keys in $pluralRules is because
	 * Language::convertPlural applies the rules based on numeric order (or
	 * explicit numeric parameter), not based on the name of the rule type. For
	 * example, {{plural:count|wordform1|wordform2|wordform3}}, rather than
	 * {{plural:count|one=wordform1|two=wordform2|many=wordform3}}.
	 *
	 * @var array<string,array<int,string>>|null
	 */
	private static $pluralRuleTypes = null;

	/**
	 * Return a suitable LCStore as specified by the given configuration.
	 *
	 * @since 1.34
	 * @param array $conf In the format of $wgLocalisationCacheConf
	 * @param string|false|null $fallbackCacheDir In case 'storeDirectory' isn't specified
	 * @return LCStore
	 */
	public static function getStoreFromConf( array $conf, $fallbackCacheDir ): LCStore {
		$storeArg = [];
		$storeArg['directory'] =
			$conf['storeDirectory'] ?: $fallbackCacheDir;

		if ( !empty( $conf['storeClass'] ) ) {
			$storeClass = $conf['storeClass'];
		} elseif ( $conf['store'] === 'files' || $conf['store'] === 'file' ||
			( $conf['store'] === 'detect' && $storeArg['directory'] )
		) {
			$storeClass = LCStoreCDB::class;
		} elseif ( $conf['store'] === 'db' || $conf['store'] === 'detect' ) {
			$storeClass = LCStoreDB::class;
			$storeArg['server'] = $conf['storeServer'] ?? [];
		} elseif ( $conf['store'] === 'array' ) {
			$storeClass = LCStoreStaticArray::class;
		} else {
			throw new ConfigException(
				'Please set $wgLocalisationCacheConf[\'store\'] to something sensible.'
			);
		}

		return new $storeClass( $storeArg );
	}

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		// True to treat all files as expired until they are regenerated by this object.
		'forceRecache',
		'manualRecache',
		MainConfigNames::ExtensionMessagesFiles,
		MainConfigNames::MessagesDirs,
		MainConfigNames::TranslationAliasesDirs,
	];

	/**
	 * For constructor parameters, @ref \MediaWiki\MainConfigSchema::LocalisationCacheConf.
	 *
	 * @internal Do not construct directly, use MediaWikiServices instead.
	 * @param ServiceOptions $options
	 * @param LCStore $store What backend to use for storage
	 * @param LoggerInterface $logger
	 * @param callable[] $clearStoreCallbacks To be called whenever the cache is cleared. Can be
	 *   used to clear other caches that depend on this one, such as ResourceLoader's
	 *   MessageBlobStore.
	 * @param LanguageNameUtils $langNameUtils
	 * @param HookContainer $hookContainer
	 */
	public function __construct(
		ServiceOptions $options,
		LCStore $store,
		LoggerInterface $logger,
		array $clearStoreCallbacks,
		LanguageNameUtils $langNameUtils,
		HookContainer $hookContainer
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->options = $options;
		$this->store = $store;
		$this->logger = $logger;
		$this->clearStoreCallbacks = $clearStoreCallbacks;
		$this->langNameUtils = $langNameUtils;
		$this->hookRunner = new HookRunner( $hookContainer );

		// Keep this separate from $this->options so that it can be mutable
		$this->manualRecache = $options->get( 'manualRecache' );
	}

	/**
	 * Returns true if the given key is mergeable, that is, if it is an associative
	 * array which can be merged through a fallback sequence.
	 * @param string $key
	 * @return bool
	 */
	private static function isMergeableKey( string $key ): bool {
		static $mergeableKeys;
		$mergeableKeys ??= array_fill_keys( [
			...self::MERGEABLE_MAP_KEYS,
			...self::MERGEABLE_ALIAS_LIST_KEYS,
			...self::OPTIONAL_MERGE_KEYS,
			...self::MAGIC_WORD_KEYS,
		], true );
		return isset( $mergeableKeys[$key] );
	}

	/**
	 * Get a cache item.
	 *
	 * Warning: this may be slow for split items (messages), since it will
	 * need to fetch all the subitems from the cache individually.
	 * @param string $code
	 * @param string $key
	 * @return mixed
	 */
	public function getItem( $code, $key ) {
		if ( !isset( $this->loadedItems[$code][$key] ) ) {
			$this->loadItem( $code, $key );
		}

		if ( $key === 'fallback' && isset( $this->shallowFallbacks[$code] ) ) {
			return $this->shallowFallbacks[$code];
		}

		// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
		return $this->data[$code][$key];
	}

	/**
	 * Get a subitem, for instance a single message for a given language.
	 * @param string $code
	 * @param string $key
	 * @param string $subkey
	 * @return mixed|null
	 */
	public function getSubitem( $code, $key, $subkey ) {
		if ( !isset( $this->loadedSubitems[$code][$key][$subkey] ) &&
			!isset( $this->loadedItems[$code][$key] )
		) {
			$this->loadSubitem( $code, $key, $subkey );
		}

		return $this->data[$code][$key][$subkey] ?? null;
	}

	/**
	 * Get a subitem with its source language. Only supports messages for now.
	 *
	 * @since 1.41
	 * @param string $code
	 * @param string $key
	 * @param string $subkey
	 * @return string[]|null Return [ subitem, sourceLanguage ] if the subitem is defined.
	 */
	public function getSubitemWithSource( $code, $key, $subkey ) {
		$subitem = $this->getSubitem( $code, $key, $subkey );
		// Undefined in the backend.
		if ( $subitem === null ) {
			return null;
		}

		// The source language should have been set, but to avoid a Phan error and to be double sure.
		return [ $subitem, $this->sourceLanguage[$code][$key][$subkey] ?? $code ];
	}

	/**
	 * Get the list of subitem keys for a given item.
	 *
	 * This is faster than array_keys($lc->getItem(...)) for the items listed in
	 * self::SPLIT_KEYS.
	 *
	 * Will return null if the item is not found, or false if the item is not an
	 * array.
	 *
	 * @param string $code
	 * @param string $key
	 * @return bool|null|string|string[]
	 */
	public function getSubitemList( $code, $key ) {
		if ( in_array( $key, self::SPLIT_KEYS ) ) {
			return $this->getSubitem( $code, 'list', $key );
		} else {
			$item = $this->getItem( $code, $key );
			if ( is_array( $item ) ) {
				return array_keys( $item );
			} else {
				return false;
			}
		}
	}

	/**
	 * Load an item into the cache.
	 *
	 * @param string $code
	 * @param string $key
	 */
	private function loadItem( $code, $key ) {
		if ( isset( $this->loadedItems[$code][$key] ) ) {
			return;
		}

		if (
			in_array( $key, self::CORE_ONLY_KEYS, true ) ||
			// "synthetic" keys added by loadCoreData based on "fallback"
			$key === 'fallbackSequence' ||
			$key === 'originalFallbackSequence'
		) {
			if ( $this->langNameUtils->isValidBuiltInCode( $code ) ) {
				$this->loadCoreData( $code );
				return;
			}
		}

		if ( !isset( $this->initialisedLangs[$code] ) ) {
			$this->initLanguage( $code );

			// Check to see if initLanguage() loaded it for us
			if ( isset( $this->loadedItems[$code][$key] ) ) {
				return;
			}
		}

		if ( isset( $this->shallowFallbacks[$code] ) ) {
			$this->loadItem( $this->shallowFallbacks[$code], $key );

			return;
		}

		if ( in_array( $key, self::SPLIT_KEYS ) ) {
			$subkeyList = $this->getSubitem( $code, 'list', $key );
			foreach ( $subkeyList as $subkey ) {
				if ( isset( $this->data[$code][$key][$subkey] ) ) {
					continue;
				}
				$this->loadSubitem( $code, $key, $subkey );
			}
		} else {
			$this->data[$code][$key] = $this->store->get( $code, $key );
		}

		$this->loadedItems[$code][$key] = true;
	}

	/**
	 * Load a subitem into the cache.
	 *
	 * @param string $code
	 * @param string $key
	 * @param string $subkey
	 */
	private function loadSubitem( $code, $key, $subkey ) {
		if ( !in_array( $key, self::SPLIT_KEYS ) ) {
			$this->loadItem( $code, $key );

			return;
		}

		if ( !isset( $this->initialisedLangs[$code] ) ) {
			$this->initLanguage( $code );
		}

		// Check to see if initLanguage() loaded it for us
		if ( isset( $this->loadedItems[$code][$key] ) ||
			isset( $this->loadedSubitems[$code][$key][$subkey] )
		) {
			return;
		}

		if ( isset( $this->shallowFallbacks[$code] ) ) {
			$this->loadSubitem( $this->shallowFallbacks[$code], $key, $subkey );

			return;
		}

		$value = $this->store->get( $code, "$key:$subkey" );
		if ( $value !== null && in_array( $key, self::SOURCE_PREFIX_KEYS ) ) {
			[
				$this->sourceLanguage[$code][$key][$subkey],
				$this->data[$code][$key][$subkey]
			] = explode( self::SOURCEPREFIX_SEPARATOR, $value, 2 );
		} else {
			$this->data[$code][$key][$subkey] = $value;
		}

		$this->loadedSubitems[$code][$key][$subkey] = true;
	}

	/**
	 * Returns true if the cache identified by $code is missing or expired.
	 *
	 * @param string $code
	 *
	 * @return bool
	 */
	public function isExpired( $code ) {
		if ( $this->options->get( 'forceRecache' ) && !isset( $this->recachedLangs[$code] ) ) {
			$this->logger->debug( __METHOD__ . "($code): forced reload" );

			return true;
		}

		$deps = $this->store->get( $code, 'deps' );
		$keys = $this->store->get( $code, 'list' );
		$preload = $this->store->get( $code, 'preload' );
		// Different keys may expire separately for some stores
		if ( $deps === null || $keys === null || $preload === null ) {
			$this->logger->debug( __METHOD__ . "($code): cache missing, need to make one" );

			return true;
		}

		foreach ( $deps as $dep ) {
			// Because we're unserializing stuff from cache, we
			// could receive objects of classes that don't exist
			// anymore (e.g., uninstalled extensions)
			// When this happens, always expire the cache
			if ( !$dep instanceof CacheDependency || $dep->isExpired() ) {
				$this->logger->debug( __METHOD__ . "($code): cache for $code expired due to " .
					get_class( $dep ) );

				return true;
			}
		}

		return false;
	}

	/**
	 * Initialise a language in this object. Rebuild the cache if necessary.
	 *
	 * @param string $code
	 */
	private function initLanguage( $code ) {
		if ( isset( $this->initialisedLangs[$code] ) ) {
			return;
		}

		$this->initialisedLangs[$code] = true;

		# If the code is of the wrong form for a Messages*.php file, do a shallow fallback
		if ( !$this->langNameUtils->isValidBuiltInCode( $code ) ) {
			$this->initShallowFallback( $code, 'en' );

			return;
		}

		# Re-cache the data if necessary
		if ( !$this->manualRecache && $this->isExpired( $code ) ) {
			if ( $this->langNameUtils->isSupportedLanguage( $code ) ) {
				$this->recache( $code );
			} elseif ( $code === 'en' ) {
				throw new RuntimeException( 'MessagesEn.php is missing.' );
			} else {
				$this->initShallowFallback( $code, 'en' );
			}

			return;
		}

		# Preload some stuff
		$preload = $this->getItem( $code, 'preload' );
		if ( $preload === null ) {
			if ( $this->manualRecache ) {
				// No Messages*.php file. Do shallow fallback to en.
				if ( $code === 'en' ) {
					throw new RuntimeException( 'No localisation cache found for English. ' .
						'Please run maintenance/rebuildLocalisationCache.php.' );
				}
				$this->initShallowFallback( $code, 'en' );

				return;
			} else {
				throw new RuntimeException( 'Invalid or missing localisation cache.' );
			}
		}

		foreach ( self::SOURCE_PREFIX_KEYS as $key ) {
			if ( !isset( $preload[$key] ) ) {
				continue;
			}
			foreach ( $preload[$key] as $subkey => $value ) {
				if ( $value !== null ) {
					[
						$this->sourceLanguage[$code][$key][$subkey],
						$preload[$key][$subkey]
					] = explode( self::SOURCEPREFIX_SEPARATOR, $value, 2 );
				} else {
					$preload[$key][$subkey] = null;
				}
			}
		}

		if ( isset( $this->data[$code] ) ) {
			foreach ( $preload as $key => $value ) {
				// @phan-suppress-next-line PhanTypeArraySuspiciousNullable -- see isset() above
				$this->mergeItem( $key, $this->data[$code][$key], $value );
			}
		} else {
			$this->data[$code] = $preload;
		}
		foreach ( $preload as $key => $item ) {
			if ( in_array( $key, self::SPLIT_KEYS ) ) {
				foreach ( $item as $subkey => $subitem ) {
					$this->loadedSubitems[$code][$key][$subkey] = true;
				}
			} else {
				$this->loadedItems[$code][$key] = true;
			}
		}
	}

	/**
	 * Create a fallback from one language to another, without creating a
	 * complete persistent cache.
	 *
	 * @param string $primaryCode
	 * @param string $fallbackCode
	 */
	private function initShallowFallback( $primaryCode, $fallbackCode ) {
		$this->data[$primaryCode] =& $this->data[$fallbackCode];
		$this->loadedItems[$primaryCode] =& $this->loadedItems[$fallbackCode];
		$this->loadedSubitems[$primaryCode] =& $this->loadedSubitems[$fallbackCode];
		$this->shallowFallbacks[$primaryCode] = $fallbackCode;
		$this->coreDataLoaded[$primaryCode] =& $this->coreDataLoaded[$fallbackCode];
	}

	/**
	 * Read a PHP file containing localisation data.
	 *
	 * @param string $_fileName
	 * @param string $_fileType
	 * @return array
	 */
	protected function readPHPFile( $_fileName, $_fileType ) {
		include $_fileName;

		$data = [];
		if ( $_fileType == 'core' ) {
			foreach ( self::ALL_KEYS as $key ) {
				// Not all keys are set in language files, so
				// check they exist first
				// @phan-suppress-next-line MediaWikiNoIssetIfDefined  May be set in the included file
				if ( isset( $$key ) ) {
					$data[$key] = $$key;
				}
			}
		} elseif ( $_fileType == 'extension' ) {
			foreach ( self::ALL_EXCEPT_CORE_ONLY_KEYS as $key ) {
				// @phan-suppress-next-line MediaWikiNoIssetIfDefined  May be set in the included file
				if ( isset( $$key ) ) {
					$data[$key] = $$key;
				}
			}
		} elseif ( $_fileType == 'aliases' ) {
			// @phan-suppress-next-line PhanImpossibleCondition May be set in the included file
			if ( isset( $aliases ) ) {
				$data['aliases'] = $aliases;
			}
		} else {
			throw new InvalidArgumentException( __METHOD__ . ": Invalid file type: $_fileType" );
		}

		return $data;
	}

	/**
	 * Read a JSON file containing localisation messages.
	 *
	 * @param string $fileName Name of file to read
	 * @return array Array with a 'messages' key, or empty array if the file doesn't exist
	 */
	private function readJSONFile( $fileName ) {
		if ( !is_readable( $fileName ) ) {
			return [];
		}

		$json = file_get_contents( $fileName );
		if ( $json === false ) {
			return [];
		}

		$data = FormatJson::decode( $json, true );
		if ( $data === null ) {
			throw new RuntimeException( __METHOD__ . ": Invalid JSON file: $fileName" );
		}

		// Remove keys starting with '@'; they are reserved for metadata and non-message data
		foreach ( $data as $key => $unused ) {
			if ( $key === '' || $key[0] === '@' ) {
				unset( $data[$key] );
			}
		}

		return $data;
	}

	/**
	 * Get the compiled plural rules for a given language from the XML files.
	 *
	 * @since 1.20
	 * @param string $code
	 * @return array<int,string>|null
	 */
	private function getCompiledPluralRules( $code ) {
		$rules = $this->getPluralRules( $code );
		if ( $rules === null ) {
			return null;
		}
		try {
			$compiledRules = Evaluator::compile( $rules );
		} catch ( CLDRPluralRuleError $e ) {
			$this->logger->debug( $e->getMessage() );

			return [];
		}

		return $compiledRules;
	}

	/**
	 * Get the plural rules for a given language from the XML files.
	 *
	 * Cached.
	 *
	 * @since 1.20
	 * @param string $code
	 * @return array<int,string>|null
	 */
	private function getPluralRules( $code ) {
		if ( self::$pluralRules === null ) {
			self::loadPluralFiles();
		}
		return self::$pluralRules[$code] ?? null;
	}

	/**
	 * Get the plural rule types for a given language from the XML files.
	 *
	 * Cached.
	 *
	 * @since 1.22
	 * @param string $code
	 * @return array<int,string>|null
	 */
	private function getPluralRuleTypes( $code ) {
		if ( self::$pluralRuleTypes === null ) {
			self::loadPluralFiles();
		}
		return self::$pluralRuleTypes[$code] ?? null;
	}

	/**
	 * Load the plural XML files.
	 */
	private static function loadPluralFiles() {
		foreach ( self::PLURAL_FILES as $fileName ) {
			self::loadPluralFile( $fileName );
		}
	}

	/**
	 * Load a plural XML file with the given filename, compile the relevant
	 * rules, and save the compiled rules in a process-local cache.
	 *
	 * @param string $fileName
	 */
	private static function loadPluralFile( $fileName ) {
		// Use file_get_contents instead of DOMDocument::load (T58439)
		$xml = file_get_contents( $fileName );
		if ( !$xml ) {
			throw new RuntimeException( "Unable to read plurals file $fileName" );
		}
		$doc = new DOMDocument;
		$doc->loadXML( $xml );
		$rulesets = $doc->getElementsByTagName( "pluralRules" );
		foreach ( $rulesets as $ruleset ) {
			$codes = $ruleset->getAttribute( 'locales' );
			$rules = [];
			$ruleTypes = [];
			$ruleElements = $ruleset->getElementsByTagName( "pluralRule" );
			foreach ( $ruleElements as $elt ) {
				$ruleType = $elt->getAttribute( 'count' );
				if ( $ruleType === 'other' ) {
					// Don't record "other" rules, which have an empty condition
					continue;
				}
				$rules[] = $elt->nodeValue;
				$ruleTypes[] = $ruleType;
			}
			foreach ( explode( ' ', $codes ) as $code ) {
				self::$pluralRules[$code] = $rules;
				self::$pluralRuleTypes[$code] = $ruleTypes;
			}
		}
	}

	/**
	 * Read the data from the source files for a given language, and register
	 * the relevant dependencies in the $deps array.
	 *
	 * @param string $code
	 * @param array &$deps
	 * @return array
	 */
	private function readSourceFilesAndRegisterDeps( $code, &$deps ) {
		// This reads in the PHP i18n file with non-messages l10n data
		$fileName = $this->langNameUtils->getMessagesFileName( $code );
		if ( !is_file( $fileName ) ) {
			$data = [];
		} else {
			$deps[] = new FileDependency( $fileName );
			$data = $this->readPHPFile( $fileName, 'core' );
		}

		return $data;
	}

	/**
	 * Read and compile the plural data for a given language,
	 * and register the relevant dependencies in the $deps array.
	 *
	 * @param string $code
	 * @param array &$deps
	 * @return array
	 */
	private function readPluralFilesAndRegisterDeps( $code, &$deps ) {
		$data = [
			// Load CLDR plural rules for JavaScript
			'pluralRules' => $this->getPluralRules( $code ),
			// And for PHP
			'compiledPluralRules' => $this->getCompiledPluralRules( $code ),
			// Load plural rule types
			'pluralRuleTypes' => $this->getPluralRuleTypes( $code ),
		];

		foreach ( self::PLURAL_FILES as $fileName ) {
			$deps[] = new FileDependency( $fileName );
		}

		return $data;
	}

	/**
	 * Merge two localisation values, a primary and a fallback, overwriting the
	 * primary value in place.
	 *
	 * @param string $key
	 * @param mixed &$value
	 * @param mixed $fallbackValue
	 */
	private function mergeItem( $key, &$value, $fallbackValue ) {
		if ( $value !== null ) {
			if ( $fallbackValue !== null ) {
				if ( in_array( $key, self::MERGEABLE_MAP_KEYS ) ) {
					$value += $fallbackValue;
				} elseif ( in_array( $key, self::MERGEABLE_ALIAS_LIST_KEYS ) ) {
					$value = array_merge_recursive( $value, $fallbackValue );
				} elseif ( in_array( $key, self::OPTIONAL_MERGE_KEYS ) ) {
					if ( !empty( $value['inherit'] ) ) {
						$value = array_merge( $fallbackValue, $value );
					}

					unset( $value['inherit'] );
				} elseif ( in_array( $key, self::MAGIC_WORD_KEYS ) ) {
					$this->mergeMagicWords( $value, $fallbackValue );
				}
			}
		} else {
			$value = $fallbackValue;
		}
	}

	private function mergeMagicWords( array &$value, array $fallbackValue ): void {
		foreach ( $fallbackValue as $magicName => $fallbackInfo ) {
			if ( !isset( $value[$magicName] ) ) {
				$value[$magicName] = $fallbackInfo;
			} else {
				$value[$magicName] = [
					$fallbackInfo[0],
					...array_unique( [
						// First value is 1 if the magic word is case-sensitive, 0 if not
						...array_slice( $value[$magicName], 1 ),
						...array_slice( $fallbackInfo, 1 ),
					] )
				];
			}
		}
	}

	/**
	 * Gets the combined list of messages dirs from
	 * core and extensions
	 *
	 * @since 1.25
	 * @return array
	 */
	public function getMessagesDirs() {
		global $IP;

		return [
			'core' => "$IP/languages/i18n",
			'botpasswords' => "$IP/languages/i18n/botpasswords",
			'codex' => "$IP/languages/i18n/codex",
			'datetime' => "$IP/languages/i18n/datetime",
			'exif' => "$IP/languages/i18n/exif",
			'preferences' => "$IP/languages/i18n/preferences",
			'api' => "$IP/includes/api/i18n",
			'rest' => "$IP/includes/Rest/i18n",
			'oojs-ui' => "$IP/resources/lib/ooui/i18n",
			'paramvalidator' => "$IP/includes/libs/ParamValidator/i18n",
			'installer' => "$IP/includes/installer/i18n",
		] + $this->options->get( MainConfigNames::MessagesDirs );
	}

	/**
	 * Load the core localisation data for a given language code,
	 * without extensions, using only the process cache.
	 * See {@link self::$coreDataLoaded} for what this guarantees.
	 *
	 * In addition to the core-only keys,
	 * {@link self::$data} may contain additional entries for $code,
	 * but those must not be used outside of {@link self::recache()}
	 * (and accordingly, they are not marked as loaded yet).
	 */
	private function loadCoreData( string $code ) {
		if ( !$code ) {
			throw new InvalidArgumentException( "Invalid language code requested" );
		}
		if ( $this->coreDataLoaded[$code] ?? false ) {
			return;
		}

		$coreData = array_fill_keys( self::CORE_ONLY_KEYS, null );
		$deps = [];

		# Load the primary localisation from the source file
		$data = $this->readSourceFilesAndRegisterDeps( $code, $deps );
		$this->logger->debug( __METHOD__ . ": got localisation for $code from source" );

		# Merge primary localisation
		foreach ( $data as $key => $value ) {
			$this->mergeItem( $key, $coreData[ $key ], $value );
		}

		# Fill in the fallback if it's not there already
		// @phan-suppress-next-line PhanSuspiciousValueComparison
		if ( ( $coreData['fallback'] === null || $coreData['fallback'] === false ) && $code === 'en' ) {
			$coreData['fallback'] = false;
			$coreData['originalFallbackSequence'] = $coreData['fallbackSequence'] = [];
		} else {
			if ( $coreData['fallback'] !== null ) {
				$coreData['fallbackSequence'] = array_map( 'trim', explode( ',', $coreData['fallback'] ) );
			} else {
				$coreData['fallbackSequence'] = [];
			}
			$len = count( $coreData['fallbackSequence'] );

			# Before we add the 'en' fallback for messages, keep a copy of
			# the original fallback sequence
			$coreData['originalFallbackSequence'] = $coreData['fallbackSequence'];

			# Ensure that the sequence ends at 'en' for messages
			if ( !$len || $coreData['fallbackSequence'][$len - 1] !== 'en' ) {
				$coreData['fallbackSequence'][] = 'en';
			}
		}

		foreach ( $coreData['fallbackSequence'] as $fbCode ) {
			// load core fallback data
			$fbData = $this->readSourceFilesAndRegisterDeps( $fbCode, $deps );
			foreach ( self::CORE_ONLY_KEYS as $key ) {
				// core-only keys are not mergeable, only set if not present in core data yet
				if ( isset( $fbData[$key] ) && !isset( $coreData[$key] ) ) {
					$coreData[$key] = $fbData[$key];
				}
			}
		}

		$coreData['deps'] = $deps;
		foreach ( $coreData as $key => $item ) {
			$this->data[$code][$key] ??= null;
			// @phan-suppress-next-line PhanTypeArraySuspiciousNullable -- we just set a default null
			$this->mergeItem( $key, $this->data[$code][$key], $item );
			if (
				in_array( $key, self::CORE_ONLY_KEYS, true ) ||
				// "synthetic" keys based on "fallback" (see above)
				$key === 'fallbackSequence' ||
				$key === 'originalFallbackSequence'
			) {
				// only mark core-only keys as loaded;
				// we may have loaded additional ones from the source file,
				// but they are not fully loaded yet, since recache()
				// may have to merge in additional values from fallback languages
				$this->loadedItems[$code][$key] = true;
			}
		}

		$this->coreDataLoaded[$code] = true;
	}

	/**
	 * Load localisation data for a given language for both core and extensions
	 * and save it to the persistent cache store and the process cache.
	 *
	 * @param string $code
	 */
	public function recache( $code ) {
		if ( !$code ) {
			throw new InvalidArgumentException( "Invalid language code requested" );
		}
		$this->recachedLangs[ $code ] = true;

		# Initial values
		$initialData = array_fill_keys( self::ALL_KEYS, null );
		$this->data[$code] = [];
		$this->loadedItems[$code] = [];
		$this->loadedSubitems[$code] = [];
		$this->coreDataLoaded[$code] = false;
		$this->loadCoreData( $code );
		$coreData = $this->data[$code];
		// @phan-suppress-next-line PhanTypeArraySuspiciousNullable -- guaranteed by loadCoreData()
		$deps = $coreData['deps'];
		$coreData += $this->readPluralFilesAndRegisterDeps( $code, $deps );

		$codeSequence = array_merge( [ $code ], $coreData['fallbackSequence'] );
		$messageDirs = $this->getMessagesDirs();
		$translationAliasesDirs = $this->options->get( MainConfigNames::TranslationAliasesDirs );

		# Load non-JSON localisation data for extensions
		$extensionData = array_fill_keys( $codeSequence, $initialData );
		foreach ( $this->options->get( MainConfigNames::ExtensionMessagesFiles ) as $extension => $fileName ) {
			if ( isset( $messageDirs[$extension] ) || isset( $translationAliasesDirs[$extension] ) ) {
				# This extension has JSON message data; skip the PHP shim
				continue;
			}

			$data = $this->readPHPFile( $fileName, 'extension' );
			$used = false;

			foreach ( $data as $key => $item ) {
				foreach ( $codeSequence as $csCode ) {
					if ( isset( $item[$csCode] ) ) {
						// Keep the behaviour the same as for json messages.
						// TODO: Consider deprecating using a PHP file for messages.
						if ( in_array( $key, self::SOURCE_PREFIX_KEYS ) ) {
							foreach ( $item[$csCode] as $subkey => $_ ) {
								$this->sourceLanguage[$code][$key][$subkey] ??= $csCode;
							}
						}
						$this->mergeItem( $key, $extensionData[$csCode][$key], $item[$csCode] );
						$used = true;
					}
				}
			}

			if ( $used ) {
				$deps[] = new FileDependency( $fileName );
			}
		}

		# Load the localisation data for each fallback, then merge it into the full array
		$allData = $initialData;
		foreach ( $codeSequence as $csCode ) {
			$csData = $initialData;

			# Load core messages and the extension localisations.
			foreach ( $messageDirs as $dirs ) {
				foreach ( (array)$dirs as $dir ) {
					$fileName = "$dir/$csCode.json";
					$messages = $this->readJSONFile( $fileName );

					foreach ( $messages as $subkey => $_ ) {
						$this->sourceLanguage[$code]['messages'][$subkey] ??= $csCode;
					}
					$this->mergeItem( 'messages', $csData['messages'], $messages );

					$deps[] = new FileDependency( $fileName );
				}
			}

			foreach ( $translationAliasesDirs as $dirs ) {
				foreach ( (array)$dirs as $dir ) {
					$fileName = "$dir/$csCode.json";
					$data = $this->readJSONFile( $fileName );

					foreach ( $data as $key => $item ) {
						// We allow the key in the JSON to be specified in PascalCase similar to key definitions in
						// extension.json, but eventually they are stored in camelCase
						$normalizedKey = lcfirst( $key );

						if ( $normalizedKey === '@metadata' ) {
							// Don't store @metadata information in extension data.
							continue;
						}

						if ( !in_array( $normalizedKey, self::ALL_ALIAS_KEYS ) ) {
							throw new UnexpectedValueException(
								"Invalid key: \"$key\" for " . MainConfigNames::TranslationAliasesDirs . ". " .
								'Valid keys: ' . implode( ', ', self::ALL_ALIAS_KEYS )
							);
						}

						$this->mergeItem( $normalizedKey, $extensionData[$csCode][$normalizedKey], $item );
					}

					$deps[] = new FileDependency( $fileName );
				}
			}

			# Merge non-JSON extension data
			if ( isset( $extensionData[$csCode] ) ) {
				foreach ( $extensionData[$csCode] as $key => $item ) {
					$this->mergeItem( $key, $csData[$key], $item );
				}
			}

			if ( $csCode === $code ) {
				# Merge core data into extension data
				foreach ( $coreData as $key => $item ) {
					$this->mergeItem( $key, $csData[$key], $item );
				}
			} else {
				# Load the secondary localisation from the source file to
				# avoid infinite cycles on cyclic fallbacks
				$fbData = $this->readSourceFilesAndRegisterDeps( $csCode, $deps );
				$fbData += $this->readPluralFilesAndRegisterDeps( $csCode, $deps );
				# Only merge the keys that make sense to merge
				foreach ( self::ALL_KEYS as $key ) {
					if ( !isset( $fbData[ $key ] ) ) {
						continue;
					}

					if ( !isset( $coreData[ $key ] ) || self::isMergeableKey( $key ) ) {
						$this->mergeItem( $key, $csData[ $key ], $fbData[ $key ] );
					}
				}
			}

			# Allow extensions an opportunity to adjust the data for this fallback
			$this->hookRunner->onLocalisationCacheRecacheFallback( $this, $csCode, $csData );

			# Merge the data for this fallback into the final array
			if ( $csCode === $code ) {
				$allData = $csData;
			} else {
				foreach ( self::ALL_KEYS as $key ) {
					if ( !isset( $csData[$key] ) ) {
						continue;
					}

					// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
					if ( $allData[$key] === null || self::isMergeableKey( $key ) ) {
						$this->mergeItem( $key, $allData[$key], $csData[$key] );
					}
				}
			}
		}

		if ( !isset( $allData['rtl'] ) ) {
			throw new RuntimeException( __METHOD__ . ': Localisation data failed validation check! ' .
				'Check that your languages/messages/MessagesEn.php file is intact.' );
		}

		// Add cache dependencies for any referenced configs
		// We use the keys prefixed with 'wg' for historical reasons.
		$deps['wgExtensionMessagesFiles'] =
			new MainConfigDependency( MainConfigNames::ExtensionMessagesFiles );
		$deps['wgMessagesDirs'] =
			new MainConfigDependency( MainConfigNames::MessagesDirs );
		$deps['version'] = new ConstantDependency( self::class . '::VERSION' );

		# Add dependencies to the cache entry
		$allData['deps'] = $deps;

		# Replace spaces with underscores in namespace names
		$allData['namespaceNames'] = str_replace( ' ', '_', $allData['namespaceNames'] );

		# And do the same for special page aliases. $page is an array.
		foreach ( $allData['specialPageAliases'] as &$page ) {
			$page = str_replace( ' ', '_', $page );
		}
		# Decouple the reference to prevent accidental damage
		unset( $page );

		# If there were no plural rules, return an empty array
		$allData['pluralRules'] ??= [];
		$allData['compiledPluralRules'] ??= [];
		# If there were no plural rule types, return an empty array
		$allData['pluralRuleTypes'] ??= [];

		# Set the list keys
		$allData['list'] = [];
		foreach ( self::SPLIT_KEYS as $key ) {
			$allData['list'][$key] = array_keys( $allData[$key] );
		}
		# Run hooks
		$unused = true; // Used to be $purgeBlobs, removed in 1.34
		$this->hookRunner->onLocalisationCacheRecache( $this, $code, $allData, $unused );

		# Save to the process cache and register the items loaded
		$this->data[$code] = $allData;
		$this->loadedItems[$code] = [];
		$this->loadedSubitems[$code] = [];
		foreach ( $allData as $key => $item ) {
			$this->loadedItems[$code][$key] = true;
		}

		# Prefix each item with its source language code before save
		foreach ( self::SOURCE_PREFIX_KEYS as $key ) {
			// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
			foreach ( $allData[$key] as $subKey => $value ) {
				// The source language should have been set, but to avoid Phan error and be double sure.
				$allData[$key][$subKey] = ( $this->sourceLanguage[$code][$key][$subKey] ?? $code ) .
					self::SOURCEPREFIX_SEPARATOR . $value;
			}
		}

		# Set the preload key
		$allData['preload'] = $this->buildPreload( $allData );

		# Save to the persistent cache
		$this->store->startWrite( $code );
		foreach ( $allData as $key => $value ) {
			if ( in_array( $key, self::SPLIT_KEYS ) ) {
				foreach ( $value as $subkey => $subvalue ) {
					$this->store->set( "$key:$subkey", $subvalue );
				}
			} else {
				$this->store->set( $key, $value );
			}
		}
		$this->store->finishWrite();

		# Clear out the MessageBlobStore
		# HACK: If using a null (i.e., disabled) storage backend, we
		# can't write to the MessageBlobStore either
		if ( !$this->store instanceof LCStoreNull ) {
			foreach ( $this->clearStoreCallbacks as $callback ) {
				$callback();
			}
		}
	}

	/**
	 * Build the preload item from the given pre-cache data.
	 *
	 * The preload item will be loaded automatically, improving performance
	 * for the commonly requested items it contains.
	 *
	 * @param array $data
	 * @return array
	 */
	private function buildPreload( $data ) {
		$preload = [ 'messages' => [] ];
		foreach ( self::PRELOADED_KEYS as $key ) {
			$preload[$key] = $data[$key];
		}

		foreach ( $data['preloadedMessages'] as $subkey ) {
			$subitem = $data['messages'][$subkey] ?? null;
			$preload['messages'][$subkey] = $subitem;
		}

		return $preload;
	}

	/**
	 * Unload the data for a given language from the object cache.
	 *
	 * Reduces memory usage.
	 *
	 * @param string $code
	 */
	public function unload( $code ) {
		unset( $this->data[$code] );
		unset( $this->loadedItems[$code] );
		unset( $this->loadedSubitems[$code] );
		unset( $this->initialisedLangs[$code] );
		unset( $this->shallowFallbacks[$code] );
		unset( $this->sourceLanguage[$code] );
		unset( $this->coreDataLoaded[$code] );

		foreach ( $this->shallowFallbacks as $shallowCode => $fbCode ) {
			if ( $fbCode === $code ) {
				$this->unload( $shallowCode );
			}
		}
	}

	/**
	 * Unload all data
	 */
	public function unloadAll() {
		foreach ( $this->initialisedLangs as $lang => $unused ) {
			$this->unload( $lang );
		}
	}

	/**
	 * Disable the storage backend
	 */
	public function disableBackend() {
		$this->store = new LCStoreNull;
		$this->manualRecache = false;
	}
}
