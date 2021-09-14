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
 * @author Kunal Mehta <legoktm@debian.org>
 */
namespace MediaWiki\Linker;

use LinkCache;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\User\UserIdentity;
use NamespaceInfo;
use TitleFormatter;

/**
 * Factory to create LinkRender objects
 * @since 1.28
 */
class LinkRendererFactory {

	/**
	 * @var TitleFormatter
	 */
	private $titleFormatter;

	/**
	 * @var LinkCache
	 */
	private $linkCache;

	/**
	 * @var NamespaceInfo
	 */
	private $nsInfo;

	/**
	 * @var HookContainer
	 */
	private $hookContainer;

	/**
	 * @var SpecialPageFactory
	 */
	private $specialPageFactory;

	/**
	 * @internal For use by core ServiceWiring
	 * @param TitleFormatter $titleFormatter
	 * @param LinkCache $linkCache
	 * @param NamespaceInfo $nsInfo
	 * @param SpecialPageFactory $specialPageFactory
	 * @param HookContainer $hookContainer
	 */
	public function __construct(
		TitleFormatter $titleFormatter,
		LinkCache $linkCache,
		NamespaceInfo $nsInfo,
		SpecialPageFactory $specialPageFactory,
		HookContainer $hookContainer
	) {
		$this->titleFormatter = $titleFormatter;
		$this->linkCache = $linkCache;
		$this->nsInfo = $nsInfo;
		$this->specialPageFactory = $specialPageFactory;
		$this->hookContainer = $hookContainer;
	}

	/**
	 * @return LinkRenderer
	 */
	public function create() {
		return new LinkRenderer(
			$this->titleFormatter, $this->linkCache, $this->nsInfo, $this->specialPageFactory,
			$this->hookContainer
		);
	}

	/**
	 * @deprecated since 1.37. LinkRenderer does not depend on the user any longer,
	 * so calling ::create is sufficient.
	 * @param UserIdentity $user
	 * @return LinkRenderer
	 */
	public function createForUser( UserIdentity $user ) {
		wfDeprecated( __METHOD__, '1.37' );
		return $this->create();
	}

	/**
	 * @param array $options
	 * @return LinkRenderer
	 */
	public function createFromLegacyOptions( array $options ) {
		$linkRenderer = $this->create();

		if ( in_array( 'forcearticlepath', $options, true ) ) {
			$linkRenderer->setForceArticlePath( true );
		}

		if ( in_array( 'http', $options, true ) ) {
			$linkRenderer->setExpandURLs( PROTO_HTTP );
		} elseif ( in_array( 'https', $options, true ) ) {
			$linkRenderer->setExpandURLs( PROTO_HTTPS );
		}

		return $linkRenderer;
	}
}
