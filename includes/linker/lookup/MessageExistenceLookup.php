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

use MessageCache;

/**
 * @since 1.28
 */
class MessageExistenceLookup extends FastExistenceLookup {

	/**
	 * @param LinkTarget $linkTarget
	 * @return bool
	 */
	public function exists( LinkTarget $linkTarget ) {
		// If the page doesn't exist but is a known system message, default
		// message content will be displayed, same for language subpages-
		// Use always content language to avoid loading hundreds of languages
		// to get the link color.
		global $wgContLang;
		list( $name, ) = MessageCache::singleton()->figureMessage(
			$wgContLang->lcfirst( $linkTarget->getText() )
		);
		$message = wfMessage( $name )->inLanguage( $wgContLang )->useDatabase( false );
		return $message->exists();
	}
}
