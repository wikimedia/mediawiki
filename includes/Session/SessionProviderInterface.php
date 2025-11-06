<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Session;

use MediaWiki\Language\Language;
use MediaWiki\Message\Message;

/**
 * MediaWiki\Session\Provider interface
 *
 * This exists to make IDEs happy, so they don't see the
 * internal-but-required-to-be-public methods on SessionProvider.
 *
 * @since 1.27
 * @ingroup Session
 */
interface SessionProviderInterface {

	/**
	 * Return an identifier for this session type
	 *
	 * @param Language $lang Language to use.
	 * @return string
	 */
	public function describe( Language $lang );

	/**
	 * Return a Message for why sessions might not be being persisted.
	 *
	 * For example, "check whether you're blocking our cookies".
	 *
	 * @return Message|null
	 */
	public function whyNoSession();

	/**
	 * Returns true if this provider is safe against csrf attacks, or false otherwise
	 *
	 * @return bool
	 */
	public function safeAgainstCsrf();

	/**
	 * Returns true if this provider is exempt from autocreate user permissions check.
	 *
	 * @return bool
	 * @since 1.42
	 */
	public function canAlwaysAutocreate(): bool;
}
