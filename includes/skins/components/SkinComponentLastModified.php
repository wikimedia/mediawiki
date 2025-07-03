<?php

namespace MediaWiki\Skin;

use MediaWiki\Language\Language;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\User;
use MessageLocalizer;

class SkinComponentLastModified implements SkinComponent {
	/** @var Language */
	private $language;
	/** @var MessageLocalizer */
	private $localizer;
	/** @var User */
	private $user;

	/**
	 * @param SkinComponentRegistryContext $skinContext
	 * @param string|null|false $revisionTimestamp
	 * @param bool $useParsoid whether Parsoid was used to render this page
	 */
	public function __construct(
		SkinComponentRegistryContext $skinContext,
		private $revisionTimestamp = null,
		private bool $useParsoid = false,
	) {
		$this->localizer = $skinContext->getMessageLocalizer();
		$this->user = $skinContext->getUser();
		$this->language = $skinContext->getLanguage();
	}

	/**
	 * Get the timestamp of the latest revision, formatted in user language
	 *
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		$localizer = $this->localizer;
		$user = $this->user;
		$language = $this->language;
		$timestamp = $this->revisionTimestamp;
		$useParsoid = $this->useParsoid;

		if ( $timestamp ) {
			$d = $language->userDate( $timestamp, $user );
			$t = $language->userTime( $timestamp, $user );
			$msg = $localizer->msg( 'lastmodifiedat', $d, $t );
			$s = ' ' . $msg->parse();
		} else {
			$s = '';
			$d = null;
			$t = null;
		}

		$msg = $useParsoid ?
			$localizer->msg( 'renderedwith-parsoid' ) :
			$localizer->msg( 'renderedwith-legacy' );
		if ( !$msg->isDisabled() ) {
			$s .= ' ' . $msg->parse();
		}

		$isLagged = MediaWikiServices::getInstance()->getDBLoadBalancerFactory()->laggedReplicaUsed();
		if ( $isLagged ) {
			$s .= ' <strong>' .
				$localizer->msg( 'laggedreplicamode' )->parse() .
				'</strong>';
		}

		return [
			'is-replica' => $isLagged,
			'text' => $s,
			'date' => $d,
			'time' => $t,
			'timestamp' => $timestamp,
		];
	}
}
