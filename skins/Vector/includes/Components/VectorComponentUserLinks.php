<?php
namespace MediaWiki\Skins\Vector\Components;

use Linker;
use MediaWiki\Skin\SkinComponentLink;
use Message;
use MessageLocalizer;
use Title;
use User;

/**
 * VectorComponentUserLinks component
 */
class VectorComponentUserLinks implements VectorComponent {
	/** @var MessageLocalizer */
	private $localizer;
	/** @var User */
	private $user;
	/** @var array */
	private $portletData;
	/** @var array */
	private $linkOptions;
	/** @var string */
	private $userIcon;

	/**
	 * @param MessageLocalizer $localizer
	 * @param User $user
	 * @param array $portletData
	 * @param array $linkOptions
	 * @param string $userIcon that represents the current type of user
	 */
	public function __construct(
		MessageLocalizer $localizer,
		User $user,
		array $portletData,
		array $linkOptions,
		string $userIcon = 'userAvatar'
	) {
		$this->localizer = $localizer;
		$this->user = $user;
		$this->portletData = $portletData;
		$this->linkOptions = $linkOptions;
		$this->userIcon = $userIcon;
	}

	/**
	 * @param string $key
	 * @return Message
	 */
	private function msg( $key ): Message {
		return $this->localizer->msg( $key );
	}

	/**
	 * @param bool $isDefaultAnonUserLinks
	 * @param bool $isAnonEditorLinksEnabled
	 * @return VectorComponentDropdown
	 */
	private function getDropdown( $isDefaultAnonUserLinks, $isAnonEditorLinksEnabled ) {
		$user = $this->user;
		$isAnon = !$user->isRegistered();

		$class = 'vector-user-menu';
		$class .= ' mw-ui-icon-flush-right';
		$class .= !$isAnon ?
			' vector-user-menu-logged-in' :
			' vector-user-menu-logged-out';

		// Hide entire user links dropdown on larger viewports if it only contains
		// create account & login link, which are only shown on smaller viewports
		if ( $isAnon && $isDefaultAnonUserLinks && !$isAnonEditorLinksEnabled ) {
			$class .= ' user-links-collapsible-item';
		}

		$tooltip = '';
		$icon = $this->userIcon;
		if ( $icon === '' ) {
			$icon = 'ellipsis';
			// T287494 We use tooltip messages to provide title attributes on hover over certain menu icons.
			// For modern Vector, the "tooltip-p-personal" key is set to "User menu" which is appropriate for
			// the user icon (dropdown indicator for user links menu) for logged-in users.
			// This overrides the tooltip for the user links menu icon which is an ellipsis for anonymous users.
			$tooltip = Linker::tooltip( 'vector-anon-user-menu-title' ) ?? '';
		}

		return new VectorComponentDropdown(
			'vector-user-links-dropdown', $this->msg( 'personaltools' )->text(), $class, $icon, $tooltip
		);
	}

	/**
	 * @param bool $isDefaultAnonUserLinks
	 * @param bool $isAnonEditorLinksEnabled
	 * @return array
	 */
	private function getDropdownMenus( $isDefaultAnonUserLinks, $isAnonEditorLinksEnabled ) {
		$user = $this->user;
		$isAnon = !$user->isRegistered();
		$portletData = $this->portletData;

		// Hide default user menu on larger viewports if it only contains
		// create account & login link, which are only shown on smaller viewports
		// FIXME: Replace array_merge with an add class helper function
		$userMenuClass = $portletData[ 'data-user-menu' ][ 'class' ];
		$userMenuClass = $isAnon && $isDefaultAnonUserLinks ?
			$userMenuClass . ' user-links-collapsible-item' : $userMenuClass;
		$dropdownMenus = [
			new VectorComponentMenu( [
				'label' => null,
				'class' => $userMenuClass
			] + $portletData[ 'data-user-menu' ] )
		];

		if ( $isAnon ) {
			// T317789: The `anontalk` and `anoncontribs` links will not be added to
			// the menu if `$wgGroupPermissions['*']['edit']` === false which can
			// leave the menu empty due to our removal of other user menu items in
			// `Hooks::updateUserLinksDropdownItems`. In this case, we do not want
			// to render the anon "learn more" link.
			if ( $isAnonEditorLinksEnabled ) {
				$anonEditorLabelLinkData = [
					'text' => $this->msg( 'vector-anon-user-menu-pages-learn' )->text(),
					'href' => Title::newFromText( $this->msg( 'vector-intro-page' )->text() )->getLocalURL(),
					'aria-label' => $this->msg( 'vector-anon-user-menu-pages-label' )->text(),
				];
				$anonEditorLabelLink = new SkinComponentLink(
					'', $anonEditorLabelLinkData, $this->localizer, $this->linkOptions
				);
				$anonEditorLabelLinkHtml = $anonEditorLabelLink->getTemplateData()[ 'html' ];
				$dropdownMenus[] = new VectorComponentMenu( [
					'label' => $this->msg( 'vector-anon-user-menu-pages' )->text() . " " . $anonEditorLabelLinkHtml,
				] + $portletData[ 'data-user-menu-anon-editor' ] );
			}
		} else {
			// Logout isnt enabled for temp users, who are considered still considered registeredt
			$isLogoutLinkEnabled = isset( $portletData[ 'data-user-menu-logout' ][ 'is-empty' ] ) &&
				!$portletData[ 'data-user-menu-logout'][ 'is-empty' ];
			if ( $isLogoutLinkEnabled ) {
				$dropdownMenus[] = new VectorComponentMenu( [
					'label' => null
				] + $portletData[ 'data-user-menu-logout' ] );
			}
		}

		return $dropdownMenus;
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		$portletData = $this->portletData;

		$isDefaultAnonUserLinks = count( $portletData['data-user-menu']['array-items'] ) === 2;
		$isAnonEditorLinksEnabled = isset( $portletData['data-user-menu-anon-editor']['is-empty'] )
			&& !$portletData['data-user-menu-anon-editor']['is-empty'];

		$overflowMenu = new VectorComponentMenu( [
			'label' => null,
		] + $portletData[ 'data-vector-user-menu-overflow' ] );

		return [
			'is-wide' => count( $overflowMenu ) > 3,
			'data-user-links-overflow-menu' => $overflowMenu->getTemplateData(),
			'data-user-links-dropdown' => $this->getDropdown( $isDefaultAnonUserLinks, $isAnonEditorLinksEnabled )
				->getTemplateData(),
			'data-user-links-dropdown-menus' => array_map( static function ( $menu ) {
				return $menu->getTemplateData();
			}, $this->getDropdownMenus( $isDefaultAnonUserLinks, $isAnonEditorLinksEnabled ) ),
		];
	}
}
