<?php

/**
 * WikitextMessagePoster
 *
 * @copyright GPL http://www.gnu.org/copyleft/gpl.html
 * @author Matthew Flaschen
 * @author Based on code from MassMessage (which was based on TranslationNotifications)
 * @ingroup MessagePoster
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace MediaWiki\MessagePoster;

use ApiBase;
use ApiMain;
use DerivativeRequest;
use MWException;
use RequestContext;
use Status;
use Title;
use User;

/**
 * This is an implementation of IMessagePoster for wikitext talk pages.
 */
class WikitextMessagePoster implements IMessagePoster {
	public function postTopic( Title $title, User $user, $subject, $body, $flags = 0 ) {
		// Add signature if needed
		if ( strpos( $body, '~~~' ) === false ) {
			$body .= "\n\n~~~~";
		}

		global $wgUser, $wgRequest;

		$oldRequest = $wgRequest;
		$oldUser = $wgUser;

		$params = [
			'action' => 'edit',
			'title' => $title->getPrefixedText(),
			'section' => 'new',
			'summary' => $subject,
			'text' => $body,
			'token' => $user->getEditToken(),
		];

		if ( $flags & IMessagePoster::BOT_EDIT ) {
			$params['bot'] = true;
		}

		$wgRequest = new DerivativeRequest(
			$wgRequest,
			$params,
			true // was posted?
		);
		// New user objects will use $wgRequest, so we set that
		// to our DerivativeRequest, so we don't run into any issues
		$wgUser = $user;
		$wgUser->clearInstanceCache(); // Force rights reload (for IP block exemption)

		$context = RequestContext::getMain();
		// All further internal API requests will use the main
		// RequestContext, so setting it here will fix it for
		// all other internal uses, like how LQT does
		$oldCUser = $context->getUser();
		$oldCRequest = $context->getRequest();
		$context->setUser( $wgUser );
		$context->setRequest( $wgRequest );

		$api = new ApiMain(
			$wgRequest,
			true // enable write?
		);

		try {
			$api->execute();
			$data = $api->getResult()->getResultData();

			if ( $data['edit']['result'] === 'Failure' ) {
				// HACK: This is ugly, but it should only happen on hook aborts.
				// ApiEditPage sometimes throws on hook aborts, and sometimes
				// returns, depending on whether there is an apiHookResult.  We
				// need to throw when it doesn't.
				$errorMessage = ApiBase::$messageMap['hookaborted']['info'] . ': ' .
					json_encode( $data['edit'] );

				throw new MWException( $errorMessage );
			}

			return Status::newGood();
		} finally {
			// Cleanup all the stuff we polluted
			$context->setUser( $oldCUser );
			$context->setRequest( $oldCRequest );
			$wgUser = $oldUser;
			$wgRequest = $oldRequest;
		}
	}
}
