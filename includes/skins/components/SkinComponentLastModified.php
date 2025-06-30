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
	/** @var string|null|false */
	private $revisionTimestamp;
	/** @var User */
	private $user;

	/**
	 * @param SkinComponentRegistryContext $skinContext
	 * @param string|null|false $revisionTimestamp
	 */
	public function __construct( SkinComponentRegistryContext $skinContext, $revisionTimestamp = null ) {
		$this->revisionTimestamp = $revisionTimestamp;
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

		if ( $timestamp ) {
			$d = $language->userDate( $timestamp, $user );
			$t = $language->userTime( $timestamp, $user );
			$s = ' ' . $localizer->msg( 'lastmodifiedat', $d, $t )->parse();
		} else {
			$s = '';
			$d = null;
			$t = null;
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
