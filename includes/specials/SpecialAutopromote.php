<?php
/**
 * Implements Special:Autopromote
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
 * @ingroup SpecialPage
 * @since 1.28
 */

/**
 * Special page for check the autopromote state
 *
 * @ingroup SpecialPage
 */
class SpecialAutopromote extends SpecialPage {

	public function __construct() {
		parent::__construct( 'Autopromote' );
	}

	public function execute( $par ) {
		global $wgAutopromote, $wgAutopromoteOnce;

		$this->setHeaders();

		$this->requireLogin( 'autopromote-needlogin' );

		$context = $this->getContext();
		$user = $context->getUser();
		$out = $context->getOutput();

		$this->getSkin()->setRelevantUser( $user );

		// explain text
		$out->addWikiMsg( 'autopromote-text' );

		// output automatically autopromote
		$countAutopromote = 0;

		foreach ( $wgAutopromote as $group => $cond ) {
			// header
			if ( $countAutopromote === 0 ) {
				$out->wrapWikiMsg( '<h2>$1</h2>', 'autopromote-header' );
				$out->addWikiMsg( 'autopromote-section-text' );
			}
			$countAutopromote++;

			// header group
			$out->addHtml( '<h3 id="' . Sanitizer::escapeId( $group ) . '">' .
				User::makeGroupLinkHTML( $group, User::getGroupMember( $group, $user->getName() ) ) .
				'</h3>'
			);

			$condObj = AutopromoteConditionBase::newFromArray( $cond );
			$out->addHtml( $condObj->getDescription( $context, $user )->parse() );
		}

		// output once autopromote
		$formerGroups = $user->getFormerGroups();
		$currentGroups = $user->getGroups();
		$countAutopromoteOnce = 0;

		foreach ( $wgAutopromoteOnce as $event => $autopromote ) {
			foreach ( $autopromote as $group => $cond ) {
				// header
				if ( $countAutopromoteOnce === 0 ) {
					$out->wrapWikiMsg( '<h2>$1</h2>', 'autopromote-once-header' );
					$out->addWikiMsg( 'autopromote-once-section-text' );
				}
				$countAutopromoteOnce++;

				// header for group
				$out->addHtml( '<h3 id="' . Sanitizer::escapeId( $group ) . '">' .
					User::makeGroupLinkHTML( $group, User::getGroupMember( $group, $user->getName() ) ) .
					'</h3>'
				);

				// If the user is already in the group, the criteria are meanless
				if ( in_array( $group, $currentGroups ) ) {
					$out->addWikiMsg( 'autopromote-once-already' );
					continue;
				}

				// If the user was already promoted, the criteria are meanless
				if ( in_array( $group, $formerGroups ) ) {
					$out->addWikiMsg( 'autopromote-once-former' );
					continue;
				}

				$condObj = AutopromoteConditionBase::newFromArray( $cond );
				$out->addHtml( $condObj->getDescription( $context, $user )->parse() );
			}
		}

		// output info when nothing is configurated
		if ( $countAutopromote + $countAutopromoteOnce === 0 ) {
			$out->addWikiMsg( 'autopromote-none', $user->getName() );
		}
	}

	protected function getGroupName() {
		return 'users';
	}
}
