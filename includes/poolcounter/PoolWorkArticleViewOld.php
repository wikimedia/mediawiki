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

use MediaWiki\Json\JsonCodec;
use MediaWiki\Logger\Spi as LoggerSpi;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionRenderer;
use Wikimedia\Assert\Assert;

/**
 * PoolWorkArticleView for an old revision of a page, using a simple cache.
 *
 * @internal
 */
class PoolWorkArticleViewOld extends PoolWorkArticleView {
	/** @var int */
	private $cacheExpiry;

	/** @var BagOStuff */
	private $cache;

	/** @var string */
	private $cacheKey;

	/** @var JsonCodec */
	private $jsonCodec;

	/**
	 * @param string $cacheKey Key for the ParserOutput to use in $cache.
	 *        Also used as the PoolCounter key.
	 * @param int $cacheExpiry Expiry for ParserOutput in $cache.
	 * @param BagOStuff $cache The cache to store ParserOutput in.
	 * @param RevisionRecord $revision Revision to render
	 * @param ParserOptions $parserOptions ParserOptions to use for the parse
	 * @param RevisionRenderer $revisionRenderer
	 * @param JsonCodec $jsonCodec
	 * @param LoggerSpi $loggerSpi
	 */
	public function __construct(
		string $cacheKey,
		int $cacheExpiry,
		BagOStuff $cache,
		RevisionRecord $revision,
		ParserOptions $parserOptions,
		RevisionRenderer $revisionRenderer,
		JsonCodec $jsonCodec,
		LoggerSpi $loggerSpi
	) {
		Assert::parameter( $cacheExpiry > 0, '$cacheExpiry', 'must be greater than zero' );

		parent::__construct( $cacheKey, $revision, $parserOptions, $revisionRenderer, $loggerSpi );

		$this->cacheKey = $cacheKey;
		$this->cacheExpiry = $cacheExpiry;
		$this->cache = $cache;
		$this->jsonCodec = $jsonCodec;

		$this->cacheable = true;
	}

	/**
	 * @param ParserOutput $output
	 * @param string $cacheTime
	 */
	protected function saveInCache( ParserOutput $output, string $cacheTime ) {
		$json = $this->encodeAsJson( $output );
		if ( $json === null ) {
			return;
		}

		$this->cache->set( $this->cacheKey, $json, $this->cacheExpiry );
	}

	/**
	 * @return bool
	 */
	public function getCachedWork() {
		$json = $this->cache->get( $this->cacheKey );

		$logger = $this->getLogger();
		if ( $json === false ) {
			$logger->debug( 'output cache miss' );
			return false;
		} else {
			$logger->debug( 'output cache hit' );
		}

		$output = $this->restoreFromJson( $json );

		// Note: if $output is null, $this->parserOutput remains false, not null.
		if ( $output === null ) {
			return false;
		}

		$this->parserOutput = $output;
		return true;
	}

	/**
	 * @param string $json
	 *
	 * @return ParserOutput|null
	 */
	private function restoreFromJson( string $json ) {
		try {
			/** @var ParserOutput $obj */
			$obj = $this->jsonCodec->unserialize( $json, ParserOutput::class );
			return $obj;
		} catch ( InvalidArgumentException $e ) {
			$this->getLogger()->error( "Unable to unserialize JSON", [
				'cache_key' => $this->cacheKey,
				'message' => $e->getMessage()
			] );
			return null;
		}
	}

	/**
	 * @param ParserOutput $output
	 *
	 * @return string|null
	 */
	private function encodeAsJson( ParserOutput $output ) {
		try {
			return $this->jsonCodec->serialize( $output );
		} catch ( InvalidArgumentException $e ) {
			$this->getLogger()->error( "JSON encoding failed", [
				'cache_key' => $this->cacheKey,
				'message' => $e->getMessage(),
			] );
			return null;
		}
	}

}
