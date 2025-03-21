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

namespace MediaWiki\Interwiki;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\MainConfigNames;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\MapCacheLRU\MapCacheLRU;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * InterwikiLookup backed by the `interwiki` database table or $wgInterwikiCache.
 *
 * By default this uses the SQL backend (`interwiki` database table) and includes
 * two levels of caching. When parsing a wiki page, many interwiki lookups may
 * be required and thus there is in-class caching for repeat lookups. To reduce
 * database pressure, there is also WANObjectCache for each prefix.
 *
 * Optionally, a pregenerated dataset can be statically set via $wgInterwikiCache,
 * in which case there are no calls to either database or WANObjectCache.
 *
 * @since 1.28
 */
class ClassicInterwikiLookup implements InterwikiLookup {
	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::InterwikiExpiry,
		MainConfigNames::InterwikiCache,
		MainConfigNames::InterwikiScopes,
		MainConfigNames::InterwikiFallbackSite,
		MainConfigNames::InterwikiMagic,
		MainConfigNames::VirtualDomainsMapping,
		'wikiId',
	];

	private ServiceOptions $options;
	private Language $contLang;
	private WANObjectCache $wanCache;
	private HookRunner $hookRunner;
	private IConnectionProvider $dbProvider;
	private LanguageNameUtils $languageNameUtils;

	/** @var MapCacheLRU<Interwiki|false> */
	private MapCacheLRU $instances;
	/**
	 * Specify number of domains to check for messages:
	 *    - 1: Just local wiki level
	 *    - 2: wiki and global levels
	 *    - 3: site level as well as wiki and global levels
	 */
	private int $interwikiScopes;
	/** Complete pregenerated data if available */
	private ?array $data;
	private string $wikiId;
	private ?string $thisSite = null;
	private array $virtualDomainsMapping;

	/**
	 * @param ServiceOptions $options
	 * @param Language $contLang Language object used to convert prefixes to lower case
	 * @param WANObjectCache $wanCache Cache for interwiki info retrieved from the database
	 * @param HookContainer $hookContainer
	 * @param IConnectionProvider $dbProvider
	 * @param LanguageNameUtils $languageNameUtils
	 */
	public function __construct(
		ServiceOptions $options,
		Language $contLang,
		WANObjectCache $wanCache,
		HookContainer $hookContainer,
		IConnectionProvider $dbProvider,
		LanguageNameUtils $languageNameUtils
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;

		$this->contLang = $contLang;
		$this->wanCache = $wanCache;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->dbProvider = $dbProvider;
		$this->languageNameUtils = $languageNameUtils;

		$this->instances = new MapCacheLRU( 1000 );
		$this->interwikiScopes = $options->get( MainConfigNames::InterwikiScopes );

		$interwikiData = $options->get( MainConfigNames::InterwikiCache );
		$this->data = is_array( $interwikiData ) ? $interwikiData : null;
		$this->wikiId = $options->get( 'wikiId' );
		$this->virtualDomainsMapping = $options->get( MainConfigNames::VirtualDomainsMapping ) ?? [];
	}

	/**
	 * @inheritDoc
	 * @param string $prefix
	 * @return bool
	 */
	public function isValidInterwiki( $prefix ) {
		$iw = $this->fetch( $prefix );
		return (bool)$iw;
	}

	/**
	 * @inheritDoc
	 * @param string|null $prefix
	 * @return Interwiki|null|false
	 */
	public function fetch( $prefix ) {
		if ( $prefix === null || $prefix === '' ) {
			return null;
		}

		$prefix = $this->contLang->lc( $prefix );

		return $this->instances->getWithSetCallback(
			$prefix,
			function () use ( $prefix ) {
				return $this->load( $prefix );
			}
		);
	}

	/**
	 * Purge the instance cache and memcached for an interwiki prefix
	 *
	 * Note that memcached is not used when $wgInterwikiCache
	 * is enabled, as the pregenerated data will be used statically
	 * without need for memcached.
	 *
	 * @param string $prefix
	 */
	public function invalidateCache( $prefix ) {
		$this->instances->clear( $prefix );

		$key = $this->wanCache->makeKey( 'interwiki', $prefix );
		$this->wanCache->delete( $key );
	}

	/**
	 * Get value from pregenerated data
	 *
	 * @param string $prefix
	 * @return string|false The pregen value or false if prefix is not known
	 */
	private function getPregenValue( string $prefix ) {
		// Lazily resolve site name
		if ( $this->interwikiScopes >= 3 && !$this->thisSite ) {
			$this->thisSite = $this->data['__sites:' . $this->wikiId]
				?? $this->options->get( MainConfigNames::InterwikiFallbackSite );
		}

		$value = $this->data[$this->wikiId . ':' . $prefix] ?? false;
		// Site level
		if ( $value === false && $this->interwikiScopes >= 3 ) {
			$value = $this->data["_{$this->thisSite}:{$prefix}"] ?? false;
		}
		// Global level
		if ( $value === false && $this->interwikiScopes >= 2 ) {
			$value = $this->data["__global:{$prefix}"] ?? false;
		}

		return $value;
	}

	/**
	 * Fetch interwiki data and create an Interwiki object.
	 *
	 * Use pregenerated data if enabled. Otherwise try memcached first
	 * and fallback to a DB query.
	 *
	 * @param string $prefix The interwiki prefix
	 * @return Interwiki|false False is prefix is invalid
	 */
	private function load( $prefix ) {
		if ( $this->data !== null ) {
			$value = $this->getPregenValue( $prefix );
			return $value ? $this->makeFromPregen( $prefix, $value ) : false;
		}

		$iwData = [];
		$abort = !$this->hookRunner->onInterwikiLoadPrefix( $prefix, $iwData );
		if ( isset( $iwData['iw_url'] ) ) {
			// Hook provided data
			return $this->makeFromRow( $iwData );
		}
		if ( $abort ) {
			// Hook indicated no other source may be considered
			return false;
		}

		$iwData = $this->wanCache->getWithSetCallback(
			$this->wanCache->makeKey( 'interwiki', $prefix ),
			$this->options->get( MainConfigNames::InterwikiExpiry ),
			function ( $oldValue, &$ttl, array &$setOpts ) use ( $prefix ) {
				// Global interlanguage link
				if ( $this->options->get( MainConfigNames::InterwikiMagic )
					&& $this->languageNameUtils->getLanguageName( $prefix )
				) {
					$row = $this->loadFromDB( $prefix, 'virtual-interwiki-interlanguage' );
				} else {
					// Local interwiki link
					$row = $this->loadFromDB( $prefix );

					// Global interwiki link (not interlanguage)
					if ( !$row && isset( $this->virtualDomainsMapping['virtual-interwiki'] ) ) {
						$row = $this->loadFromDB( $prefix, 'virtual-interwiki' );
					}
				}

				return $row ? (array)$row : '!NONEXISTENT';
			}
		);

		// Handle non-existent case
		return is_array( $iwData ) ? $this->makeFromRow( $iwData ) : false;
	}

	/*
	 * Fetch interwiki data from a DB query.
	 *
	 * @param string $prefix The interwiki prefix
	 * @param string|false $domain Domain ID, or false for the current domain
	 * @return stdClass|false interwiki data
	 */
	private function loadFromDB( $prefix, $domain = false ) {
		$dbr = $this->dbProvider->getReplicaDatabase( $domain );
		$row = $dbr->newSelectQueryBuilder()
			->select( self::selectFields() )
			->from( 'interwiki' )
			->where( [ 'iw_prefix' => $prefix ] )
			->caller( __METHOD__ )->fetchRow();

		return $row;
	}

	/**
	 * @param array $row Row from the interwiki table, possibly via memcached
	 * @return Interwiki
	 */
	private function makeFromRow( array $row ) {
		$url = $row['iw_url'];
		$local = $row['iw_local'] ?? 0;
		$trans = $row['iw_trans'] ?? 0;
		$api = $row['iw_api'] ?? '';
		$wikiId = $row['iw_wikiid'] ?? '';

		return new Interwiki( null, $url, $api, $wikiId, $local, $trans );
	}

	/**
	 * @param string $prefix
	 * @param string $value
	 * @return Interwiki
	 */
	private function makeFromPregen( string $prefix, string $value ) {
		// Split values
		[ $local, $url ] = explode( ' ', $value, 2 );
		return new Interwiki( $prefix, $url, '', '', (int)$local );
	}

	/**
	 * Fetch all interwiki prefixes from pregenerated data
	 *
	 * @param null|string $local
	 * @return array Database-like rows
	 */
	private function getAllPrefixesPregenerated( $local ) {
		// Lazily resolve site name
		if ( $this->interwikiScopes >= 3 && !$this->thisSite ) {
			$this->thisSite = $this->data['__sites:' . $this->wikiId]
				?? $this->options->get( MainConfigNames::InterwikiFallbackSite );
		}

		// List of interwiki sources
		$sources = [];
		// Global level
		if ( $this->interwikiScopes >= 2 ) {
			$sources[] = '__global';
		}
		// Site level
		if ( $this->interwikiScopes >= 3 ) {
			$sources[] = '_' . $this->thisSite;
		}
		$sources[] = $this->wikiId;

		$data = [];
		foreach ( $sources as $source ) {
			$list = $this->data['__list:' . $source] ?? '';
			foreach ( explode( ' ', $list ) as $iw_prefix ) {
				$row = $this->data["{$source}:{$iw_prefix}"] ?? null;
				if ( !$row ) {
					continue;
				}

				[ $iw_local, $iw_url ] = explode( ' ', $row );

				if ( $local !== null && $local != $iw_local ) {
					continue;
				}

				$data[$iw_prefix] = [
					'iw_prefix' => $iw_prefix,
					'iw_url' => $iw_url,
					'iw_local' => $iw_local,
				];
			}
		}

		return array_values( $data );
	}

	/**
	 * Build an array in the format accepted by $wgInterwikiCache.
	 *
	 * Given the array returned by getAllPrefixes(), build a PHP array which
	 * can be given to self::__construct() as $interwikiData, i.e. as the
	 * value of $wgInterwikiCache.  This is used to construct mock
	 * interwiki lookup services for testing (in particular, parsertests).
	 *
	 * @param array $allPrefixes An array of interwiki information such as
	 *   would be returned by ::getAllPrefixes()
	 * @param int $scope The scope at which to insert interwiki prefixes.
	 *   See the $interwikiScopes parameter to ::__construct().
	 * @param ?string $thisSite The value of $thisSite, if $scope is 3.
	 * @return array
	 */
	public static function buildCdbHash(
		array $allPrefixes, int $scope = 1, ?string $thisSite = null
	): array {
		$result = [];
		$wikiId = WikiMap::getCurrentWikiId();
		$keyPrefix = ( $scope >= 2 ) ? '__global' : $wikiId;
		if ( $scope >= 3 && $thisSite ) {
			$result[ "__sites:$wikiId" ] = $thisSite;
			$keyPrefix = "_$thisSite";
		}
		$list = [];
		foreach ( $allPrefixes as $iwInfo ) {
			$prefix = $iwInfo['iw_prefix'];
			$result["$keyPrefix:$prefix"] = implode( ' ', [
				$iwInfo['iw_local'] ?? 0, $iwInfo['iw_url']
			] );
			$list[] = $prefix;
		}
		$result["__list:$keyPrefix"]  = implode( ' ', $list );
		$result["__list:__sites"] = $wikiId;
		return $result;
	}

	/**
	 * Fetch all interwiki prefixes from DB
	 *
	 * @param bool|null $local
	 * @return array[] Database rows
	 */
	private function getAllPrefixesDB( $local ) {
		$where = [];
		if ( $local !== null ) {
			$where['iw_local'] = (int)$local;
		}

		$dbr = $this->dbProvider->getReplicaDatabase();
		$res = $dbr->newSelectQueryBuilder()
			->select( self::selectFields() )
			->from( 'interwiki' )
			->where( $where )
			->orderBy( 'iw_prefix' )
			->caller( __METHOD__ )->fetchResultSet();

		$retval = [];
		foreach ( $res as $row ) {
			$retval[] = (array)$row;
		}
		return $retval;
	}

	/**
	 * Fetch all interwiki data
	 *
	 * @param string|null $local If set, limit returned data to local or non-local interwikis
	 * @return array[] Database-like interwiki rows
	 */
	public function getAllPrefixes( $local = null ) {
		if ( $this->data !== null ) {
			return $this->getAllPrefixesPregenerated( $local );
		} else {
			return $this->getAllPrefixesDB( $local );
		}
	}

	/**
	 * List of interwiki table fields to select.
	 *
	 * @return string[]
	 */
	private static function selectFields() {
		return [
			'iw_prefix',
			'iw_url',
			'iw_api',
			'iw_wikiid',
			'iw_local',
			'iw_trans'
		];
	}

}
