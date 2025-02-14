<?php
declare( strict_types=1 );

namespace MediaWiki\Linker;

use HtmlArmor;
use MapCacheLRU;
use MediaWiki\Html\Html;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\ExternalUserNames;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserIdentity;
use MessageLocalizer;
use Wikimedia\IPUtils;

/**
 * Service class that renders HTML for user-related links.
 * @since 1.44
 * @unstable
 */
class UserLinkRenderer {
	private TempUserConfig $tempUserConfig;
	private SpecialPageFactory $specialPageFactory;
	private LinkRenderer $linkRenderer;

	/**
	 * Process cache for user links keyed by user name,
	 * to optimize rendering large pagers with potentially repeated user links.
	 *
	 * @var MapCacheLRU
	 */
	private MapCacheLRU $userLinkCache;

	public function __construct(
		TempUserConfig $tempUserConfig,
		SpecialPageFactory $specialPageFactory,
		LinkRenderer $linkRenderer
	) {
		$this->tempUserConfig = $tempUserConfig;
		$this->specialPageFactory = $specialPageFactory;
		$this->linkRenderer = $linkRenderer;

		// Set a large enough cache size to accommodate long pagers,
		// such as Special:RecentChanges with a high limit.
		$this->userLinkCache = new MapCacheLRU( 1_000 );
	}

	/**
	 * Render a user page link (or user contributions for anonymous and temporary users).
	 * Returns potentially cached link HTML.
	 *
	 * @param UserIdentity $targetUser The user to render a link for.
	 * @param MessageLocalizer $unused Unused until follow-up patchset.
	 * @param string|null $altUserName Optional text to display instead of the user name,
	 * or `null` to use the user name.
	 * @param string[] $attributes Optional extra HTML attributes for the link.
	 * @return string HTML fragment
	 */
	public function userLink(
		UserIdentity $targetUser,
		MessageLocalizer $unused,
		?string $altUserName = null,
		array $attributes = []
	): string {
		return $this->userLinkCache->getWithSetCallback(
			$this->userLinkCache->makeKey(
				$targetUser->getName(),
				$altUserName ?? '',
				implode( ' ', $attributes )
			),
			fn () => $this->renderUserLink( $targetUser, $unused, $altUserName, $attributes )
		);
	}

	/**
	 * Render a user page link (or user contributions for anonymous and temporary users),
	 * without caching.
	 *
	 * @param UserIdentity $targetUser The user to render a link for.
	 * @param MessageLocalizer $unused Unused until follow-up patchset.
	 * @param string|null $altUserName Optional text to display instead of the user name,
	 * or `null` to use the user name.
	 * @param string[] $attributes Optional extra HTML attributes for the link.
	 * @return string HTML fragment
	 */
	private function renderUserLink(
		UserIdentity $targetUser,
		MessageLocalizer $unused,
		?string $altUserName = null,
		array $attributes = []
	): string {
		$userName = $targetUser->getName();

		$classes = [ 'mw-userlink' ];

		if ( $this->tempUserConfig->isTempName( $userName ) ) {
			$classes[] = 'mw-tempuserlink';
			$pageName = $this->specialPageFactory->getLocalNameFor( 'Contributions', $userName );
			$page = new TitleValue( NS_SPECIAL, $pageName );
		} elseif ( !$targetUser->isRegistered() ) {
			$page = ExternalUserNames::getUserLinkTitle( $userName );

			if ( ExternalUserNames::isExternal( $userName ) ) {
				$classes[] = 'mw-extuserlink';
			} elseif ( $altUserName === null ) {
				$altUserName = IPUtils::prettifyIP( $userName );
			}
			$classes[] = 'mw-anonuserlink'; // Separate link class for anons (T45179)
		} else {
			$page = TitleValue::tryNew( NS_USER, strtr( $userName, ' ', '_' ) );
		}

		// Wrap the output with <bdi> tags for directionality isolation
		$linkText =
			'<bdi>' . htmlspecialchars( $altUserName ?? $userName ) . '</bdi>';

		if ( isset( $attributes['class'] ) ) {
			$classes[] = $attributes['class'];
		}

		$attributes['class'] = implode( ' ', $classes );

		if ( $page !== null ) {
			return $this->linkRenderer->makeLink( $page, new HtmlArmor( $linkText ), $attributes );
		}

		return Html::rawElement( 'span', $attributes, $linkText );
	}
}
