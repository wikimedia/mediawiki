<?php
/**
 * Interfaces for preprocessors
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
 * @ingroup Parser
 */

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;

/**
 * @ingroup Parser
 */
abstract class Preprocessor {

	public const CACHE_VERSION = 1;

	/**
	 * @var Parser
	 */
	public $parser;

	/**
	 * @var array Brace matching rules.
	 */
	protected $rules = [
		'{' => [
			'end' => '}',
			'names' => [
				2 => 'template',
				3 => 'tplarg',
			],
			'min' => 2,
			'max' => 3,
		],
		'[' => [
			'end' => ']',
			'names' => [ 2 => null ],
			'min' => 2,
			'max' => 2,
		],
		'-{' => [
			'end' => '}-',
			'names' => [ 2 => null ],
			'min' => 2,
			'max' => 2,
		],
	];

	/**
	 * Store a document tree in the cache.
	 *
	 * @param string $text
	 * @param int $flags
	 * @param string $tree
	 */
	protected function cacheSetTree( $text, $flags, $tree ) {
		$config = RequestContext::getMain()->getConfig();

		$length = strlen( $text );
		$threshold = $config->get( 'PreprocessorCacheThreshold' );
		if ( $threshold === false || $length < $threshold || $length > 1e6 ) {
			return;
		}

		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$key = $cache->makeKey(
			// @phan-suppress-next-line PhanUndeclaredConstantOfClass
			defined( 'static::CACHE_PREFIX' ) ? static::CACHE_PREFIX : static::class,
			md5( $text ),
			$flags
		);
		$value = sprintf( "%08d", static::CACHE_VERSION ) . $tree;

		$cache->set( $key, $value, 86400 );

		LoggerFactory::getInstance( 'Preprocessor' )
			->info( "Cached preprocessor output (key: $key)" );
	}

	/**
	 * Attempt to load a precomputed document tree for some given wikitext
	 * from the cache.
	 *
	 * @param string $text
	 * @param int $flags
	 * @return PPNode_Hash_Tree|bool
	 */
	protected function cacheGetTree( $text, $flags ) {
		$config = RequestContext::getMain()->getConfig();

		$length = strlen( $text );
		$threshold = $config->get( 'PreprocessorCacheThreshold' );
		if ( $threshold === false || $length < $threshold || $length > 1e6 ) {
			return false;
		}

		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();

		$key = $cache->makeKey(
			// @phan-suppress-next-line PhanUndeclaredConstantOfClass
			defined( 'static::CACHE_PREFIX' ) ? static::CACHE_PREFIX : static::class,
			md5( $text ),
			$flags
		);

		$value = $cache->get( $key );
		if ( !$value ) {
			return false;
		}

		$version = intval( substr( $value, 0, 8 ) );
		if ( $version !== static::CACHE_VERSION ) {
			return false;
		}

		LoggerFactory::getInstance( 'Preprocessor' )
			->info( "Loaded preprocessor output from cache (key: $key)" );

		return substr( $value, 8 );
	}

	/**
	 * Create a new top-level frame for expansion of a page
	 *
	 * @return PPFrame
	 */
	abstract public function newFrame();

	/**
	 * Create a new custom frame for programmatic use of parameter replacement
	 * as used in some extensions.
	 *
	 * @param array $args
	 *
	 * @return PPFrame
	 */
	abstract public function newCustomFrame( $args );

	/**
	 * Create a new custom node for programmatic use of parameter replacement
	 * as used in some extensions.
	 *
	 * @param array $values
	 */
	abstract public function newPartNodeArray( $values );

	/**
	 * Preprocess text to a PPNode
	 *
	 * @param string $text
	 * @param int $flags
	 *
	 * @return PPNode
	 */
	abstract public function preprocessToObj( $text, $flags = 0 );
}
