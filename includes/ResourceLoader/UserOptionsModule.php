<?php

namespace MediaWiki\ResourceLoader;

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\UserTimeCorrection;

/**
 * @license GPL-2.0-or-later
 * @file
 * @author Trevor Parscal
 * @author Roan Kattouw
 */

/**
 * Module for per-user private data that is transmitted on all HTML web responses.
 *
 * This module is embedded by ClientHtml and sent to the browser
 * by OutputPage as part of the HTML `<head>`.
 *
 * @ingroup ResourceLoader
 * @internal
 */
class UserOptionsModule extends Module {

	/** @inheritDoc */
	protected $origin = self::ORIGIN_CORE_INDIVIDUAL;

	/**
	 * @param Context $context
	 * @return string JavaScript code
	 */
	public function getScript( Context $context ) {
		$user = $context->getUserObj();

		$tokens = [
			// Replacement is tricky - T287542
			'patrolToken' => $user->getEditToken( 'patrol' ),
			'watchToken' => $user->getEditToken( 'watch' ),
			'csrfToken' => $user->getEditToken(),
		];
		$script = 'mw.user.tokens.set(' . $context->encodeJson( $tokens ) . ');' . "\n";

		$userOptionsLookup = MediaWikiServices::getInstance()->getUserOptionsLookup();

		// Optimisation: Exclude the defaults, which we load separately and allow the browser
		// to cache across page views. The defaults are loaded before this code executes,
		// as part of the "mediawiki.base" module.
		$options = $userOptionsLookup->getOptions( $user, UserOptionsLookup::EXCLUDE_DEFAULTS );

		$keysToExclude = [];
		$this->getHookRunner()->onResourceLoaderExcludeUserOptions( $keysToExclude, $context );
		foreach ( $keysToExclude as $excludedKey ) {
			unset( $options[ $excludedKey ] );
		}

		// Update timezone offset (T323193)
		if ( isset( $options['timecorrection'] ) ) {
			$corr = new UserTimeCorrection(
				$options['timecorrection'],
				null,
				$this->getConfig()->get( MainConfigNames::LocalTZoffset )
			);
			$options['timecorrection'] = $corr->toString();
		}

		// Optimisation: Only output this function call if the user has non-default settings.
		if ( $options ) {
			$script .= 'mw.user.options.set(' . $context->encodeJson( $options ) . ');' . "\n";
		}

		return $script;
	}

	/**
	 * @return bool
	 */
	public function supportsURLLoading() {
		return false;
	}

	/**
	 * @return string
	 */
	public function getGroup() {
		return self::GROUP_PRIVATE;
	}
}
