<?php

namespace MediaWiki\EditPage;

use MediaWiki\Context\IContextSource;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use MediaWiki\Utils\MWCryptRand;

/**
 * Securely stash user-posted data in the session, modeled on
 * AuthManagerSpecialPage::handleReauthBeforeExecute().
 */
trait DataStashTrait {
	private const STASH_TTL = 600;

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
	 * Called from setPostDataInRequest() once the stashed data has been
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

	protected function destroyStashedData(): bool {
		$context = $this->getContext();
		$request = $context->getRequest();
		$session = $request->getSession();
		$uniqueId = $request->getVal( 'requestUniqueId' );
		$key = $this->getStashKey() . ':' . $uniqueId;
		if ( $session->remove( $key ) ) {
			return true;
		} else {
			return false;
		}
	}

	protected function enableReauthPopup( string $jsPopupModule, ?string $operation ): void {
		$context = $this->getContext();
		if ( $operation === null ) {
			return;
		}
		$out = $this->getContext()->getOutput();
		$out->addModules( $jsPopupModule );
		$out->addJsConfigVars( [
			'wgReauthOperation' => $operation
		] );
	}

	protected function doReauthRedirect( PermissionStatus $status, array $queryParams ): void {
		$context = $this->getContext();
		$context->getOutput()->redirect( SpecialPage::getTitleFor( 'Userlogin' )->getFullURL( [
			'force' => $status->getReauthOperation(),
			'returnto' => $this->getTitle()->getPrefixedDBkey(),
			'returntoquery' => wfArrayToCgi( array_diff_key(
				$queryParams,
				[ 'title' => true, 'returnto' => true, 'returntoquery' => true ]
			) ),
		], false, PROTO_HTTPS ) );
	}
}
