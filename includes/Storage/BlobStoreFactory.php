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

namespace MediaWiki\Storage;

use Language;
use MediaWiki\Config\ServiceOptions;
use WANObjectCache;
use Wikimedia\Rdbms\LBFactory;

/**
 * Service for instantiating BlobStores
 *
 * This can be used to create BlobStore objects for other wikis.
 *
 * @since 1.31
 */
class BlobStoreFactory {

	/**
	 * @var LBFactory
	 */
	private $lbFactory;

	/**
	 * @var WANObjectCache
	 */
	private $cache;

	/**
	 * @var ServiceOptions
	 */
	private $options;

	/**
	 * @var Language
	 */
	private $contLang;

	/**
	 * TODO Make this a const when HHVM support is dropped (T192166)
	 *
	 * @var array
	 * @since 1.34
	 */
	public static $constructorOptions = [
		'CompressRevisions',
		'DefaultExternalStore',
		'LegacyEncoding',
		'RevisionCacheExpiry',
	];

	public function __construct(
		LBFactory $lbFactory,
		WANObjectCache $cache,
		ServiceOptions $options,
		Language $contLang
	) {
		$options->assertRequiredOptions( self::$constructorOptions );

		$this->lbFactory = $lbFactory;
		$this->cache = $cache;
		$this->options = $options;
		$this->contLang = $contLang;
	}

	/**
	 * @since 1.31
	 *
	 * @param bool|string $wikiId The ID of the target wiki database. Use false for the local wiki.
	 *
	 * @return BlobStore
	 */
	public function newBlobStore( $wikiId = false ) {
		return $this->newSqlBlobStore( $wikiId );
	}

	/**
	 * @internal Please call newBlobStore and use the BlobStore interface.
	 *
	 * @param bool|string $wikiId The ID of the target wiki database. Use false for the local wiki.
	 *
	 * @return SqlBlobStore
	 */
	public function newSqlBlobStore( $wikiId = false ) {
		$lb = $this->lbFactory->getMainLB( $wikiId );
		$store = new SqlBlobStore(
			$lb,
			$this->cache,
			$wikiId
		);

		$store->setCompressBlobs( $this->options->get( 'CompressRevisions' ) );
		$store->setCacheExpiry( $this->options->get( 'RevisionCacheExpiry' ) );
		$store->setUseExternalStore( $this->options->get( 'DefaultExternalStore' ) !== false );

		if ( $this->options->get( 'LegacyEncoding' ) ) {
			$store->setLegacyEncoding( $this->options->get( 'LegacyEncoding' ), $this->contLang );
		}

		return $store;
	}

}
