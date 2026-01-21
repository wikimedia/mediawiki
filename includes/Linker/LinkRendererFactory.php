<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author Kunal Mehta <legoktm@debian.org>
 */
namespace MediaWiki\Linker;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Page\LinkCache;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\TempUser\TempUserDetailsLookup;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserNameUtils;

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
	 * @var HookContainer
	 */
	private $hookContainer;

	/**
	 * @var SpecialPageFactory
	 */
	private $specialPageFactory;

	private TempUserConfig $tempUserConfig;
	private TempUserDetailsLookup $tempUserDetailsLookup;
	private UserIdentityLookup $userIdentityLookup;
	private UserNameUtils $userNameUtils;

	/**
	 * @internal For use by core ServiceWiring
	 */
	public function __construct(
		TitleFormatter $titleFormatter,
		LinkCache $linkCache,
		SpecialPageFactory $specialPageFactory,
		HookContainer $hookContainer,
		TempUserConfig $tempUserConfig,
		TempUserDetailsLookup $tempUserDetailsLookup,
		UserIdentityLookup $userIdentityLookup,
		UserNameUtils $userNameUtils
	) {
		$this->titleFormatter = $titleFormatter;
		$this->linkCache = $linkCache;
		$this->specialPageFactory = $specialPageFactory;
		$this->hookContainer = $hookContainer;
		$this->tempUserConfig = $tempUserConfig;
		$this->tempUserDetailsLookup = $tempUserDetailsLookup;
		$this->userIdentityLookup = $userIdentityLookup;
		$this->userNameUtils = $userNameUtils;
	}

	/**
	 * @param array $options optional flags for rendering
	 *  - 'renderForComment': set to true if the created LinkRenderer will be used for
	 *    links in an edit summary or log comments. An instance with renderForComment
	 *    enabled must not be used for other links.
	 *
	 * @return LinkRenderer
	 */
	public function create( array $options = [ 'renderForComment' => false ] ) {
		return new LinkRenderer(
			$this->titleFormatter, $this->linkCache, $this->specialPageFactory,
			$this->hookContainer, $this->tempUserConfig,
			$this->tempUserDetailsLookup, $this->userIdentityLookup,
			$this->userNameUtils,
			new ServiceOptions( LinkRenderer::CONSTRUCTOR_OPTIONS, $options )
		);
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
