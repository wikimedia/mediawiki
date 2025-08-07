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

namespace MediaWiki\Config;

/**
 * Configuration holder, particularly for multi-wiki sites.
 *
 * A basic synopsis:
 *
 * Consider a wikifarm having three sites: two production sites, one in English
 * and one in German, and one testing site. You can assign them easy-to-remember
 * identifiers - ISO 639 codes 'en' and 'de' for language wikis, and 'beta' for
 * the testing wiki.
 *
 * You would thus initialize the site configuration by specifying the wiki
 * identifiers:
 *
 * @code
 * $conf = new SiteConfiguration;
 * $conf->wikis = [ 'de', 'en', 'beta' ];
 * @endcode
 *
 * When configuring the MediaWiki global settings (the $wg variables),
 * the identifiers will be available to specify settings on a per wiki basis.
 *
 * @code
 * $conf->settings = [
 *    'wgSomeSetting' => [
 *
 *        # production:
 *        'de'     => false,
 *        'en'     => false,
 *
 *        # test:
 *        'beta    => true,
 *    ],
 * ];
 * @endcode
 *
 * With three wikis, that is easy to manage. But what about a farm with
 * hundreds of wikis? Site configuration provides a special keyword named
 * 'default' which is the value used when a wiki is not found. Hence
 * the above code could be written:
 *
 * @code
 * $conf->settings = [
 *    'wgSomeSetting' => [
 *
 *        'default' => false,
 *
 *        # Enable feature on test
 *        'beta'    => true,
 *    ],
 * ];
 * @endcode
 *
 *
 * Since settings can contain arrays, site configuration provides a way
 * to merge an array with the default. This is very useful to avoid
 * repeating settings again and again while still maintaining specific changes
 * on a per wiki basis.
 *
 * @code
 * $conf->settings = [
 *    'wgMergeSetting' = [
 *        # Value that will be shared among all wikis:
 *        'default' => [ NS_USER => true ],
 *
 *        # Leading '+' means merging the array of value with the defaults
 *        '+beta' => [ NS_HELP => true ],
 *    ],
 * ];
 *
 * # Get configuration for the German site:
 * $conf->get( 'wgMergeSetting', 'de' );
 * // --> [ NS_USER => true ];
 *
 * # Get configuration for the testing site:
 * $conf->get( 'wgMergeSetting', 'beta' );
 * // --> [ NS_USER => true, NS_HELP => true ];
 * @endcode
 *
 * Finally, to load all configuration settings, extract them in global context:
 *
 * @code
 * # Name / identifier of the wiki as set in $conf->wikis
 * $wikiID = 'beta';
 * $globals = $conf->getAll( $wikiID );
 * extract( $globals );
 * @endcode
 *
 * Simple suffix system where "pt_brwiki" becomes lang="pt-br", site="wiki":
 *
 * @code
 * $conf->suffixes[] = 'wiki';
 * @endcode
 *
 * Suffix is resolved as an alias, so "dewiki" becomes lang="de", site="wikipedia":
 * @code
 * $conf->suffixes['wikipedia'] = 'wiki';
 * @endcode
 *
 * @note For WikiMap to function, the configuration must define string values for
 *  $wgServer (or $wgCanonicalServer) and $wgArticlePath, even if these are the
 *  same for all wikis or can be correctly determined by the logic in
 *  Setup.php.
 */
class SiteConfiguration {

	/**
	 * Array of suffixes, for self::siteFromDB()
	 * @var string[]
	 */
	public $suffixes = [];

	/**
	 * Array of wikis, should be the same as $wgLocalDatabases
	 * @var string[]
	 */
	public $wikis = [];

	/**
	 * The whole array of settings.
	 *
	 * If the key "@replaceableSettings" exists, it contains a list of setting
	 * names that are subject to string replacement of $params.
	 * @var array
	 */
	public $settings = [];

	/**
	 * Optional callback to load full configuration data.
	 * @var string|array
	 */
	public $fullLoadCallback = null;

	/** @var bool Whether or not all data has been loaded */
	public $fullLoadDone = false;

	/**
	 * A callback function that returns an array with the following keys (all
	 * optional):
	 * - suffix: site's suffix
	 * - lang: site's lang
	 * - tags: array of wiki tags
	 * - params: array of parameters to be replaced
	 * The function will receive the SiteConfiguration instance in the first
	 * argument and the wiki in the second one.
	 * if suffix and lang are passed they will be used for the return value of
	 * self::siteFromDB() and self::$suffixes will be ignored
	 *
	 * @var callable|null
	 */
	public $siteParamsCallback = null;

	/**
	 * Retrieves a configuration setting for a given wiki.
	 * @param string $settingName ID of the setting name to retrieve
	 * @param string $wiki Wiki ID of the wiki in question.
	 * @param string|null $site The site from ::siteFromDB(), or db suffix.
	 * @param array $params List of parameters. $.'key' is replaced by $value in all returned data.
	 * @param array $wikiTags The tags assigned to the wiki.
	 * @return mixed The value of the setting requested.
	 */
	public function get(
		$settingName,
		$wiki,
		$site = null,
		$params = [],
		$wikiTags = []
	) {
		if ( !is_string( $settingName ) ) {
			wfDeprecated( __METHOD__ . ' with non-string $settingName', '1.44' );
			$settingName = (string)$settingName;
		}
		if ( $wiki === null ) {
			wfDeprecated( __METHOD__ . ' with null $wiki', '1.44' );
		}
		if ( !is_string( $wiki ) ) {
			wfDeprecated( __METHOD__ . ' with non-string $wiki', '1.44' );
		}
		$wiki = (string)$wiki;
		if ( $site !== null && !is_string( $site ) ) {
			wfDeprecated( __METHOD__ . ' with non-string $site', '1.44' );
			$site = (string)$site;
		}
		if ( !is_array( $params ) ) {
			wfDeprecated( __METHOD__ . ' with non-array $params', '1.44' );
			$params = (array)$params;
		}
		if ( !is_array( $wikiTags ) ) {
			wfDeprecated( __METHOD__ . ' with non-array $wikiTags', '1.44' );
			$wikiTags = (array)$wikiTags;
		}
		$params = $this->mergeParams( $wiki, $site, $params, $wikiTags );
		$overrides = $this->settings[$settingName] ?? null;
		$value = $overrides ? $this->processSetting( $overrides, $wiki, $params['tags'] ) : null;
		if ( !array_key_exists( '@replaceableSettings', $this->settings )
			|| in_array( $settingName, $this->settings['@replaceableSettings'] )
		) {
			$this->doReplacements( $value, $params['replacements'] );
		}
		return $value;
	}

	/**
	 * Retrieve the configuration setting for a given wiki, based on an overrides array.
	 *
	 * General order of precedence:
	 *
	 * 1. Wiki ID, an override specific to the given wiki.
	 * 2. Tag, an override specific to a group of wikis (e.g. wiki family, or db
	 *    shard). It is unsupported for the same setting to be set for multiple
	 *    tags of which the wiki groups overlap. In that case, whichever is
	 *    iterated and matched first wins, where the tag iteration order
	 *    is NOT guaranteed.
	 * 3. Default, the default value for all wikis in this wiki farm.
	 *
	 * If the "+" operator is used, with any of these, then the merges will follow the
	 * following order (earlier entries have precedence on clashing sub keys):
	 *
	 * 1. "+wiki"
	 * 2. "tag"
	 *    Only one may match here. And upon match, the merge cascade stops.
	 * 3. "+tag"
	 *    These are only considered if there was no "tag" match.
	 *    Multiple matches are allowed here, although the array values from
	 *    multiple tags that contain the same wiki must not overlap, as it is
	 *    undocumented how key conflicts among them would be handled.
	 * 4. "default"
	 *
	 * @param array $thisSetting An array of overrides for a given setting.
	 * @param string $wiki Wiki ID of the wiki in question.
	 * @param string[] $tags Array of tags.
	 * @return mixed The value of the setting requested.
	 */
	private function processSetting( $thisSetting, $wiki, $tags ) {
		// Optimization: Avoid native type hint on private method called by hot getAll()
		// <https://gerrit.wikimedia.org/r/c/mediawiki/core/+/820244>

		$retval = null;

		if ( array_key_exists( $wiki, $thisSetting ) ) {
			// Found override by Wiki ID.
			$retval = $thisSetting[$wiki];
		} else {
			if ( array_key_exists( "+$wiki", $thisSetting ) ) {
				// Found mergable override by Wiki ID.
				// We continue to look for more merge candidates.
				$retval = $thisSetting["+$wiki"];
			}

			foreach ( $tags as $tag ) {
				if ( array_key_exists( $tag, $thisSetting ) ) {
					if ( is_array( $retval ) && is_array( $thisSetting[$tag] ) ) {
						// Found a mergable override by Tag, without "+" operator.
						// Merge it with any "+wiki" match from before, and stop the cascade.
						$retval = self::arrayMerge( $retval, $thisSetting[$tag] );
					} else {
						// Found a non-mergable override by Tag.
						// This could in theory replace a "+wiki" match, but it should never happen
						// that a setting uses both mergable array values and non-array values.
						$retval = $thisSetting[$tag];
					}
					return $retval;
				} elseif ( array_key_exists( "+$tag", $thisSetting ) ) {
					// Found a mergable override by Tag with "+" operator.
					// Merge it with any "+wiki" or "+tag" matches from before,
					// and keep looking for more merge candidates.
					$retval = self::arrayMerge( $retval ?? [], $thisSetting["+$tag"] );
				}
			}

			if ( array_key_exists( 'default', $thisSetting ) ) {
				if ( is_array( $retval ) && is_array( $thisSetting['default'] ) ) {
					// Found a mergable default
					// Merge it with any "+wiki" or "+tag" matches from before.
					$retval = self::arrayMerge( $retval, $thisSetting['default'] );
				} else {
					// Found a default
					// If any array-based values were built up via "+wiki" or "+tag" matches,
					// these are thrown away here. We don't support merging array values into
					// non-array values, and the fallback here is to use the default.
					$retval = $thisSetting['default'];
				}
			}
		}
		return $retval;
	}

	/**
	 * Do string replacements
	 *
	 * @param string &$value
	 * @param array $replacements
	 */
	private function doReplacements( &$value, $replacements ) {
		// Optimization: Avoid native type hint on private method called by hot getAll()
		// <https://gerrit.wikimedia.org/r/c/mediawiki/core/+/820244>

		if ( is_string( $value ) ) {
			$value = strtr( $value, $replacements );
		} elseif ( is_array( $value ) ) {
			foreach ( $value as &$val ) {
				if ( is_string( $val ) ) {
					$val = strtr( $val, $replacements );
				}
			}
		}
	}

	/**
	 * Gets all settings for a wiki
	 * @param string $wiki Wiki ID of the wiki in question.
	 * @param string|null $site The site from ::siteFromDB(), or db suffix.
	 * @param array $params List of parameters. $.'key' is replaced by $value in all returned data.
	 * @param array $wikiTags The tags assigned to the wiki.
	 * @return array Array of settings requested.
	 */
	public function getAll( $wiki, $site = null, $params = [], $wikiTags = [] ) {
		if ( $wiki === null ) {
			wfDeprecated( __METHOD__ . ' with null $wiki', '1.44' );
		}
		if ( !is_string( $wiki ) ) {
			wfDeprecated( __METHOD__ . ' with non-string $wiki', '1.44' );
		}
		$wiki = (string)$wiki;
		if ( $site !== null && !is_string( $site ) ) {
			wfDeprecated( __METHOD__ . ' with non-string $site', '1.44' );
			$site = (string)$site;
		}
		if ( !is_array( $params ) ) {
			wfDeprecated( __METHOD__ . ' with non-array $params', '1.44' );
			$params = (array)$params;
		}
		if ( !is_array( $wikiTags ) ) {
			wfDeprecated( __METHOD__ . ' with non-array $wikiTags', '1.44' );
			$wikiTags = (array)$wikiTags;
		}
		$params = $this->mergeParams( $wiki, $site, $params, $wikiTags );
		$tags = $params['tags'];
		$localSettings = [];
		foreach ( $this->settings as $varname => $overrides ) {
			$value = $this->processSetting( $overrides, $wiki, $tags );
			if ( $varname[0] === '+' ) {
				$varname = substr( $varname, 1 );
				if ( is_array( $value ) && is_array( $GLOBALS[$varname] ) ) {
					$value = self::arrayMerge( $value, $GLOBALS[$varname] );
				}
			}
			if ( $value !== null ) {
				$localSettings[$varname] = $value;
			}
		}

		$replacements = $params['replacements'];
		if ( array_key_exists( '@replaceableSettings', $this->settings ) ) {
			foreach ( $this->settings['@replaceableSettings'] as $varname ) {
				if ( array_key_exists( $varname, $localSettings ) ) {
					$this->doReplacements( $localSettings[$varname], $replacements );
				}
			}
		} else {
			foreach ( $localSettings as &$value ) {
				$this->doReplacements( $value, $replacements );
			}
		}
		return $localSettings;
	}

	/**
	 * Retrieves a configuration setting for a given wiki, forced to a boolean.
	 *
	 * @param string $setting ID of the setting name to retrieve
	 * @param string $wiki Wiki ID of the wiki in question.
	 * @param string|null $site The site from ::siteFromDB(), or db suffix.
	 * @param array $wikiTags The tags assigned to the wiki.
	 * @return bool The value of the setting requested.
	 */
	public function getBool( $setting, $wiki, $site = null, $wikiTags = [] ) {
		if ( !is_string( $setting ) ) {
			wfDeprecated( __METHOD__ . ' with non-string $setting', '1.44' );
			$setting = (string)$setting;
		}
		if ( $wiki === null ) {
			wfDeprecated( __METHOD__ . ' with null $wiki', '1.44' );
		}
		if ( !is_string( $wiki ) ) {
			wfDeprecated( __METHOD__ . ' with non-string $wiki', '1.44' );
		}
		$wiki = (string)$wiki;
		if ( $site !== null && !is_string( $site ) ) {
			wfDeprecated( __METHOD__ . ' with non-string $site', '1.44' );
			$site = (string)$site;
		}
		if ( !is_array( $wikiTags ) ) {
			wfDeprecated( __METHOD__ . ' with non-array $wikiTags', '1.44' );
			$wikiTags = (array)$wikiTags;
		}
		return (bool)$this->get( $setting, $wiki, $site, [], $wikiTags );
	}

	/**
	 * Retrieves an array of local databases
	 *
	 * @return array
	 */
	public function getLocalDatabases() {
		return $this->wikis;
	}

	/**
	 * @param string $setting
	 * @param string $wiki
	 * @param array $params
	 */
	private function extractGlobalSetting( $setting, $wiki, $params ) {
		$overrides = $this->settings[$setting] ?? null;
		$value = $overrides ? $this->processSetting( $overrides, $wiki, $params['tags'] ) : null;
		if ( !array_key_exists( '@replaceableSettings', $this->settings )
			|| in_array( $setting, $this->settings['@replaceableSettings'] )
		) {
			$this->doReplacements( $value, $params['replacements'] );
		}
		if ( $value !== null ) {
			if ( substr( $setting, 0, 1 ) == '+' && is_array( $value ) ) {
				$setting = substr( $setting, 1 );
				if ( is_array( $GLOBALS[$setting] ) ) {
					$GLOBALS[$setting] = self::arrayMerge( $GLOBALS[$setting], $value );
				} else {
					$GLOBALS[$setting] = $value;
				}
			} else {
				$GLOBALS[$setting] = $value;
			}
		}
	}

	/**
	 * Retrieves the values of all settings, and places them in their corresponding global variables.
	 * @param string $wiki Wiki ID of the wiki in question.
	 * @param string|null $site The site from ::siteFromDB(), or db suffix.
	 * @param array $params List of parameters. $.'key' is replaced by $value in all returned data.
	 * @param array $wikiTags The tags assigned to the wiki.
	 */
	public function extractAllGlobals(
		$wiki,
		$site = null,
		$params = [],
		$wikiTags = []
	) {
		if ( $wiki === null ) {
			wfDeprecated( __METHOD__ . ' with null $wiki', '1.44' );
		}
		if ( !is_string( $wiki ) ) {
			wfDeprecated( __METHOD__ . ' with non-string $wiki', '1.44' );
		}
		$wiki = (string)$wiki;
		if ( $site !== null && !is_string( $site ) ) {
			wfDeprecated( __METHOD__ . ' with non-string $site', '1.44' );
			$site = (string)$site;
		}
		if ( !is_array( $params ) ) {
			wfDeprecated( __METHOD__ . ' with non-array $params', '1.44' );
			$params = (array)$params;
		}
		if ( !is_array( $wikiTags ) ) {
			wfDeprecated( __METHOD__ . ' with non-array $wikiTags', '1.44' );
			$wikiTags = (array)$wikiTags;
		}
		$params = $this->mergeParams( $wiki, $site, $params, $wikiTags );
		foreach ( $this->settings as $setting => $overrides ) {
			$this->extractGlobalSetting( $setting, $wiki, $params );
		}
	}

	/**
	 * Return specific settings for $wiki
	 * See the documentation of self::$siteParamsCallback for more in-depth
	 * documentation about this function
	 *
	 * @param string $wiki
	 * @return array
	 */
	protected function getWikiParams( $wiki ) {
		static $default = [
			'suffix' => null,
			'lang' => null,
			'tags' => [],
			'params' => [],
		];

		if ( !is_callable( $this->siteParamsCallback ) ) {
			return $default;
		}

		$ret = ( $this->siteParamsCallback )( $this, $wiki );
		# Validate the returned value
		if ( !is_array( $ret ) ) {
			return $default;
		}

		foreach ( $default as $name => $def ) {
			if ( !isset( $ret[$name] ) || ( is_array( $def ) && !is_array( $ret[$name] ) ) ) {
				$ret[$name] = $def;
			}
		}

		return $ret;
	}

	/**
	 * Merge params between the ones passed to the function and the ones given
	 * by self::$siteParamsCallback for backward compatibility
	 * Values returned by self::getWikiParams() have the priority.
	 *
	 * @param string $wiki Wiki ID of the wiki in question.
	 * @param string|null $site The site from ::siteFromDB(), or db suffix.
	 * @param array $params List of parameters. $.'key' is replaced by $value in
	 *   all returned data.
	 * @param array $wikiTags The tags assigned to the wiki.
	 * @return array
	 */
	protected function mergeParams( $wiki, $site, array $params, array $wikiTags ) {
		$ret = $this->getWikiParams( $wiki );

		$ret['suffix'] ??= $site;

		// Make tags based on the db suffix (e.g. wiki family) automatically
		// available for use in wgConf. The user does not have to maintain
		// wiki tag lookups (e.g. dblists at WMF) for the wiki family.
		$wikiTags[] = $ret['suffix'];

		$ret['tags'] = array_unique( array_merge( $ret['tags'], $wikiTags ) );

		$ret['params'] += $params;

		// Make the $lang and $site parameters automatically available if they
		// were provided by `siteParamsCallback` via getWikiParams()
		if ( !isset( $ret['params']['lang'] ) && $ret['lang'] !== null ) {
			$ret['params']['lang'] = $ret['lang'];
		}
		if ( !isset( $ret['params']['site'] ) && $ret['suffix'] !== null ) {
			$ret['params']['site'] = $ret['suffix'];
		}

		// Optimization: For hot getAll() code path, precompute replacements to re-use
		// over hundreds of processSetting() calls.
		$ret['replacements'] = [];
		foreach ( $ret['params'] as $key => $value ) {
			$ret['replacements']['$' . $key] = $value;
		}

		return $ret;
	}

	/**
	 * Work out the site and language name from a database name
	 *
	 * @param string $wiki Wiki ID
	 * @return array [ string|null $site, string|null $languageCode ]
	 */
	public function siteFromDB( $wiki ) {
		if ( $wiki === null ) {
			wfDeprecated( __METHOD__ . ' with null $wiki', '1.44' );
		}
		if ( !is_string( $wiki ) ) {
			wfDeprecated( __METHOD__ . ' with non-string $wiki', '1.44' );
		}
		$wiki = (string)$wiki;
		// Allow override
		$def = $this->getWikiParams( $wiki );
		if ( $def['suffix'] !== null && $def['lang'] !== null ) {
			return [ $def['suffix'], $def['lang'] ];
		}

		$languageCode = str_replace( '_', '-', $wiki );
		foreach ( $this->suffixes as $altSite => $suffix ) {
			if ( $suffix === '' ) {
				return [ '', $languageCode ];
			} elseif ( str_ends_with( $wiki, $suffix ) ) {
				$site = is_string( $altSite ) ? $altSite : $suffix;
				$languageCode = substr( $languageCode, 0, -strlen( $suffix ) );
				return [ $site, $languageCode ];
			}
		}

		return [ null, null ];
	}

	/**
	 * Merge multiple arrays together.
	 * On encountering duplicate keys, merge the two, but ONLY if they're arrays.
	 * PHP's array_merge_recursive() merges ANY duplicate values into arrays,
	 * which is not fun
	 *
	 * @param array $array1
	 * @param array $array2
	 * @return array
	 */
	private static function arrayMerge( array $array1, array $array2 ) {
		$out = $array1;
		foreach ( $array2 as $key => $value ) {
			if ( isset( $out[$key] ) ) {
				if ( is_array( $out[$key] ) && is_array( $value ) ) {
					// Merge the new array into the existing one
					$out[$key] = self::arrayMerge( $out[$key], $value );
				} elseif ( is_numeric( $key ) ) {
					// A numerical key is taken, append the value at the end instead.
					// It is important that we generally preserve numerical keys and only
					// fallback to appending values if there are conflicts. This is needed
					// by configuration variables that hold associative arrays with
					// meaningful numerical keys, such as $wgNamespacesWithSubpages,
					// $wgNamespaceProtection, $wgNamespacesToBeSearchedDefault, etc.
					$out[] = $value;
				} elseif ( $out[$key] === false ) {
					// A non-numerical key is taken and holds a false value,
					// allow it to be overridden always. This exists mainly for the purpose
					// merging permissions arrays, such as $wgGroupPermissions.
					$out[$key] = $value;
				}
				// Else: The key is already taken and we keep the current value

			} else {
				// Add a new key.
				$out[$key] = $value;
			}
		}

		return $out;
	}

	public function loadFullData() {
		if ( $this->fullLoadCallback && !$this->fullLoadDone ) {
			( $this->fullLoadCallback )( $this );
			$this->fullLoadDone = true;
		}
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SiteConfiguration::class, 'SiteConfiguration' );
