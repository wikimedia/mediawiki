<?php
/**
 * MediaWiki\Session\Provider interface
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
 * @ingroup Session
 */

namespace MediaWiki\Session;

use Language;
use Message;

/**
 * This exists to make IDEs happy, so they don't see the
 * internal-but-required-to-be-public methods on SessionProvider.
 *
 * @ingroup Session
 * @since 1.27
 */
interface SessionProviderInterface {

	/**
	 * Return an identifier for this session type
	 *
	 * @param Language $lang Language to use.
	 * @return string
	 */
	public function describe( Language $lang );

	/**
	 * Return a Message for why sessions might not be being persisted.
	 *
	 * For example, "check whether you're blocking our cookies".
	 *
	 * @return Message|null
	 */
	public function whyNoSession();

}
