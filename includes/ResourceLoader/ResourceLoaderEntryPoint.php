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

namespace MediaWiki\ResourceLoader;

use MediaWiki\MediaWikiEntryPoint;
use Profiler;

/**
 * Entry point implementation for @ref ResourceLoader, which serves static CSS/JavaScript
 * via @ref MediaWiki\ResourceLoader\Module Module subclasses.
 *
 * @see load.php
 * @ingroup ResourceLoader
 * @ingroup entrypoint
 */
class ResourceLoaderEntryPoint extends MediaWikiEntryPoint {

	/**
	 * Main entry point
	 */
	public function execute() {
		$services = $this->getServiceContainer();

		// Disable ChronologyProtector so that we don't wait for unrelated MediaWiki
		// writes when getting database connections for ResourceLoader. (T192611)
		$services->getChronologyProtector()->setEnabled( false );

		$resourceLoader = $services->getResourceLoader();
		$context = new Context(
			$resourceLoader,
			$this->getRequest(),
			array_keys( $services->getSkinFactory()->getInstalledSkins() )
		);

		// T390929
		$extraHeaders = [];
		$hookRunner = new HookRunner( $services->getHookContainer() );
		$hookRunner->onResourceLoaderBeforeResponse( $context, $extraHeaders );

		// Respond to ResourceLoader request
		$resourceLoader->respond( $context, $extraHeaders );

		// Append any visible profiling data in a manner appropriate for the Content-Type
		$profiler = Profiler::instance();
		$profiler->setAllowOutput();
		$profiler->logDataPageOutputOnly();
	}

	protected function doPrepareForOutput() {
		// No-op.
		// Do not call parent::doPrepareForOutput() to avoid
		// commitMainTransaction() getting called.
	}
}
