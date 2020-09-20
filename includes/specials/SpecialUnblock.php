<?php
/**
 * Implements Special:Unblock
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
 */

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\MediaWikiServices;

/**
 * A special page for unblocking users
 *
 * @ingroup SpecialPage
 */
class SpecialUnblock extends SpecialPage {

	protected $target;
	protected $type;
	protected $block;

	public function __construct() {
		parent::__construct( 'Unblock', 'block' );
	}

	public function doesWrites() {
		return true;
	}

	public function execute( $par ) {
		$this->checkPermissions();
		$this->checkReadOnly();

		list( $this->target, $this->type ) = SpecialBlock::getTargetAndType( $par, $this->getRequest() );
		$this->block = DatabaseBlock::newFromTarget( $this->target );
		if ( $this->target instanceof User ) {
			# Set the 'relevant user' in the skin, so it displays links like Contributions,
			# User logs, UserRights, etc.
			$this->getSkin()->setRelevantUser( $this->target );
		}

		$this->setHeaders();
		$this->outputHeader();
		$this->addHelpLink( 'Help:Blocking users' );

		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'unblockip' ) );
		$out->addModules( [ 'mediawiki.userSuggest' ] );

		$form = HTMLForm::factory( 'ooui', $this->getFields(), $this->getContext() )
			->setWrapperLegendMsg( 'unblockip' )
			->setSubmitCallback( [ __CLASS__, 'processUIUnblock' ] )
			->setSubmitTextMsg( 'ipusubmit' )
			->addPreText( $this->msg( 'unblockiptext' )->parseAsBlock() );

		if ( $form->show() ) {
			switch ( $this->type ) {
				case DatabaseBlock::TYPE_IP:
					$out->addWikiMsg( 'unblocked-ip', wfEscapeWikiText( $this->target ) );
					break;
				case DatabaseBlock::TYPE_USER:
					$out->addWikiMsg( 'unblocked', wfEscapeWikiText( $this->target ) );
					break;
				case DatabaseBlock::TYPE_RANGE:
					$out->addWikiMsg( 'unblocked-range', wfEscapeWikiText( $this->target ) );
					break;
				case DatabaseBlock::TYPE_ID:
				case DatabaseBlock::TYPE_AUTO:
					$out->addWikiMsg( 'unblocked-id', wfEscapeWikiText( $this->target ) );
					break;
			}
		}
	}

	protected function getFields() {
		$fields = [
			'Target' => [
				'type' => 'text',
				'label-message' => 'ipaddressorusername',
				'autofocus' => true,
				'size' => '45',
				'required' => true,
				'cssclass' => 'mw-autocomplete-user', // used by mediawiki.userSuggest
			],
			'Name' => [
				'type' => 'info',
				'label-message' => 'ipaddressorusername',
			],
			'Reason' => [
				'type' => 'text',
				'label-message' => 'ipbreason',
			]
		];

		if ( $this->block instanceof DatabaseBlock ) {
			list( $target, $type ) = $this->block->getTargetAndType();

			# Autoblocks are logged as "autoblock #123 because the IP was recently used by
			# User:Foo, and we've just got any block, auto or not, that applies to a target
			# the user has specified.  Someone could be fishing to connect IPs to autoblocks,
			# so don't show any distinction between unblocked IPs and autoblocked IPs
			if ( $type == DatabaseBlock::TYPE_AUTO && $this->type == DatabaseBlock::TYPE_IP ) {
				$fields['Target']['default'] = $this->target;
				unset( $fields['Name'] );
			} else {
				$fields['Target']['default'] = $target;
				$fields['Target']['type'] = 'hidden';
				switch ( $type ) {
					case DatabaseBlock::TYPE_IP:
						$fields['Name']['default'] = $this->getLinkRenderer()->makeKnownLink(
							SpecialPage::getTitleFor( 'Contributions', $target->getName() ),
							$target->getName()
						);
						$fields['Name']['raw'] = true;
						break;
					case DatabaseBlock::TYPE_USER:
						$fields['Name']['default'] = $this->getLinkRenderer()->makeLink(
							$target->getUserPage(),
							$target->getName()
						);
						$fields['Name']['raw'] = true;
						break;

					case DatabaseBlock::TYPE_RANGE:
						$fields['Name']['default'] = $target;
						break;

					case DatabaseBlock::TYPE_AUTO:
						$fields['Name']['default'] = $this->block->getRedactedName();
						$fields['Name']['raw'] = true;
						# Don't expose the real target of the autoblock
						$fields['Target']['default'] = "#{$this->target}";
						break;
				}
				// target is hidden, so the reason is the first element
				$fields['Target']['autofocus'] = false;
				$fields['Reason']['autofocus'] = true;
			}
		} else {
			$fields['Target']['default'] = $this->target;
			unset( $fields['Name'] );
		}

		return $fields;
	}

	/**
	 * Submit callback for an HTMLForm object
	 * @param array $data
	 * @param HTMLForm $form
	 * @internal Only to be used as the submit callback in execute().
	 * @return Status
	 */
	public static function processUIUnblock( array $data, HTMLForm $form ) {
		if ( !isset( $data['Tags'] ) ) {
			$data['Tags'] = [];
		}

		return MediaWikiServices::getInstance()->getUnblockUserFactory()->newUnblockUser(
			$data['Target'],
			$form->getContext()->getUser(),
			$data['Reason'],
			$data['Tags']
		)->unblock();
	}

	/**
	 * Process the form
	 *
	 * @deprecated since 1.36, use UnblockUser instead
	 * @param array $data
	 * @param IContextSource $context
	 * @return array|bool [ [ message key, parameters ] ] on failure, True on success
	 */
	public static function processUnblock( array $data, IContextSource $context ) {
		wfDeprecated( __METHOD__, '1.36' );

		if ( !isset( $data['Tags'] ) ) {
			$data['Tags'] = [];
		}

		$unblockUser = MediaWikiServices::getInstance()->getUnblockUserFactory()->newUnblockUser(
			$data['Target'],
			$context->getUser(),
			$data['Reason'],
			$data['Tags']
		);

		$status = $unblockUser->unblock();
		if ( !$status->isOK() ) {
			return $status->getErrorsArray();
		}

		return true;
	}

	/**
	 * Return an array of subpages beginning with $search that this special page will accept.
	 *
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return (usually 10)
	 * @param int $offset Number of results to skip (usually 0)
	 * @return string[] Matching subpages
	 */
	public function prefixSearchSubpages( $search, $limit, $offset ) {
		$user = User::newFromName( $search );
		if ( !$user ) {
			// No prefix suggestion for invalid user
			return [];
		}
		// Autocomplete subpage as user list - public to allow caching
		return UserNamePrefixSearch::search( 'public', $search, $limit, $offset );
	}

	protected function getGroupName() {
		return 'users';
	}
}
