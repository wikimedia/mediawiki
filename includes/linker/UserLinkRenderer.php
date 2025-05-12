<?php
declare( strict_types=1 );

namespace MediaWiki\Linker;

use MediaWiki\Context\IContextSource;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\Html\Html;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\ExternalUserNames;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\TempUser\TempUserDetailsLookup;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\WikiMap\WikiMap;
use MessageLocalizer;
use Wikimedia\HtmlArmor\HtmlArmor;
use Wikimedia\IPUtils;
use Wikimedia\MapCacheLRU\MapCacheLRU;

/**
 * Service class that renders HTML for user-related links.
 * @since 1.44
 * @unstable
 */
class UserLinkRenderer {
	private TempUserConfig $tempUserConfig;
	private SpecialPageFactory $specialPageFactory;
	private LinkRenderer $linkRenderer;
	private TempUserDetailsLookup $tempUserDetailsLookup;
	private UserIdentityLookup $userIdentityLookup;

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
		LinkRenderer $linkRenderer,
		TempUserDetailsLookup $tempUserDetailsLookup,
		UserIdentityLookup $userIdentityLookup
	) {
		$this->tempUserConfig = $tempUserConfig;
		$this->specialPageFactory = $specialPageFactory;
		$this->linkRenderer = $linkRenderer;
		$this->tempUserDetailsLookup = $tempUserDetailsLookup;
		$this->userIdentityLookup = $userIdentityLookup;

		// Set a large enough cache size to accommodate long pagers,
		// such as Special:RecentChanges with a high limit.
		$this->userLinkCache = new MapCacheLRU( 1_000 );
	}

	/**
	 * Render a user page link (or user contributions for anonymous and temporary users).
	 * Returns potentially cached link HTML.
	 *
	 * @param UserIdentity $targetUser The user to render a link for.
	 * @param IContextSource $context
	 * @param string|null $altUserName Optional text to display instead of the user name,
	 * or `null` to use the user name.
	 * @param string[] $attributes Optional extra HTML attributes for the link.
	 * @return string HTML fragment
	 */
	public function userLink(
		UserIdentity $targetUser,
		IContextSource $context,
		?string $altUserName = null,
		array $attributes = []
	): string {
		$outputPage = $context->getOutput();
		$outputPage->addModuleStyles( [
			'mediawiki.interface.helpers.styles',
			'mediawiki.interface.helpers.linker.styles'
		] );

		$userName = $targetUser->getName();

		if ( $this->isFromExternalWiki( $targetUser->getWikiId() ) ) {
			return $this->userLinkCache->getWithSetCallback(
				$this->userLinkCache->makeKey(
					$targetUser->getWikiId(),
					$userName,
					$altUserName ?? '',
					implode( ' ', $attributes )
				),
				fn () => $this->renderExternalUserLink(
					$targetUser,
					$context,
					$altUserName,
					$attributes
				)
			);
		}

		return $this->userLinkCache->getWithSetCallback(
			$this->userLinkCache->makeKey(
				$userName,
				$altUserName ?? '',
				implode( ' ', $attributes )
			),
			fn () => $this->renderUserLink(
				$targetUser,
				$context,
				$altUserName,
				$attributes
			)
		);
	}

	/**
	 * Render a user page link for an external user without caching.
	 *
	 * @param UserIdentity $targetUser The user to render a link for.
	 * @param MessageLocalizer $messageLocalizer
	 * @param string|null $altUserName Optional text to display instead of the user name,
	 * or `null` to use the user name.
	 * @param string[] $attributes Optional extra HTML attributes for the link.
	 * @return string HTML fragment
	 */
	private function renderExternalUserLink(
		UserIdentity $targetUser,
		MessageLocalizer $messageLocalizer,
		?string $altUserName = null,
		array $attributes = []
	): string {
		$userName = $targetUser->getName();
		$params = $this->getUserLinkParameters( $targetUser, $messageLocalizer );
		$attributes += $params[ 'extraAttr' ];
		$classes = $params[ 'classes' ];
		$postfix = $params[ 'postfix' ];

		$link = $this->linkRenderer->makeExternalLink(
			WikiMap::getForeignURL(
				$targetUser->getWikiId(),
				'User:' . strtr( $userName, ' ', '_' )
			),
			new HtmlArmor(
				Html::element( 'bdi', [], $altUserName ?? $userName ) . $postfix
			),
			Title::makeTitle( NS_USER, $userName ),
			'',
			$attributes + [ 'class' => $classes ]
		);

		return $link;
	}

	/**
	 * Render a user page link (or user contributions for anonymous and temporary users),
	 * without caching.
	 *
	 * @param UserIdentity $targetUser The user to render a link for.
	 * @param MessageLocalizer $messageLocalizer
	 * @param string|null $altUserName Optional text to display instead of the user name,
	 * or `null` to use the user name.
	 * @param string[] $attributes Optional extra HTML attributes for the link.
	 * @return string HTML fragment
	 */
	private function renderUserLink(
		UserIdentity $targetUser,
		MessageLocalizer $messageLocalizer,
		?string $altUserName = null,
		array $attributes = []
	): string {
		$userName = $targetUser->getName();

		$params = $this->getUserLinkParameters( $targetUser, $messageLocalizer );
		$attributes += $params[ 'extraAttr' ];
		$classes = $params[ 'classes' ];
		$postfix = $params[ 'postfix' ];

		if ( $this->tempUserConfig->isTempName( $userName ) ) {
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
			'<bdi>' . htmlspecialchars( $altUserName ?? $userName ) . '</bdi>'
			 . $postfix;

		if ( isset( $attributes['class'] ) ) {
			$classes[] = $attributes['class'];
		}

		$attributes['class'] = implode( ' ', $classes );

		if ( $page !== null ) {
			return $this->linkRenderer->makeLink( $page, new HtmlArmor( $linkText ), $attributes );
		}

		return Html::rawElement( 'span', $attributes, $linkText );
	}

	/**
	 * @param UserIdentity $targetUser
	 * @param MessageLocalizer $messageLocalizer
	 * @return array{classes: string[], extraAttr: array, postfix: string}
	 */
	private function getUserLinkParameters(
		UserIdentity $targetUser,
		MessageLocalizer $messageLocalizer
	) {
		$attributes = [];
		$classes = [ 'mw-userlink' ];
		$userName = $targetUser->getName();
		$isExpired = false;
		$postfix = '';

		if ( $this->tempUserConfig->isTempName( $userName ) ) {
			$classes[] = 'mw-tempuserlink';
			$attributes['data-mw-target'] = $userName;

			if ( $this->isFromExternalWiki( $targetUser->getWikiId() ) ) {
				// Check if the local wiki has an account with the same name and,
				// if it does, check if it is expired. We can do this because
				// temporary accounts expire on all wikis at the same time for a
				// wiki farm.
				$localIdentity = $this->userIdentityLookup->getUserIdentityByName(
					$userName
				);
			} else {
				// For local users, we can directly use $targetUser
				$localIdentity = $targetUser;
			}

			if ( $localIdentity instanceof UserIdentity ) {
				$isExpired = $this->tempUserDetailsLookup->isExpired(
					$localIdentity
				);
			}
		}

		// Adjust the styling of expired temporary account links (T358469).
		if ( $isExpired ) {
			$classes[] = 'mw-tempuserlink-expired';

			$description = $messageLocalizer->msg(
				'tempuser-expired-link-tooltip'
			)->text();

			$postfix = Html::element(
				'span',
				[
					'role' => 'presentation',
					'class' => 'cdx-tooltip mw-tempuserlink-expired--tooltip',
				],
				$description
			);

			$attributes['aria-description'] = $description;

			// Hide default link title when rendering expired temporary account
			// links to avoid conflicting with the tooltip.
			$attributes['title'] = null;
		}

		return [
			'classes' => $classes,
			'extraAttr' => $attributes,
			'postfix' => $postfix
		];
	}

	/**
	 * Checks whether a given wiki identifier belongs to an external wiki.
	 *
	 * @param string|false $wikiId ID to check
	 * @return bool true if the ID belongs to an external wiki, false otherwise.
	 */
	protected function isFromExternalWiki( $wikiId ): bool {
		if ( $wikiId === WikiAwareEntity::LOCAL ) {
			return false;
		}

		return !WikiMap::isCurrentWikiDbDomain( $wikiId );
	}
}
