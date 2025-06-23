<?php
/**
 * Helper class for the index.php entry point.
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
 */

use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\EntryPointEnvironment;
use MediaWiki\MediaWikiEntryPoint;
use MediaWiki\MediaWikiServices;

/**
 * Backwards compatibility shim for use by extensions that created a MediaWiki object just in order to call
 * doPostOutputShutdown().
 *
 * @deprecated since 1.42, use MediaWikiEntryPoint instead
 */
class MediaWiki extends MediaWikiEntryPoint {

	public function __construct(
		?IContextSource $context = null,
		?EntryPointEnvironment $environment = null
	) {
		$context ??= RequestContext::getMain();
		$environment ??= new EntryPointEnvironment();

		parent::__construct( $context, $environment, MediaWikiServices::getInstance() );
	}

	protected function execute(): never {
		throw new LogicException(
			'The backwards-compat MediaWiki class does not implement the execute() method'
		);
	}

	/**
	 * Overwritten to make public, for backwards compatibility
	 *
	 * @deprecated since 1.42, extensions should have no need to call this.
	 *             Subclasses of MediaWikiEntryPoint in core should generally
	 *             call postOutputShutdown() instead.
	 */
	public function restInPeace() {
		parent::restInPeace();
	}

	/**
	 * Overwritten to make public, for backwards compatibility.
	 *
	 * @deprecated since 1.42, extensions should have no need to call this.
	 */
	public function doPostOutputShutdown() {
		parent::doPostOutputShutdown();
	}

	/**
	 * This function commits all DB and session changes as needed *before* the
	 * client can receive a response (in case DB commit fails) and thus also before
	 * the response can trigger a subsequent related request by the client.
	 *
	 * @param IContextSource $context
	 *
	 * @since 1.27
	 * @deprecated since 1.42, extensions should have no need to call this.
	 *             Subclasses of MediaWikiEntryPoint in core should generally
	 *             call prepareForOutput() instead.
	 */
	public static function preOutputCommit( IContextSource $context ) {
		$entryPoint = new static( $context );
		$entryPoint->prepareForOutput();
	}

}
