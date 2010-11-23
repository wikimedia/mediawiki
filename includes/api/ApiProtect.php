<?php
/**
 * API for MediaWiki 1.8+
 *
 * Created on Sep 1, 2007
 *
 * Copyright © 2007 Roan Kattouw <Firstname>.<Lastname>@home.nl
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

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( "ApiBase.php" );
}

/**
 * @ingroup API
 */
class ApiProtect extends ApiBase {

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}

	public function execute() {
		global $wgUser, $wgRestrictionLevels;
		$params = $this->extractRequestParams();

		$titleObj = Title::newFromText( $params['title'] );
		if ( !$titleObj ) {
			$this->dieUsageMsg( array( 'invalidtitle', $params['title'] ) );
		}

		$errors = $titleObj->getUserPermissionsErrors( 'protect', $wgUser );
		if ( $errors ) {
			// We don't care about multiple errors, just report one of them
			$this->dieUsageMsg( reset( $errors ) );
		}

		$expiry = (array)$params['expiry'];
		if ( count( $expiry ) != count( $params['protections'] ) ) {
			if ( count( $expiry ) == 1 ) {
				$expiry = array_fill( 0, count( $params['protections'] ), $expiry[0] );
			} else {
				$this->dieUsageMsg( array( 'toofewexpiries', count( $expiry ), count( $params['protections'] ) ) );
			}
		}

		$restrictionTypes = $titleObj->getRestrictionTypes();

		$protections = array();
		$expiryarray = array();
		$resultProtections = array();
		foreach ( $params['protections'] as $i => $prot ) {
			$p = explode( '=', $prot );
			$protections[$p[0]] = ( $p[1] == 'all' ? '' : $p[1] );

			if ( $titleObj->exists() && $p[0] == 'create' ) {
				$this->dieUsageMsg( array( 'create-titleexists' ) );
			}
			if ( !$titleObj->exists() && $p[0] != 'create' ) {
				$this->dieUsageMsg( array( 'missingtitle-createonly' ) );
			}

			if ( !in_array( $p[0], $restrictionTypes ) && $p[0] != 'create' ) {
				$this->dieUsageMsg( array( 'protect-invalidaction', $p[0] ) );
			}
			if ( !in_array( $p[1], $wgRestrictionLevels ) && $p[1] != 'all' ) {
				$this->dieUsageMsg( array( 'protect-invalidlevel', $p[1] ) );
			}

			if ( in_array( $expiry[$i], array( 'infinite', 'indefinite', 'never' ) ) ) {
				$expiryarray[$p[0]] = Block::infinity();
			} else {
				$exp = strtotime( $expiry[$i] );
				if ( $exp < 0 || !$exp ) {
					$this->dieUsageMsg( array( 'invalidexpiry', $expiry[$i] ) );
				}

				$exp = wfTimestamp( TS_MW, $exp );
				if ( $exp < wfTimestampNow() ) {
					$this->dieUsageMsg( array( 'pastexpiry', $expiry[$i] ) );
				}
				$expiryarray[$p[0]] = $exp;
			}
			$resultProtections[] = array( $p[0] => $protections[$p[0]],
					'expiry' => ( $expiryarray[$p[0]] == Block::infinity() ?
								'infinite' :
								wfTimestamp( TS_ISO_8601, $expiryarray[$p[0]] ) ) );
		}

		$cascade = $params['cascade'];
		$articleObj = new Article( $titleObj );

		$watch = $params['watch'] ? 'watch' : $params['watchlist'];
		$this->setWatch( $watch, $titleObj );

		if ( $titleObj->exists() ) {
			$ok = $articleObj->updateRestrictions( $protections, $params['reason'], $cascade, $expiryarray );
		} else {
			$ok = $titleObj->updateTitleProtection( $protections['create'], $params['reason'], $expiryarray['create'] );
		}
		if ( !$ok ) {
			// This is very weird. Maybe the article was deleted or the user was blocked/desysopped in the meantime?
			// Just throw an unknown error in this case, as it's very likely to be a race condition
			$this->dieUsageMsg( array() );
		}
		$res = array(
			'title' => $titleObj->getPrefixedText(),
			'reason' => $params['reason']
		);
		if ( $cascade ) {
			$res['cascade'] = '';
		}
		$res['protections'] = $resultProtections;
		$this->getResult()->setIndexedTagName( $res['protections'], 'protection' );
		$this->getResult()->addValue( null, $this->getModuleName(), $res );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return array(
			'title' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'token' => null,
			'protections' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_REQUIRED => true,
			),
			'expiry' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_ALLOW_DUPLICATES => true,
				ApiBase::PARAM_DFLT => 'infinite',
			),
			'reason' => '',
			'cascade' => false,
			'watch' => array(
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
		);
	}

	public function getParamDescription() {
		return array(
			'title' => 'Title of the page you want to (un)protect',
			'token' => 'A protect token previously retrieved through prop=info',
			'protections' => 'Pipe-separated list of protection levels, formatted action=group (e.g. edit=sysop)',
			'expiry' => array( 'Expiry timestamps. If only one timestamp is set, it\'ll be used for all protections.',
					'Use \'infinite\', \'indefinite\' or \'never\', for a neverexpiring protection.' ),
			'reason' => 'Reason for (un)protecting (optional)',
			'cascade' => array( 'Enable cascading protection (i.e. protect pages included in this page)',
					'Ignored if not all protection levels are \'sysop\' or \'protect\'' ),
			'watch' => 'If set, add the page being (un)protected to your watchlist',
			'watchlist' => 'Unconditionally add or remove the page from your watchlist, use preferences or do not change watch',
		);
	}

	public function getDescription() {
		return 'Change the protection level of a page';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'invalidtitle', 'title' ),
			array( 'toofewexpiries', 'noofexpiries', 'noofprotections' ),
			array( 'create-titleexists' ),
			array( 'missingtitle-createonly' ),
			array( 'protect-invalidaction', 'action' ),
			array( 'protect-invalidlevel', 'level' ),
			array( 'invalidexpiry', 'expiry' ),
			array( 'pastexpiry', 'expiry' ),
		) );
	}

	public function needsToken() {
		return true;
	}

	public function getTokenSalt() {
		return '';
	}

	protected function getExamples() {
		return array(
			'api.php?action=protect&title=Main%20Page&token=123ABC&protections=edit=sysop|move=sysop&cascade=&expiry=20070901163000|never',
			'api.php?action=protect&title=Main%20Page&token=123ABC&protections=edit=all|move=all&reason=Lifting%20restrictions'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
