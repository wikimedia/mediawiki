<?php

namespace MediaWiki\EditPage;

use MediaWiki\Context\IContextSource;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use MediaWiki\Utils\MWCryptRand;
use OOUI\IconWidget;

/**
 * Securely stash user-posted data in the session, modeled on
 * AuthManagerSpecialPage::handleReauthBeforeExecute().
 *
 * @since 1.47
 */
trait DataStashTrait {
	private const int STASH_TTL = 600;

	private ?string $stashKey = null;

	/** @return Title */
	abstract protected function getTitle();

	/** @return IContextSource */
	abstract protected function getContext();

	private function getStashKey(): ?string {
		return $this->stashKey;
	}

	protected function setStashKey( string $keyValue ): void {
		if ( $keyValue ) {
			$this->stashKey = $keyValue;
		}
	}

	private function getUniqueId(): string {
		return MWCryptRand::generateHex( 6 );
	}

	/**
	 * Apply retrieved stashed user data to a page form
	 *
	 * Called from retrieveStashedData() once the stashed data has been
	 * retrieved. The default is a no-op; the exhibiting class overrides it to
	 * act on the data, typically by setting form state such as $this->textbox1.
	 *
	 * @param array<string,mixed> $data Retrieved stashed POST data (field => value)
	 */
	protected function handleRetrievedData( array $data ): void {
	}

	protected function stashDataOnPost(): array {
		$context = $this->getContext();
		$request = $context->getRequest();
		$session = $request->getSession();
		$queryParams = $request->getQueryValues();

		if ( $request->wasPosted() ) {
			$data = array_diff_assoc( $request->getValues(), $queryParams );
			if ( $data ) {
				$uniqueId = $this->getUniqueId();
				$session->persist();
				$session->setSecret( $this->getStashKey() . ':' . $uniqueId, [
					'data' => $data,
					'ts'   => time(),
				] );
				$queryParams['requestUniqueId'] = $uniqueId;
			}
		}
		return $queryParams;
	}

	protected function retrieveStashedData(): bool {
		$context = $this->getContext();
		$request = $context->getRequest();
		$session = $request->getSession();
		$uniqueId = $request->getVal( 'requestUniqueId' );

		if ( $uniqueId === null ) {
			return false;
		}

		$key = $this->getStashKey() . ':' . $uniqueId;
		$stash = $session->getSecret( $key );

		if ( !is_array( $stash ) || ( time() - ( $stash['ts'] ?? 0 ) ) > self::STASH_TTL ) {
			$session->remove( $key );
			return false;
		}

		$this->handleRetrievedData( $stash['data'] );

		return true;
	}

	protected function destroyStashedData(): void {
		$context = $this->getContext();
		$request = $context->getRequest();
		$session = $request->getSession();
		$uniqueId = $request->getVal( 'requestUniqueId' );
		$key = $this->getStashKey() . ':' . $uniqueId;
		$session->remove( $key );
	}

	protected function enableReauthPopup( string $jsPopupModule, ?string $operation ): void {
		if ( $operation === null ) {
			return;
		}
		$out = $this->getContext()->getOutput();
		$out->addModules( $jsPopupModule );
		$out->addJsConfigVars( [
			'wgReauthOperation' => $operation
		] );
	}

	/**
	 * Return [ 'icon' => 'lock' ] to merge into a hand-built OOUI
	 * ButtonInputWidget's attribs. Loads the icons-moderation styles module.
	 * Guard the call with your own reauth-required state.
	 *
	 * For an HTMLForm submit button, see HTMLForm::setSubmitLockIcon().
	 * For a standalone icon not on a button, see getReauthLockOOUIIcon().
	 *
	 * @return array
	 */
	protected function getReauthLockButtonAttribs(): array {
		$this->getContext()->getOutput()->addModuleStyles( 'oojs-ui.styles.icons-moderation' );
		return [ 'icon' => 'lock' ];
	}

	/**
	 * Return a standalone OOUI lock IconWidget (not attached to a button).
	 * Loads the icons-moderation styles module. Guard the call with your own
	 * reauth-required state.
	 *
	 * To attach a lock icon to a button instead, see getReauthLockButtonAttribs()
	 * (hand-built OOUI button) or HTMLForm::setSubmitLockIcon() (HTMLForm submit).
	 *
	 * @return IconWidget
	 */
	protected function getReauthLockOOUIIcon(): IconWidget {
		return new IconWidget( $this->getReauthLockButtonAttribs() );
	}

	/**
	 * Redirect to the reauth flow on Special:UserLogin. Pass an optional
	 * $subaction so the reauth banner can pick an action-specific message
	 * (userlogin-reauth-banner-<operation>-<subaction>).
	 */
	protected function doReauthRedirect(
		PermissionStatus $status,
		array $queryParams,
		?string $subaction = null
	): void {
		$context = $this->getContext();
		$loginQuery = [
			'force' => $status->getReauthOperation(),
			'returnto' => $this->getTitle()->getPrefixedDBkey(),
			'returntoquery' => wfArrayToCgi( array_diff_key(
				$queryParams,
				[ 'title' => true, 'returnto' => true, 'returntoquery' => true ]
			) ),
		];
		if ( $subaction !== null ) {
			$loginQuery['reauthSubaction'] = $subaction;
		}
		$context->getOutput()->redirect(
			SpecialPage::getTitleFor( 'Userlogin' )->getFullURL( $loginQuery, false, PROTO_HTTPS )
		);
	}
}
