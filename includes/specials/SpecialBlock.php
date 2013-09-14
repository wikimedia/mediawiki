<?php
/**
 * Implements Special:Block
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
 * A special page that allows users with 'block' right to block users from
 * editing pages and other actions
 *
 * @ingroup SpecialPage
 */
class SpecialBlock extends FormSpecialPage {
	/** The maximum number of edits a user can have and still be hidden
	 * TODO: config setting? */
	const HIDEUSER_CONTRIBLIMIT = 1000;

	/** @var User user to be blocked, as passed either by parameter (url?wpTarget=Foo)
	 * or as subpage (Special:Block/Foo) */
	protected $target;

	/// @var Block::TYPE_ constant
	protected $type;

	/// @var  User|String the previous block target
	protected $previousTarget;

	/// @var Bool whether the previous submission of the form asked for HideUser
	protected $requestedHideUser;

	/// @var Bool
	protected $alreadyBlocked;

	/// @var Array
	protected $preErrors = array();

	public function __construct() {
		parent::__construct( 'Block', 'block' );
	}

	/**
	 * Checks that the user can unblock themselves if they are trying to do so
	 *
	 * @param User $user
	 * @throws ErrorPageError
	 */
	protected function checkExecutePermissions( User $user ) {
		parent::checkExecutePermissions( $user );

		# bug 15810: blocked admins should have limited access here
		$status = self::checkUnblockSelf( $this->target, $user );
		if ( $status !== true ) {
			throw new ErrorPageError( 'badaccess', $status );
		}
	}

	/**
	 * Handle some magic here
	 *
	 * @param $par String
	 */
	protected function setParameter( $par ) {
		# Extract variables from the request.  Try not to get into a situation where we
		# need to extract *every* variable from the form just for processing here, but
		# there are legitimate uses for some variables
		$request = $this->getRequest();
		list( $this->target, $this->type ) = self::getTargetAndType( $par, $request );
		if ( $this->target instanceof User ) {
			# Set the 'relevant user' in the skin, so it displays links like Contributions,
			# User logs, UserRights, etc.
			$this->getSkin()->setRelevantUser( $this->target );
		}

		list( $this->previousTarget, /*...*/ ) = Block::parseTarget( $request->getVal( 'wpPreviousTarget' ) );
		$this->requestedHideUser = $request->getBool( 'wpHideUser' );
	}

	/**
	 * Customizes the HTMLForm a bit
	 *
	 * @param $form HTMLForm
	 */
	protected function alterForm( HTMLForm $form ) {
		$form->setWrapperLegendMsg( 'blockip-legend' );
		$form->setHeaderText( '' );
		$form->setSubmitCallback( array( __CLASS__, 'processUIForm' ) );

		$msg = $this->alreadyBlocked ? 'ipb-change-block' : 'ipbsubmit';
		$form->setSubmitTextMsg( $msg );

		# Don't need to do anything if the form has been posted
		if ( !$this->getRequest()->wasPosted() && $this->preErrors ) {
			$s = HTMLForm::formatErrors( $this->preErrors );
			if ( $s ) {
				$form->addHeaderText( Html::rawElement(
					'div',
					array( 'class' => 'error' ),
					$s
				) );
			}
		}
	}

	/**
	 * Get the HTMLForm descriptor array for the block form
	 * @return Array
	 */
	protected function getFormFields() {
		global $wgBlockAllowsUTEdit;

		$user = $this->getUser();

		$suggestedDurations = self::getSuggestedDurations();

		$a = array(
			'Target' => array(
				'type' => 'text',
				'label-message' => 'ipadressorusername',
				'tabindex' => '1',
				'id' => 'mw-bi-target',
				'size' => '45',
				'autofocus' => true,
				'required' => true,
				'validation-callback' => array( __CLASS__, 'validateTargetField' ),
			),
			'Expiry' => array(
				'type' => !count( $suggestedDurations ) ? 'text' : 'selectorother',
				'label-message' => 'ipbexpiry',
				'required' => true,
				'tabindex' => '2',
				'options' => $suggestedDurations,
				'other' => $this->msg( 'ipbother' )->text(),
				'default' => $this->msg( 'ipb-default-expiry' )->inContentLanguage()->text(),
			),
			'Reason' => array(
				'type' => 'selectandother',
				'label-message' => 'ipbreason',
				'options-message' => 'ipbreason-dropdown',
			),
			'CreateAccount' => array(
				'type' => 'check',
				'label-message' => 'ipbcreateaccount',
				'default' => true,
			),
		);

		if ( self::canBlockEmail( $user ) ) {
			$a['DisableEmail'] = array(
				'type' => 'check',
				'label-message' => 'ipbemailban',
			);
		}

		if ( $wgBlockAllowsUTEdit ) {
			$a['DisableUTEdit'] = array(
				'type' => 'check',
				'label-message' => 'ipb-disableusertalk',
				'default' => false,
			);
		}

		$a['AutoBlock'] = array(
			'type' => 'check',
			'label-message' => 'ipbenableautoblock',
			'default' => true,
		);

		# Allow some users to hide name from block log, blocklist and listusers
		if ( $user->isAllowed( 'hideuser' ) ) {
			$a['HideUser'] = array(
				'type' => 'check',
				'label-message' => 'ipbhidename',
				'cssclass' => 'mw-block-hideuser',
			);
		}

		# Watchlist their user page? (Only if user is logged in)
		if ( $user->isLoggedIn() ) {
			$a['Watch'] = array(
				'type' => 'check',
				'label-message' => 'ipbwatchuser',
			);
		}

		$a['HardBlock'] = array(
			'type' => 'check',
			'label-message' => 'ipb-hardblock',
			'default' => false,
		);

		# This is basically a copy of the Target field, but the user can't change it, so we
		# can see if the warnings we maybe showed to the user before still apply
		$a['PreviousTarget'] = array(
			'type' => 'hidden',
			'default' => false,
		);

		# We'll turn this into a checkbox if we need to
		$a['Confirm'] = array(
			'type' => 'hidden',
			'default' => '',
			'label-message' => 'ipb-confirm',
		);

		$this->maybeAlterFormDefaults( $a );

		return $a;
	}

	/**
	 * If the user has already been blocked with similar settings, load that block
	 * and change the defaults for the form fields to match the existing settings.
	 * @param array $fields HTMLForm descriptor array
	 * @return Bool whether fields were altered (that is, whether the target is
	 *     already blocked)
	 */
	protected function maybeAlterFormDefaults( &$fields ) {
		# This will be overwritten by request data
		$fields['Target']['default'] = (string)$this->target;

		# This won't be
		$fields['PreviousTarget']['default'] = (string)$this->target;

		$block = Block::newFromTarget( $this->target );

		if ( $block instanceof Block && !$block->mAuto # The block exists and isn't an autoblock
			&& ( $this->type != Block::TYPE_RANGE # The block isn't a rangeblock
				|| $block->getTarget() == $this->target ) # or if it is, the range is what we're about to block
		) {
			$fields['HardBlock']['default'] = $block->isHardblock();
			$fields['CreateAccount']['default'] = $block->prevents( 'createaccount' );
			$fields['AutoBlock']['default'] = $block->isAutoblocking();

			if ( isset( $fields['DisableEmail'] ) ) {
				$fields['DisableEmail']['default'] = $block->prevents( 'sendemail' );
			}

			if ( isset( $fields['HideUser'] ) ) {
				$fields['HideUser']['default'] = $block->mHideName;
			}

			if ( isset( $fields['DisableUTEdit'] ) ) {
				$fields['DisableUTEdit']['default'] = $block->prevents( 'editownusertalk' );
			}

			// If the username was hidden (ipb_deleted == 1), don't show the reason
			// unless this user also has rights to hideuser: Bug 35839
			if ( !$block->mHideName || $this->getUser()->isAllowed( 'hideuser' ) ) {
				$fields['Reason']['default'] = $block->mReason;
			} else {
				$fields['Reason']['default'] = '';
			}

			if ( $this->getRequest()->wasPosted() ) {
				# Ok, so we got a POST submission asking us to reblock a user.  So show the
				# confirm checkbox; the user will only see it if they haven't previously
				$fields['Confirm']['type'] = 'check';
			} else {
				# We got a target, but it wasn't a POST request, so the user must have gone
				# to a link like [[Special:Block/User]].  We don't need to show the checkbox
				# as long as they go ahead and block *that* user
				$fields['Confirm']['default'] = 1;
			}

			if ( $block->mExpiry == 'infinity' ) {
				$fields['Expiry']['default'] = 'infinite';
			} else {
				$fields['Expiry']['default'] = wfTimestamp( TS_RFC2822, $block->mExpiry );
			}

			$this->alreadyBlocked = true;
			$this->preErrors[] = array( 'ipb-needreblock', wfEscapeWikiText( (string)$block->getTarget() ) );
		}

		# We always need confirmation to do HideUser
		if ( $this->requestedHideUser ) {
			$fields['Confirm']['type'] = 'check';
			unset( $fields['Confirm']['default'] );
			$this->preErrors[] = 'ipb-confirmhideuser';
		}

		# Or if the user is trying to block themselves
		if ( (string)$this->target === $this->getUser()->getName() ) {
			$fields['Confirm']['type'] = 'check';
			unset( $fields['Confirm']['default'] );
			$this->preErrors[] = 'ipb-blockingself';
		}
	}

	/**
	 * Add header elements like block log entries, etc.
	 * @return String
	 */
	protected function preText() {
		$this->getOutput()->addModules( 'mediawiki.special.block' );

		$text = $this->msg( 'blockiptext' )->parse();

		$otherBlockMessages = array();
		if ( $this->target !== null ) {
			# Get other blocks, i.e. from GlobalBlocking or TorBlock extension
			wfRunHooks( 'OtherBlockLogLink', array( &$otherBlockMessages, $this->target ) );

			if ( count( $otherBlockMessages ) ) {
				$s = Html::rawElement(
					'h2',
					array(),
					$this->msg( 'ipb-otherblocks-header', count( $otherBlockMessages ) )->parse()
				) . "\n";

				$list = '';

				foreach ( $otherBlockMessages as $link ) {
					$list .= Html::rawElement( 'li', array(), $link ) . "\n";
				}

				$s .= Html::rawElement(
					'ul',
					array( 'class' => 'mw-blockip-alreadyblocked' ),
					$list
				) . "\n";

				$text .= $s;
			}
		}

		return $text;
	}

	/**
	 * Add footer elements to the form
	 * @return string
	 */
	protected function postText() {
		$links = array();

		# Link to the user's contributions, if applicable
		if ( $this->target instanceof User ) {
			$contribsPage = SpecialPage::getTitleFor( 'Contributions', $this->target->getName() );
			$links[] = Linker::link(
				$contribsPage,
				$this->msg( 'ipb-blocklist-contribs', $this->target->getName() )->escaped()
			);
		}

		# Link to unblock the specified user, or to a blank unblock form
		if ( $this->target instanceof User ) {
			$message = $this->msg( 'ipb-unblock-addr', wfEscapeWikiText( $this->target->getName() ) )->parse();
			$list = SpecialPage::getTitleFor( 'Unblock', $this->target->getName() );
		} else {
			$message = $this->msg( 'ipb-unblock' )->parse();
			$list = SpecialPage::getTitleFor( 'Unblock' );
		}
		$links[] = Linker::linkKnown( $list, $message, array() );

		# Link to the block list
		$links[] = Linker::linkKnown(
			SpecialPage::getTitleFor( 'BlockList' ),
			$this->msg( 'ipb-blocklist' )->escaped()
		);

		$user = $this->getUser();

		# Link to edit the block dropdown reasons, if applicable
		if ( $user->isAllowed( 'editinterface' ) ) {
			$links[] = Linker::link(
				Title::makeTitle( NS_MEDIAWIKI, 'Ipbreason-dropdown' ),
				$this->msg( 'ipb-edit-dropdown' )->escaped(),
				array(),
				array( 'action' => 'edit' )
			);
		}

		$text = Html::rawElement(
			'p',
			array( 'class' => 'mw-ipb-conveniencelinks' ),
			$this->getLanguage()->pipeList( $links )
		);

		$userTitle = self::getTargetUserTitle( $this->target );
		if ( $userTitle ) {
			# Get relevant extracts from the block and suppression logs, if possible
			$out = '';

			LogEventsList::showLogExtract(
				$out,
				'block',
				$userTitle,
				'',
				array(
					'lim' => 10,
					'msgKey' => array( 'blocklog-showlog', $userTitle->getText() ),
					'showIfEmpty' => false
				)
			);
			$text .= $out;

			# Add suppression block entries if allowed
			if ( $user->isAllowed( 'suppressionlog' ) ) {
				LogEventsList::showLogExtract(
					$out,
					'suppress',
					$userTitle,
					'',
					array(
						'lim' => 10,
						'conds' => array( 'log_action' => array( 'block', 'reblock', 'unblock' ) ),
						'msgKey' => array( 'blocklog-showsuppresslog', $userTitle->getText() ),
						'showIfEmpty' => false
					)
				);

				$text .= $out;
			}
		}

		return $text;
	}

	/**
	 * Get a user page target for things like logs.
	 * This handles account and IP range targets.
	 * @param $target User|string
	 * @return Title|null
	 */
	protected static function getTargetUserTitle( $target ) {
		if ( $target instanceof User ) {
			return $target->getUserPage();
		} elseif ( IP::isIPAddress( $target ) ) {
			return Title::makeTitleSafe( NS_USER, $target );
		}

		return null;
	}

	/**
	 * Determine the target of the block, and the type of target
	 * TODO: should be in Block.php?
	 * @param string $par subpage parameter passed to setup, or data value from
	 *     the HTMLForm
	 * @param $request WebRequest optionally try and get data from a request too
	 * @return array( User|string|null, Block::TYPE_ constant|null )
	 */
	public static function getTargetAndType( $par, WebRequest $request = null ) {
		$i = 0;
		$target = null;

		while ( true ) {
			switch ( $i++ ) {
				case 0:
					# The HTMLForm will check wpTarget first and only if it doesn't get
					# a value use the default, which will be generated from the options
					# below; so this has to have a higher precedence here than $par, or
					# we could end up with different values in $this->target and the HTMLForm!
					if ( $request instanceof WebRequest ) {
						$target = $request->getText( 'wpTarget', null );
					}
					break;
				case 1:
					$target = $par;
					break;
				case 2:
					if ( $request instanceof WebRequest ) {
						$target = $request->getText( 'ip', null );
					}
					break;
				case 3:
					# B/C @since 1.18
					if ( $request instanceof WebRequest ) {
						$target = $request->getText( 'wpBlockAddress', null );
					}
					break;
				case 4:
					break 2;
			}

			list( $target, $type ) = Block::parseTarget( $target );

			if ( $type !== null ) {
				return array( $target, $type );
			}
		}

		return array( null, null );
	}

	/**
	 * HTMLForm field validation-callback for Target field.
	 * @since 1.18
	 * @param $value String
	 * @param $alldata Array
	 * @param $form HTMLForm
	 * @return Message
	 */
	public static function validateTargetField( $value, $alldata, $form ) {
		$status = self::validateTarget( $value, $form->getUser() );
		if ( !$status->isOK() ) {
			$errors = $status->getErrorsArray();

			return call_user_func_array( array( $form, 'msg' ), $errors[0] );
		} else {
			return true;
		}
	}

	/**
	 * Validate a block target.
	 *
	 * @since 1.21
	 * @param string $value Block target to check
	 * @param User $user Performer of the block
	 * @return Status
	 */
	public static function validateTarget( $value, User $user ) {
		global $wgBlockCIDRLimit;

		/** @var User $target */
		list( $target, $type ) = self::getTargetAndType( $value );
		$status = Status::newGood( $target );

		if ( $type == Block::TYPE_USER ) {
			if ( $target->isAnon() ) {
				$status->fatal(
					'nosuchusershort',
					wfEscapeWikiText( $target->getName() )
				);
			}

			$unblockStatus = self::checkUnblockSelf( $target, $user );
			if ( $unblockStatus !== true ) {
				$status->fatal( 'badaccess', $unblockStatus );
			}
		} elseif ( $type == Block::TYPE_RANGE ) {
			list( $ip, $range ) = explode( '/', $target, 2 );

			if (
				( IP::isIPv4( $ip ) && $wgBlockCIDRLimit['IPv4'] == 32 ) ||
				( IP::isIPv6( $ip ) && $wgBlockCIDRLimit['IPv6'] == 128 )
			) {
				// Range block effectively disabled
				$status->fatal( 'range_block_disabled' );
			}

			if (
				( IP::isIPv4( $ip ) && $range > 32 ) ||
				( IP::isIPv6( $ip ) && $range > 128 )
			) {
				// Dodgy range
				$status->fatal( 'ip_range_invalid' );
			}

			if ( IP::isIPv4( $ip ) && $range < $wgBlockCIDRLimit['IPv4'] ) {
				$status->fatal( 'ip_range_toolarge', $wgBlockCIDRLimit['IPv4'] );
			}

			if ( IP::isIPv6( $ip ) && $range < $wgBlockCIDRLimit['IPv6'] ) {
				$status->fatal( 'ip_range_toolarge', $wgBlockCIDRLimit['IPv6'] );
			}
		} elseif ( $type == Block::TYPE_IP ) {
			# All is well
		} else {
			$status->fatal( 'badipaddress' );
		}

		return $status;
	}

	/**
	 * Submit callback for an HTMLForm object, will simply pass
	 * @param $data array
	 * @param $form HTMLForm
	 * @return Bool|String
	 */
	public static function processUIForm( array $data, HTMLForm $form ) {
		return self::processForm( $data, $form->getContext() );
	}

	/**
	 * Given the form data, actually implement a block
	 * @param  $data Array
	 * @param  $context IContextSource
	 * @return Bool|String
	 */
	public static function processForm( array $data, IContextSource $context ) {
		global $wgBlockAllowsUTEdit;

		$performer = $context->getUser();

		// Handled by field validator callback
		// self::validateTargetField( $data['Target'] );

		# This might have been a hidden field or a checkbox, so interesting data
		# can come from it
		$data['Confirm'] = !in_array( $data['Confirm'], array( '', '0', null, false ), true );

		/** @var User $target */
		list( $target, $type ) = self::getTargetAndType( $data['Target'] );
		if ( $type == Block::TYPE_USER ) {
			$user = $target;
			$target = $user->getName();
			$userId = $user->getId();

			# Give admins a heads-up before they go and block themselves.  Much messier
			# to do this for IPs, but it's pretty unlikely they'd ever get the 'block'
			# permission anyway, although the code does allow for it.
			# Note: Important to use $target instead of $data['Target']
			# since both $data['PreviousTarget'] and $target are normalized
			# but $data['target'] gets overriden by (non-normalized) request variable
			# from previous request.
			if ( $target === $performer->getName() &&
				( $data['PreviousTarget'] !== $target || !$data['Confirm'] )
			) {
				return array( 'ipb-blockingself' );
			}
		} elseif ( $type == Block::TYPE_RANGE ) {
			$userId = 0;
		} elseif ( $type == Block::TYPE_IP ) {
			$target = $target->getName();
			$userId = 0;
		} else {
			# This should have been caught in the form field validation
			return array( 'badipaddress' );
		}

		if ( ( strlen( $data['Expiry'] ) == 0 ) || ( strlen( $data['Expiry'] ) > 50 )
			|| !self::parseExpiryInput( $data['Expiry'] )
		) {
			return array( 'ipb_expiry_invalid' );
		}

		if ( !isset( $data['DisableEmail'] ) ) {
			$data['DisableEmail'] = false;
		}

		# If the user has done the form 'properly', they won't even have been given the
		# option to suppress-block unless they have the 'hideuser' permission
		if ( !isset( $data['HideUser'] ) ) {
			$data['HideUser'] = false;
		}

		if ( $data['HideUser'] ) {
			if ( !$performer->isAllowed( 'hideuser' ) ) {
				# this codepath is unreachable except by a malicious user spoofing forms,
				# or by race conditions (user has oversight and sysop, loads block form,
				# and is de-oversighted before submission); so need to fail completely
				# rather than just silently disable hiding
				return array( 'badaccess-group0' );
			}

			# Recheck params here...
			if ( $type != Block::TYPE_USER ) {
				$data['HideUser'] = false; # IP users should not be hidden
			} elseif ( !in_array( $data['Expiry'], array( 'infinite', 'infinity', 'indefinite' ) ) ) {
				# Bad expiry.
				return array( 'ipb_expiry_temp' );
			} elseif ( $user->getEditCount() > self::HIDEUSER_CONTRIBLIMIT ) {
				# Typically, the user should have a handful of edits.
				# Disallow hiding users with many edits for performance.
				return array( 'ipb_hide_invalid' );
			} elseif ( !$data['Confirm'] ) {
				return array( 'ipb-confirmhideuser' );
			}
		}

		# Create block object.
		$block = new Block();
		$block->setTarget( $target );
		$block->setBlocker( $performer );
		$block->mReason = $data['Reason'][0];
		$block->mExpiry = self::parseExpiryInput( $data['Expiry'] );
		$block->prevents( 'createaccount', $data['CreateAccount'] );
		$block->prevents( 'editownusertalk', ( !$wgBlockAllowsUTEdit || $data['DisableUTEdit'] ) );
		$block->prevents( 'sendemail', $data['DisableEmail'] );
		$block->isHardblock( $data['HardBlock'] );
		$block->isAutoblocking( $data['AutoBlock'] );
		$block->mHideName = $data['HideUser'];

		if ( !wfRunHooks( 'BlockIp', array( &$block, &$performer ) ) ) {
			return array( 'hookaborted' );
		}

		# Try to insert block. Is there a conflicting block?
		$status = $block->insert();
		if ( !$status ) {
			# Indicates whether the user is confirming the block and is aware of
			# the conflict (did not change the block target in the meantime)
			$blockNotConfirmed = !$data['Confirm'] || ( array_key_exists( 'PreviousTarget', $data )
				&& $data['PreviousTarget'] !== $target );

			# Special case for API - bug 32434
			$reblockNotAllowed = ( array_key_exists( 'Reblock', $data ) && !$data['Reblock'] );

			# Show form unless the user is already aware of this...
			if ( $blockNotConfirmed || $reblockNotAllowed ) {
				return array( array( 'ipb_already_blocked', $block->getTarget() ) );
				# Otherwise, try to update the block...
			} else {
				# This returns direct blocks before autoblocks/rangeblocks, since we should
				# be sure the user is blocked by now it should work for our purposes
				$currentBlock = Block::newFromTarget( $target );

				if ( $block->equals( $currentBlock ) ) {
					return array( array( 'ipb_already_blocked', $block->getTarget() ) );
				}

				# If the name was hidden and the blocking user cannot hide
				# names, then don't allow any block changes...
				if ( $currentBlock->mHideName && !$performer->isAllowed( 'hideuser' ) ) {
					return array( 'cant-see-hidden-user' );
				}

				$currentBlock->delete();
				$status = $block->insert();
				$logaction = 'reblock';

				# Unset _deleted fields if requested
				if ( $currentBlock->mHideName && !$data['HideUser'] ) {
					RevisionDeleteUser::unsuppressUserName( $target, $userId );
				}

				# If hiding/unhiding a name, this should go in the private logs
				if ( (bool)$currentBlock->mHideName ) {
					$data['HideUser'] = true;
				}
			}
		} else {
			$logaction = 'block';
		}

		wfRunHooks( 'BlockIpComplete', array( $block, $performer ) );

		# Set *_deleted fields if requested
		if ( $data['HideUser'] ) {
			RevisionDeleteUser::suppressUserName( $target, $userId );
		}

		# Can't watch a rangeblock
		if ( $type != Block::TYPE_RANGE && $data['Watch'] ) {
			WatchAction::doWatch( Title::makeTitle( NS_USER, $target ), $performer, WatchedItem::IGNORE_USER_RIGHTS );
		}

		# Block constructor sanitizes certain block options on insert
		$data['BlockEmail'] = $block->prevents( 'sendemail' );
		$data['AutoBlock'] = $block->isAutoblocking();

		# Prepare log parameters
		$logParams = array();
		$logParams[] = $data['Expiry'];
		$logParams[] = self::blockLogFlags( $data, $type );

		# Make log entry, if the name is hidden, put it in the oversight log
		$log_type = $data['HideUser'] ? 'suppress' : 'block';
		$log = new LogPage( $log_type );
		$log_id = $log->addEntry(
			$logaction,
			Title::makeTitle( NS_USER, $target ),
			$data['Reason'][0],
			$logParams,
			$performer
		);
		# Relate log ID to block IDs (bug 25763)
		$blockIds = array_merge( array( $status['id'] ), $status['autoIds'] );
		$log->addRelations( 'ipb_id', $blockIds, $log_id );

		# Report to the user
		return true;
	}

	/**
	 * Get an array of suggested block durations from MediaWiki:Ipboptions
	 * @todo FIXME: This uses a rather odd syntax for the options, should it be converted
	 *     to the standard "**<duration>|<displayname>" format?
	 * @param $lang Language|null the language to get the durations in, or null to use
	 *     the wiki's content language
	 * @return Array
	 */
	public static function getSuggestedDurations( $lang = null ) {
		$a = array();
		$msg = $lang === null
			? wfMessage( 'ipboptions' )->inContentLanguage()->text()
			: wfMessage( 'ipboptions' )->inLanguage( $lang )->text();

		if ( $msg == '-' ) {
			return array();
		}

		foreach ( explode( ',', $msg ) as $option ) {
			if ( strpos( $option, ':' ) === false ) {
				$option = "$option:$option";
			}

			list( $show, $value ) = explode( ':', $option );
			$a[htmlspecialchars( $show )] = htmlspecialchars( $value );
		}

		return $a;
	}

	/**
	 * Convert a submitted expiry time, which may be relative ("2 weeks", etc) or absolute
	 * ("24 May 2034", etc), into an absolute timestamp we can put into the database.
	 * @param string $expiry whatever was typed into the form
	 * @return String: timestamp or "infinity" string for the DB implementation
	 */
	public static function parseExpiryInput( $expiry ) {
		static $infinity;
		if ( $infinity == null ) {
			$infinity = wfGetDB( DB_SLAVE )->getInfinity();
		}

		if ( $expiry == 'infinite' || $expiry == 'indefinite' ) {
			$expiry = $infinity;
		} else {
			$expiry = strtotime( $expiry );

			if ( $expiry < 0 || $expiry === false ) {
				return false;
			}

			$expiry = wfTimestamp( TS_MW, $expiry );
		}

		return $expiry;
	}

	/**
	 * Can we do an email block?
	 * @param $user User: the sysop wanting to make a block
	 * @return Boolean
	 */
	public static function canBlockEmail( $user ) {
		global $wgEnableUserEmail, $wgSysopEmailBans;

		return ( $wgEnableUserEmail && $wgSysopEmailBans && $user->isAllowed( 'blockemail' ) );
	}

	/**
	 * bug 15810: blocked admins should not be able to block/unblock
	 * others, and probably shouldn't be able to unblock themselves
	 * either.
	 * @param $user User|Int|String
	 * @param $performer User user doing the request
	 * @return Bool|String true or error message key
	 */
	public static function checkUnblockSelf( $user, User $performer ) {
		if ( is_int( $user ) ) {
			$user = User::newFromId( $user );
		} elseif ( is_string( $user ) ) {
			$user = User::newFromName( $user );
		}

		if ( $performer->isBlocked() ) {
			if ( $user instanceof User && $user->getId() == $performer->getId() ) {
				# User is trying to unblock themselves
				if ( $performer->isAllowed( 'unblockself' ) ) {
					return true;
					# User blocked themselves and is now trying to reverse it
				} elseif ( $performer->blockedBy() === $performer->getName() ) {
					return true;
				} else {
					return 'ipbnounblockself';
				}
			} else {
				# User is trying to block/unblock someone else
				return 'ipbblocked';
			}
		} else {
			return true;
		}
	}

	/**
	 * Return a comma-delimited list of "flags" to be passed to the log
	 * reader for this block, to provide more information in the logs
	 * @param array $data from HTMLForm data
	 * @param $type Block::TYPE_ constant (USER, RANGE, or IP)
	 * @return string
	 */
	protected static function blockLogFlags( array $data, $type ) {
		global $wgBlockAllowsUTEdit;
		$flags = array();

		# when blocking a user the option 'anononly' is not available/has no effect
		# -> do not write this into log
		if ( !$data['HardBlock'] && $type != Block::TYPE_USER ) {
			// For grepping: message block-log-flags-anononly
			$flags[] = 'anononly';
		}

		if ( $data['CreateAccount'] ) {
			// For grepping: message block-log-flags-nocreate
			$flags[] = 'nocreate';
		}

		# Same as anononly, this is not displayed when blocking an IP address
		if ( !$data['AutoBlock'] && $type == Block::TYPE_USER ) {
			// For grepping: message block-log-flags-noautoblock
			$flags[] = 'noautoblock';
		}

		if ( $data['DisableEmail'] ) {
			// For grepping: message block-log-flags-noemail
			$flags[] = 'noemail';
		}

		if ( $wgBlockAllowsUTEdit && $data['DisableUTEdit'] ) {
			// For grepping: message block-log-flags-nousertalk
			$flags[] = 'nousertalk';
		}

		if ( $data['HideUser'] ) {
			// For grepping: message block-log-flags-hiddenname
			$flags[] = 'hiddenname';
		}

		return implode( ',', $flags );
	}

	/**
	 * Process the form on POST submission.
	 * @param  $data Array
	 * @return Bool|Array true for success, false for didn't-try, array of errors on failure
	 */
	public function onSubmit( array $data ) {
		// This isn't used since we need that HTMLForm that's passed in the
		// second parameter. See alterForm for the real function
	}

	/**
	 * Do something exciting on successful processing of the form, most likely to show a
	 * confirmation message
	 */
	public function onSuccess() {
		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'blockipsuccesssub' ) );
		$out->addWikiMsg( 'blockipsuccesstext', wfEscapeWikiText( $this->target ) );
	}

	protected function getGroupName() {
		return 'users';
	}
}

# BC @since 1.18
class IPBlockForm extends SpecialBlock {
}
