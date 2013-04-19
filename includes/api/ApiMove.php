<?php
/**
 *
 *
 * Created on Oct 31, 2007
 *
 * Copyright © 2007 Roan Kattouw "<Firstname>.<Lastname>@gmail.com"
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
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * API Module to move pages
 * @ingroup API
 */
class ApiMove extends ApiBase {

	public function execute() {
		$user = $this->getUser();
		$params = $this->extractRequestParams();

		$this->requireOnlyOneParameter( $params, 'from', 'fromid' );

		if ( isset( $params['from'] ) ) {
			$fromTitle = Title::newFromText( $params['from'] );
			if ( !$fromTitle || $fromTitle->isExternal() ) {
				$this->dieUsageMsg( array( 'invalidtitle', $params['from'] ) );
			}
		} elseif ( isset( $params['fromid'] ) ) {
			$fromTitle = Title::newFromID( $params['fromid'] );
			if ( !$fromTitle ) {
				$this->dieUsageMsg( array( 'nosuchpageid', $params['fromid'] ) );
			}
		}

		if ( !$fromTitle->exists() ) {
			$this->dieUsageMsg( 'notanarticle' );
		}
		$fromTalk = $fromTitle->getTalkPage();

		$toTitle = Title::newFromText( $params['to'] );
		if ( !$toTitle || $toTitle->isExternal() ) {
			$this->dieUsageMsg( array( 'invalidtitle', $params['to'] ) );
		}
		$toTalk = $toTitle->getTalkPage();

		if ( $toTitle->getNamespace() == NS_FILE
			&& !RepoGroup::singleton()->getLocalRepo()->findFile( $toTitle )
			&& wfFindFile( $toTitle ) )
		{
			if ( !$params['ignorewarnings'] && $user->isAllowed( 'reupload-shared' ) ) {
				$this->dieUsageMsg( 'sharedfile-exists' );
			} elseif ( !$user->isAllowed( 'reupload-shared' ) ) {
				$this->dieUsageMsg( 'cantoverwrite-sharedfile' );
			}
		}

		// Move the page
		$toTitleExists = $toTitle->exists();
		$retval = $fromTitle->moveTo( $toTitle, true, $params['reason'], !$params['noredirect'] );
		if ( $retval !== true ) {
			$this->dieUsageMsg( reset( $retval ) );
		}

		$r = array( 'from' => $fromTitle->getPrefixedText(), 'to' => $toTitle->getPrefixedText(), 'reason' => $params['reason'] );

		if ( $fromTitle->exists() ) {
			//NOTE: we assume that if the old title exists, it's because it was re-created as
			// a redirect to the new title. This is not safe, but what we did before was
			// even worse: we just determined whether a redirect should have been created,
			// and reported that it was created if it should have, without any checks.
			// Also note that isRedirect() is unreliable because of bug 37209.
			$r['redirectcreated'] = '';
		}

		if ( $toTitleExists ) {
			$r['moveoverredirect'] = '';
		}

		// Move the talk page
		if ( $params['movetalk'] && $fromTalk->exists() && !$fromTitle->isTalkPage() ) {
			$toTalkExists = $toTalk->exists();
			$retval = $fromTalk->moveTo( $toTalk, true, $params['reason'], !$params['noredirect'] );
			if ( $retval === true ) {
				$r['talkfrom'] = $fromTalk->getPrefixedText();
				$r['talkto'] = $toTalk->getPrefixedText();
				if ( $toTalkExists ) {
					$r['talkmoveoverredirect'] = '';
				}
			} else {
				// We're not gonna dieUsage() on failure, since we already changed something
				$parsed = $this->parseMsg( reset( $retval ) );
				$r['talkmove-error-code'] = $parsed['code'];
				$r['talkmove-error-info'] = $parsed['info'];
			}
		}

		$result = $this->getResult();

		// Move subpages
		if ( $params['movesubpages'] ) {
			$r['subpages'] = $this->moveSubpages( $fromTitle, $toTitle,
					$params['reason'], $params['noredirect'] );
			$result->setIndexedTagName( $r['subpages'], 'subpage' );

			if ( $params['movetalk'] ) {
				$r['subpages-talk'] = $this->moveSubpages( $fromTalk, $toTalk,
					$params['reason'], $params['noredirect'] );
				$result->setIndexedTagName( $r['subpages-talk'], 'subpage' );
			}
		}

		$watch = 'preferences';
		if ( isset( $params['watchlist'] ) ) {
			$watch = $params['watchlist'];
		} elseif ( $params['watch'] ) {
			$watch = 'watch';
		} elseif ( $params['unwatch'] ) {
			$watch = 'unwatch';
		}

		// Watch pages
		$this->setWatch( $watch, $fromTitle, 'watchmoves' );
		$this->setWatch( $watch, $toTitle, 'watchmoves' );

		$result->addValue( null, $this->getModuleName(), $r );
	}

	/**
	 * @param Title $fromTitle
	 * @param Title $toTitle
	 * @param  $reason
	 * @param  $noredirect
	 * @return array
	 */
	public function moveSubpages( $fromTitle, $toTitle, $reason, $noredirect ) {
		$retval = array();
		$success = $fromTitle->moveSubpages( $toTitle, true, $reason, !$noredirect );
		if ( isset( $success[0] ) ) {
			return array( 'error' => $this->parseMsg( $success ) );
		} else {
			// At least some pages could be moved
			// Report each of them separately
			foreach ( $success as $oldTitle => $newTitle ) {
				$r = array( 'from' => $oldTitle );
				if ( is_array( $newTitle ) ) {
					$r['error'] = $this->parseMsg( reset( $newTitle ) );
				} else {
					// Success
					$r['to'] = $newTitle;
				}
				$retval[] = $r;
			}
		}
		return $retval;
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return array(
			'from' => null,
			'fromid' => array(
				ApiBase::PARAM_TYPE => 'integer'
			),
			'to' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'token' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'reason' => '',
			'movetalk' => false,
			'movesubpages' => false,
			'noredirect' => false,
			'watch' => array(
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_DEPRECATED => true,
			),
			'unwatch' => array(
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_DEPRECATED => true,
			),
			'watchlist' => array(
				ApiBase::PARAM_DFLT => 'preferences',
				ApiBase::PARAM_TYPE => array(
					'watch',
					'unwatch',
					'preferences',
					'nochange'
				),
			),
			'ignorewarnings' => false
		);
	}

	public function getParamDescription() {
		$p = $this->getModulePrefix();
		return array(
			'from' => "Title of the page you want to move. Cannot be used together with {$p}fromid",
			'fromid' => "Page ID of the page you want to move. Cannot be used together with {$p}from",
			'to' => 'Title you want to rename the page to',
			'token' => 'A move token previously retrieved through prop=info',
			'reason' => 'Reason for the move',
			'movetalk' => 'Move the talk page, if it exists',
			'movesubpages' => 'Move subpages, if applicable',
			'noredirect' => 'Don\'t create a redirect',
			'watch' => 'Add the page and the redirect to your watchlist',
			'unwatch' => 'Remove the page and the redirect from your watchlist',
			'watchlist' => 'Unconditionally add or remove the page from your watchlist, use preferences or do not change watch',
			'ignorewarnings' => 'Ignore any warnings'
		);
	}

	public function getResultProperties() {
		return array(
			'' => array(
				'from' => 'string',
				'to' => 'string',
				'reason' => 'string',
				'redirectcreated' => 'boolean',
				'moveoverredirect' => 'boolean',
				'talkfrom' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'talkto' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'talkmoveoverredirect' => 'boolean',
				'talkmove-error-code' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'talkmove-error-info' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				)
			)
		);
	}

	public function getDescription() {
		return 'Move a page';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(),
			$this->getRequireOnlyOneParameterErrorMessages( array( 'from', 'fromid' ) ),
			array(
				array( 'invalidtitle', 'from' ),
				array( 'nosuchpageid', 'fromid' ),
				array( 'notanarticle' ),
				array( 'invalidtitle', 'to' ),
				array( 'sharedfile-exists' ),
			)
		);
	}

	public function needsToken() {
		return true;
	}

	public function getTokenSalt() {
		return '';
	}

	public function getExamples() {
		return array(
			'api.php?action=move&from=Badtitle&to=Goodtitle&token=123ABC&reason=Misspelled%20title&movetalk=&noredirect='
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Move';
	}
}
