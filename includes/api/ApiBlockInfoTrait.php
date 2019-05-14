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

use MediaWiki\Block\AbstractBlock;
use MediaWiki\Block\SystemBlock;

/**
 * @ingroup API
 */
trait ApiBlockInfoTrait {

	/**
	 * Get basic info about a given block
	 * @param AbstractBlock $block
	 * @return array Array containing several keys:
	 *  - blockid - ID of the block
	 *  - blockedby - username of the blocker
	 *  - blockedbyid - user ID of the blocker
	 *  - blockreason - reason provided for the block
	 *  - blockedtimestamp - timestamp for when the block was placed/modified
	 *  - blockexpiry - expiry time of the block
	 *  - systemblocktype - system block type, if any
	 */
	private function getBlockDetails( AbstractBlock $block ) {
		$vals = [];
		$vals['blockid'] = $block->getId();
		$vals['blockedby'] = $block->getByName();
		$vals['blockedbyid'] = $block->getBy();
		$vals['blockreason'] = $block->getReason();
		$vals['blockedtimestamp'] = wfTimestamp( TS_ISO_8601, $block->getTimestamp() );
		$vals['blockexpiry'] = ApiResult::formatExpiry( $block->getExpiry(), 'infinite' );
		$vals['blockpartial'] = !$block->isSitewide();
		if ( $block instanceof SystemBlock ) {
			$vals['systemblocktype'] = $block->getSystemBlockType();
		}
		return $vals;
	}

}
