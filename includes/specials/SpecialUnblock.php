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
		$this->block = Block::newFromTarget( $this->target );
		if ( $this->target instanceof User ) {
			# Set the 'relevant user' in the skin, so it displays links like Contributions,
			# User logs, UserRights, etc.
			$this->getSkin()->setRelevantUser( $this->target );
		}

		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'unblockip' ) );
		$out->addModules( [ 'mediawiki.userSuggest' ] );

		$form = HTMLForm::factory( 'ooui', $this->getFields(), $this->getContext() );
		$form->setWrapperLegendMsg( 'unblockip' );
		$form->setSubmitCallback( [ __CLASS__, 'processUIUnblock' ] );
		$form->setSubmitTextMsg( 'ipusubmit' );
		$form->addPreText( $this->msg( 'unblockiptext' )->parseAsBlock() );

		if ( $form->show() ) {
			switch ( $this->type ) {
				case Block::TYPE_IP:
					$out->addWikiMsg( 'unblocked-ip', wfEscapeWikiText( $this->target ) );
					break;
				case Block::TYPE_USER:
					$out->addWikiMsg( 'unblocked', wfEscapeWikiText( $this->target ) );
					break;
				case Block::TYPE_RANGE:
					$out->addWikiMsg( 'unblocked-range', wfEscapeWikiText( $this->target ) );
					break;
				case Block::TYPE_ID:
				case Block::TYPE_AUTO:
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

		if ( $this->block instanceof Block ) {
			list( $target, $type ) = $this->block->getTargetAndType();

			# Autoblocks are logged as "autoblock #123 because the IP was recently used by
			# User:Foo, and we've just got any block, auto or not, that applies to a target
			# the user has specified.  Someone could be fishing to connect IPs to autoblocks,
			# so don't show any distinction between unblocked IPs and autoblocked IPs
			if ( $type == Block::TYPE_AUTO && $this->type == Block::TYPE_IP ) {
				$fields['Target']['default'] = $this->target;
				unset( $fields['Name'] );
			} else {
				$fields['Target']['default'] = $target;
				$fields['Target']['type'] = 'hidden';
				switch ( $type ) {
					case Block::TYPE_IP:
						$fields['Name']['default'] = $this->getLinkRenderer()->makeKnownLink(
							SpecialPage::getTitleFor( 'Contributions', $target->getName() ),
							$target->getName()
						);
						$fields['Name']['raw'] = true;
						break;
					case Block::TYPE_USER:
						$fields['Name']['default'] = $this->getLinkRenderer()->makeLink(
							$target->getUserPage(),
							$target->getName()
						);
						$fields['Name']['raw'] = true;
						break;

					case Block::TYPE_RANGE:
						$fields['Name']['default'] = $target;
						break;

					case Block::TYPE_AUTO:
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
	 * @return array|bool Array(message key, parameters)
	 */
	public static function processUIUnblock( array $data, HTMLForm $form ) {
		return self::processUnblock( $data, $form->getContext() );
	}

	/**
	 * Process the form
	 *
	 * Change tags can be provided via $data['Tags'], but the calling function
	 * must check if the tags can be added by the user prior to this function.
	 *
	 * @param array $data
	 * @param IContextSource $context
	 * @throws ErrorPageError
	 * @return array|bool Array( Array( message key, parameters ) ) on failure, True on success
	 */
	public static function processUnblock( array $data, IContextSource $context ) {
		$performer = $context->getUser();
		$target = $data['Target'];
		$block = Block::newFromTarget( $data['Target'] );

		if ( !$block instanceof Block ) {
			return [ [ 'ipb_cant_unblock', $target ] ];
		}

		# T17810: blocked admins should have limited access here.  This
		# won't allow sysops to remove autoblocks on themselves, but they
		# should have ipblock-exempt anyway
		$status = SpecialBlock::checkUnblockSelf( $target, $performer );
		if ( $status !== true ) {
			throw new ErrorPageError( 'badaccess', $status );
		}

		# If the specified IP is a single address, and the block is a range block, don't
		# unblock the whole range.
		list( $target, $type ) = SpecialBlock::getTargetAndType( $target );
		if ( $block->getType() == Block::TYPE_RANGE && $type == Block::TYPE_IP ) {
			$range = $block->getTarget();

			return [ [ 'ipb_blocked_as_range', $target, $range ] ];
		}

		# If the name was hidden and the blocking user cannot hide
		# names, then don't allow any block removals...
		if ( !$performer->isAllowed( 'hideuser' ) && $block->getHideName() ) {
			return [ 'unblock-hideuser' ];
		}

		$reason = [ 'hookaborted' ];
		if ( !Hooks::run( 'UnblockUser', [ &$block, &$performer, &$reason ] ) ) {
			return $reason;
		}

		# Delete block
		if ( !$block->delete() ) {
			return [ [ 'ipb_cant_unblock', htmlspecialchars( $block->getTarget() ) ] ];
		}

		Hooks::run( 'UnblockUserComplete', [ $block, $performer ] );

		# Unset _deleted fields as needed
		if ( $block->getHideName() ) {
			# Something is deeply FUBAR if this is not a User object, but who knows?
			$id = $block->getTarget() instanceof User
				? $block->getTarget()->getId()
				: User::idFromName( $block->getTarget() );

			RevisionDeleteUser::unsuppressUserName( $block->getTarget(), $id );
		}

		# Redact the name (IP address) for autoblocks
		if ( $block->getType() == Block::TYPE_AUTO ) {
			$page = Title::makeTitle( NS_USER, '#' . $block->getId() );
		} else {
			$page = $block->getTarget() instanceof User
				? $block->getTarget()->getUserPage()
				: Title::makeTitle( NS_USER, $block->getTarget() );
		}

		# Make log entry
		$logEntry = new ManualLogEntry( 'block', 'unblock' );
		$logEntry->setTarget( $page );
		$logEntry->setComment( $data['Reason'] );
		$logEntry->setPerformer( $performer );
		if ( isset( $data['Tags'] ) ) {
			$logEntry->setTags( $data['Tags'] );
		}
		$logEntry->setRelations( [ 'ipb_id' => $block->getId() ] );
		$logId = $logEntry->insert();
		$logEntry->publish( $logId );

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
