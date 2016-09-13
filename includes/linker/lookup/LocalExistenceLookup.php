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
 * @license GPL-2.0+
 */
namespace MediaWiki\Linker;

use LinkBatch;
use LinkCache;
use MediaWiki\MediaWikiServices;
use TitleFormatter;

/**
 * Lookup for local link targets, mostly a wrapper around
 * LinkBatch and LinkCache
 *
 * It also short-circuits for links that are only fragments
 *
 * @since 1.28
 */
class LocalExistenceLookup implements LinkTargetExistenceLookup {

	/**
	 * @var LinkBatch
	 */
	private $linkBatch;

	/**
	 * @var LinkCache
	 */
	private $linkCache;

	/**
	 * @var TitleFormatter
	 */
	private $titleFormatter;

	public function __construct() {
		$this->linkBatch = new LinkBatch();
		$this->linkBatch->setCaller( __CLASS__ );
		$this->linkCache = MediaWikiServices::getInstance()->getLinkCache();
		$this->titleFormatter = MediaWikiServices::getInstance()->getTitleFormatter();
	}

	public function add( LinkTarget $linkTarget ) {
		if ( $linkTarget->getDBkey() === '' ) {
			// Self-referential link, always exists
			return self::EXISTS;
		}

		$pdbk = $this->titleFormatter->getPrefixedDBkey( $linkTarget );
		if ( $this->linkCache->getGoodLinkID( $pdbk ) ) {
			return self::EXISTS;
		} elseif ( $this->linkCache->isBadLink( $pdbk ) ) {
			// We know it doesn't exist, so we skip because we still
			// want other classes to handle it
			return self::SKIP;
		} else {
			$this->linkBatch->addObj( $linkTarget );
			return self::HANDLE;
		}
	}

	public function lookup() {
		$this->linkBatch->execute();

		// Reset LinkBatch
		$this->linkBatch = new LinkBatch();
		$this->linkBatch->setCaller( __CLASS__ );
	}

	public function exists( LinkTarget $linkTarget ) {
		if ( !$linkTarget->getDBkey() ) {
			// Self-referential link, always exists
			return self::EXISTS;
		}
		return (bool)$this->linkCache->getGoodLinkID(
			$this->titleFormatter->getPrefixedDBkey( $linkTarget )
		) ? self::EXISTS : self::SKIP;
	}
}
