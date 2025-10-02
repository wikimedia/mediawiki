<?php
/**
 * Class for temporary blocks created on enforcement.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Block;

use MediaWiki\User\UserIdentity;

/**
 * System blocks are temporary blocks that are created on enforcement (e.g.
 * from IP lists) and are not saved to the database. The target of a
 * system block is an IP address. System blocks do not give rise to
 * autoblocks and are not tracked with cookies.
 *
 * @since 1.34
 */
class SystemBlock extends AbstractBlock {
	/** @var string|null */
	private $systemBlockType;

	/**
	 * Create a new block with specified parameters on a user, IP or IP range.
	 *
	 * @param array $options Parameters of the block, with options supported by
	 *  `AbstractBlock::__construct`, and also:
	 *  - systemBlock: (string) Indicate that this block is automatically created by
	 *    MediaWiki rather than being stored in the database. Value is a string to
	 *    return from self::getSystemBlockType().
	 */
	public function __construct( array $options = [] ) {
		parent::__construct( $options );

		$defaults = [
			'systemBlock' => null,
		];

		$options += $defaults;

		$this->systemBlockType = $options['systemBlock'];
	}

	/**
	 * Get the system block type, if any. A SystemBlock can have the following types:
	 * - 'proxy': the IP is listed in $wgProxyList
	 * - 'dnsbl': the IP is associated with a listed domain in $wgDnsBlacklistUrls
	 * - 'wgSoftBlockRanges': the IP is covered by $wgSoftBlockRanges
	 * - 'global-block': for backwards compatibility with the UserIsBlockedGlobally hook
	 *
	 * @since 1.29
	 * @return string|null
	 */
	public function getSystemBlockType() {
		return $this->systemBlockType;
	}

	/**
	 * @inheritDoc
	 */
	public function getIdentifier( $wikiId = self::LOCAL ) {
		return $this->getSystemBlockType();
	}

	/**
	 * @inheritDoc
	 */
	public function appliesToPasswordReset() {
		switch ( $this->getSystemBlockType() ) {
			case null:
			case 'global-block':
				return $this->isCreateAccountBlocked();
			case 'proxy':
				return true;
			case 'dnsbl':
			case 'wgSoftBlockRanges':
				return false;
			default:
				return true;
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getBy( $wikiId = self::LOCAL ): int {
		$this->assertWiki( $wikiId );
		return 0;
	}

	/**
	 * @inheritDoc
	 */
	public function getByName() {
		return '';
	}

	/**
	 * @inheritDoc
	 */
	public function getBlocker(): ?UserIdentity {
		return null;
	}
}
