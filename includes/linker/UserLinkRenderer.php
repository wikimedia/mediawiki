<?php
declare( strict_types=1 );

namespace MediaWiki\Linker;

use MediaWiki\Context\IContextSource;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\ExternalUserNames;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\TempUser\TempUserDetailsLookup;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserNameUtils;
use MediaWiki\WikiMap\WikiMap;
use MessageLocalizer;
use Wikimedia\HtmlArmor\HtmlArmor;
use Wikimedia\IPUtils;
use Wikimedia\MapCacheLRU\MapCacheLRU;

/**
 * Service class that renders HTML for user-related links.
 * @since 1.44
 * @internal Call via LinkRenderer::userLink(), not directly.
 */
class UserLinkRenderer {

	private HookRunner $hookRunner;
	private TempUserConfig $tempUserConfig;
	private SpecialPageFactory $specialPageFactory;
	private LinkRenderer $linkRenderer;
	private TempUserDetailsLookup $tempUserDetailsLookup;
	private UserIdentityLookup $userIdentityLookup;
	private UserNameUtils $userNameUtils;

	/**
	 * Process cache for user links keyed by user name,
	 * to optimize rendering large pagers with potentially repeated user links.
	 *
	 * @var MapCacheLRU
	 */
	private MapCacheLRU $userLinkCache;

	public function __construct(
		HookContainer $hookContainer,
		TempUserConfig $tempUserConfig,
		SpecialPageFactory $specialPageFactory,
		LinkRenderer $linkRenderer,
		TempUserDetailsLookup $tempUserDetailsLookup,
		UserIdentityLookup $userIdentityLookup,
		UserNameUtils $userNameUtils
	) {
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->tempUserConfig = $tempUserConfig;
		$this->specialPageFactory = $specialPageFactory;
		$this->linkRenderer = $linkRenderer;
		$this->tempUserDetailsLookup = $tempUserDetailsLookup;
		$this->userIdentityLookup = $userIdentityLookup;
		$this->userNameUtils = $userNameUtils;

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
	 * @param array<string,string> $attributes Optional extra HTML attributes for the link.
	 * @return string HTML fragment
	 * @deprecated since 1.45, use LinkRenderer::makeUserLink() instead.
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
			$html = $this->userLinkCache->getWithSetCallback(
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
		} else {
			$html = $this->userLinkCache->getWithSetCallback(
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
		$prefix = '';
		$postfix = '';
		$this->hookRunner->onUserLinkRendererUserLinkPostRender( $targetUser, $context, $html, $prefix, $postfix );
		return $prefix . $html . $postfix;
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
	 * In addition to the classes applied by ::getLinkClassesFromUserName(),
	 * this method adds 'mw-tempuserlink-expired', 'mw-extuserlink', and
	 * 'mw-anonuserlink'.
	 *
	 * @param UserIdentity $targetUser The user to render a link for.
	 * @param MessageLocalizer $messageLocalizer
	 * @param string|null $altUserName Optional text to display instead of the user name,
	 * or `null` to use the user name.
	 * @param array<string,string> $attributes Optional extra HTML attributes for the link.
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
			} else {
				$altUserName ??= IPUtils::prettifyIP( $userName );
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

		$attributes['class'] = $classes;

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
		$userName = $targetUser->getName();
		$isExpired = false;
		$postfix = '';

		$classes = $this->getLinkClassesFromUserName( $userName );
		if ( $this->tempUserConfig->isTempName( $userName ) ) {
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

	/**
	 * Returns CSS classes to add to a link to the given user.
	 *
	 * This adds the `mw-userlink` and `mw-tempuserlink` classes, which
	 * don't require a database lookup to apply; since this method
	 * is potentially called on a large number of page links, a batch
	 * of some kind would have to be used if evaluating link classes
	 * involved a DB lookup.
	 *
	 * @param string $userName The (canonical) user name to render a link for.
	 * @return string[] CSS classes.
	 */
	private function getLinkClassesFromUserName( string $userName ): array {
		$classes = [ 'mw-userlink' ];
		if ( $this->tempUserConfig->isTempName( $userName ) ) {
			$classes[] = 'mw-tempuserlink';
		}
		return $classes;
	}

	/**
	 * Convenience function for LinkRenderer: return the CSS classes
	 * to add to a given LinkTarget if it represents a link to a user.
	 *
	 * @see ::getLinkClassesFromUserName() for details on the classes
	 *   applied.
	 * @internal For use by LinkRenderer::getLinkClasses()
	 */
	public function getLinkClasses( LinkTarget $target ): array {
		$ns = $target->getNamespace();
		$userName = null;
		if ( $ns === NS_USER || $ns === NS_USER_TALK ) {
			// Recognize direct links to users
			$userName = $target->getText();
		} elseif ( $ns === NS_SPECIAL ) {
			// Recognize links to contributions pages
			[ $name, $subpage ] = $this->specialPageFactory->resolveAlias( $target->getText() );
			if ( $name === 'Contributions' && $subpage !== null ) {
				$userName = $subpage;
			}
		}
		if ( $userName !== null ) {
			$userName = $this->userNameUtils->getCanonical( $userName );
			if ( $userName !== false ) {
				return $this->getLinkClassesFromUserName( $userName );
			}
		}
		return [];
	}
}
