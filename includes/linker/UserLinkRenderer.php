<?php
declare( strict_types=1 );

namespace MediaWiki\Linker;

use HtmlArmor;
use MapCacheLRU;
use MediaWiki\Context\IContextSource;
use MediaWiki\Html\Html;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\ExternalUserNames;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\TempUser\TempUserDetailsLookup;
use MediaWiki\User\UserIdentity;
use MessageLocalizer;
use Wikimedia\Assert\Assert;
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
	private TempUserDetailsLookup $tempUserDetailsLookup;

	/**
	 * Process cache for user links keyed by user name,
	 * to optimize rendering large pagers with potentially repeated user links.
	 *
	 * @var MapCacheLRU
	 */
	private MapCacheLRU $userLinkCache;

	/**
	 * Counter used to generate process-unique IDs for expired temporary account links.
	 * @var int
	 */
	private static int $nextExpiredTempUserLinkId = 0;

	public function __construct(
		TempUserConfig $tempUserConfig,
		SpecialPageFactory $specialPageFactory,
		LinkRenderer $linkRenderer,
		TempUserDetailsLookup $tempUserDetailsLookup
	) {
		$this->tempUserConfig = $tempUserConfig;
		$this->specialPageFactory = $specialPageFactory;
		$this->linkRenderer = $linkRenderer;
		$this->tempUserDetailsLookup = $tempUserDetailsLookup;

		// Set a large enough cache size to accommodate long pagers,
		// such as Special:RecentChanges with a high limit.
		$this->userLinkCache = new MapCacheLRU( 1_000 );
	}

	/**
	 * Reset the counter used to generate process-unique IDs for expired temporary account links.
	 * Useful for testing.
	 */
	public static function resetExpiredTempUserLinkIdCounter(): void {
		Assert::precondition(
			defined( 'MW_PHPUNIT_TEST' ),
			'This function is only meant to be used by tests.'
		);

		self::$nextExpiredTempUserLinkId = 0;
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
		$outputPage->addModuleStyles( [ 'mediawiki.interface.helpers.styles' ] );
		$outputPage->addModules( [ 'mediawiki.interface.helpers' ] );

		// Expired temporary account user links are not cacheable, because they contain a tooltip
		// that needs to have a unique ID used in an ARIA association with the corresponding link.
		if ( $this->tempUserDetailsLookup->isExpired( $targetUser ) ) {
			return $this->renderUserLink( $targetUser, $context, $altUserName, $attributes );
		}

		return $this->userLinkCache->getWithSetCallback(
			$this->userLinkCache->makeKey(
				$targetUser->getName(),
				$altUserName ?? '',
				implode( ' ', $attributes )
			),
			fn () => $this->renderUserLink( $targetUser, $context, $altUserName, $attributes )
		);
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

		$classes = [ 'mw-userlink' ];

		$postfix = '';

		if ( $this->tempUserConfig->isTempName( $userName ) ) {
			$classes[] = 'mw-tempuserlink';

			// Adjust the styling of expired temporary account links (T358469).
			if ( $this->tempUserDetailsLookup->isExpired( $targetUser ) ) {
				$classes[] = 'mw-tempuserlink-expired';
				$tooltipId = sprintf(
					'mw-tempuserlink-expired-tooltip-%d',
					self::$nextExpiredTempUserLinkId++
				);

				$postfix = Html::element(
					'div',
					[
						'id' => $tooltipId,
						'role' => 'tooltip',
						'class' => 'cdx-tooltip mw-tempuserlink-expired--tooltip',
					],
					$messageLocalizer->msg( 'tempuser-expired-link-tooltip' )->text()
				);

				$attributes['aria-describedby'] = $tooltipId;

				// Hide default link title when rendering expired temporary account links
				// to avoid conflicting with the tooltip.
				$attributes['title'] = '';
			}

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
			return $this->linkRenderer->makeLink( $page, new HtmlArmor( $linkText ), $attributes ) . $postfix;
		}

		return Html::rawElement( 'span', $attributes, $linkText ) . $postfix;
	}
}
