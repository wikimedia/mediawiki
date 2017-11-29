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
use MediaWiki\MediaWikiServices;
use Wikimedia\Assert\Assert;

/**
 * @deprecated since 1.29
 * MimeAnalyzer should be used instead of MimeMagic
 */
class MimeMagic extends MimeAnalyzer {
	/**
	 * Get an instance of this class
	 * @return MimeMagic
	 * @deprecated since 1.28 get a MimeAnalyzer instance from MediaWikiServices
	 */
	public static function singleton() {
		wfDeprecated( __METHOD__, '1.28' );
		// XXX: We know that the MimeAnalyzer is currently an instance of MimeMagic
		$instance = MediaWikiServices::getInstance()->getMimeAnalyzer();
		Assert::postcondition(
			$instance instanceof MimeMagic,
			__METHOD__ . ' should return an instance of ' . self::class
		);
		return $instance;
	}
}
