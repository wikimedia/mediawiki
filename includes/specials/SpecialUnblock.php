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

namespace MediaWiki\Specials;

use HTMLForm;
use LogEventsList;
use MediaWiki\Block\BlockUtils;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\UnblockUserFactory;
use MediaWiki\Request\WebRequest;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserNamePrefixSearch;
use MediaWiki\User\UserNameUtils;
use MediaWiki\Watchlist\WatchlistManager;
use Wikimedia\IPUtils;

/**
 * A special page for unblocking users
 *
 * @ingroup SpecialPage
 */
class SpecialUnblock extends SpecialPage {

	/** @var UserIdentity|string|null */
	protected $target;

	/** @var int|null DatabaseBlock::TYPE_ constant */
	protected $type;

	protected $block;

	private UnblockUserFactory $unblockUserFactory;
	private BlockUtils $blockUtils;
	private UserNameUtils $userNameUtils;
	private UserNamePrefixSearch $userNamePrefixSearch;
	private WatchlistManager $watchlistManager;

	/**
	 * @param UnblockUserFactory $unblockUserFactory
	 * @param BlockUtils $blockUtils
	 * @param UserNameUtils $userNameUtils
	 * @param UserNamePrefixSearch $userNamePrefixSearch
	 * @param WatchlistManager $watchlistManager
	 */
	public function __construct(
		UnblockUserFactory $unblockUserFactory,
		BlockUtils $blockUtils,
		UserNameUtils $userNameUtils,
		UserNamePrefixSearch $userNamePrefixSearch,
		WatchlistManager $watchlistManager
	) {
		parent::__construct( 'Unblock', 'block' );
		$this->unblockUserFactory = $unblockUserFactory;
		$this->blockUtils = $blockUtils;
		$this->userNameUtils = $userNameUtils;
		$this->userNamePrefixSearch = $userNamePrefixSearch;
		$this->watchlistManager = $watchlistManager;
	}

	public function doesWrites() {
		return true;
	}

	public function execute( $par ) {
		$this->checkPermissions();
		$this->checkReadOnly();

		[ $this->target, $this->type ] = $this->getTargetAndType( $par, $this->getRequest() );
		$this->block = DatabaseBlock::newFromTarget( $this->target );
		if ( $this->target instanceof UserIdentity ) {
			// Set the 'relevant user' in the skin, so it displays links like Contributions,
			// User logs, UserRights, etc.
			$this->getSkin()->setRelevantUser( $this->target );
		}

		$this->setHeaders();
		$this->outputHeader();
		$this->addHelpLink( 'Help:Blocking users' );

		$out = $this->getOutput();
		$out->setPageTitleMsg( $this->msg( 'unblockip' ) );
		$out->addModules( [ 'mediawiki.userSuggest', 'mediawiki.special.block' ] );

		$form = HTMLForm::factory( 'ooui', $this->getFields(), $this->getContext() )
			->setWrapperLegendMsg( 'unblockip' )
			->setSubmitCallback( function ( array $data, HTMLForm $form ) {
				if ( $this->type != DatabaseBlock::TYPE_RANGE
					&& $this->type != DatabaseBlock::TYPE_AUTO
					&& $data['Watch']
				) {
					$this->watchlistManager->addWatchIgnoringRights(
						$form->getUser(),
						Title::makeTitle( NS_USER, $this->target )
					);
				}
				return $this->unblockUserFactory->newUnblockUser(
					$data['Target'],
					$form->getContext()->getAuthority(),
					$data['Reason'],
					$data['Tags'] ?? []
				)->unblock();
			} )
			->setSubmitTextMsg( 'ipusubmit' )
			->addPreHtml( $this->msg( 'unblockiptext' )->parseAsBlock() );

		$userPage = $this->getTargetUserTitle( $this->target );
		if ( $userPage ) {
			// Get relevant extracts from the block and suppression logs, if possible
			$logExtract = '';
			LogEventsList::showLogExtract(
				$logExtract,
				'block',
				$userPage,
				'',
				[
					'lim' => 10,
					'msgKey' => [
						'unblocklog-showlog',
						$userPage->getText(),
					],
					'showIfEmpty' => false
				]
			);
			if ( $logExtract !== '' ) {
				$form->addPostHtml( $logExtract );
			}

			// Add suppression block entries if allowed
			if ( $this->getAuthority()->isAllowed( 'suppressionlog' ) ) {
				$logExtract = '';
				LogEventsList::showLogExtract(
					$logExtract,
					'suppress',
					$userPage,
					'',
					[
						'lim' => 10,
						'conds' => [ 'log_action' => [ 'block', 'reblock', 'unblock' ] ],
						'msgKey' => [
							'unblocklog-showsuppresslog',
							$userPage->getText(),
						],
						'showIfEmpty' => false
					]
				);
				if ( $logExtract !== '' ) {
					$form->addPostHtml( $logExtract );
				}
			}
		}

		if ( $form->show() ) {
			switch ( $this->type ) {
				case DatabaseBlock::TYPE_IP:
					// @phan-suppress-next-line PhanTypeMismatchArgumentNullable target is set when type is set
					$out->addWikiMsg( 'unblocked-ip', wfEscapeWikiText( $this->target ) );
					break;
				case DatabaseBlock::TYPE_USER:
					// @phan-suppress-next-line PhanTypeMismatchArgumentNullable target is set when type is set
					$out->addWikiMsg( 'unblocked', wfEscapeWikiText( $this->target ) );
					break;
				case DatabaseBlock::TYPE_RANGE:
					// @phan-suppress-next-line PhanTypeMismatchArgumentNullable target is set when type is set
					$out->addWikiMsg( 'unblocked-range', wfEscapeWikiText( $this->target ) );
					break;
				case DatabaseBlock::TYPE_ID:
				case DatabaseBlock::TYPE_AUTO:
					// @phan-suppress-next-line PhanTypeMismatchArgumentNullable target is set when type is set
					$out->addWikiMsg( 'unblocked-id', wfEscapeWikiText( $this->target ) );
					break;
			}
		}
	}

	/**
	 * Get the target and type, given the request and the subpage parameter.
	 * Several parameters are handled for backwards compatability. 'wpTarget' is
	 * prioritized, since it matches the HTML form.
	 *
	 * @param string|null $par Subpage parameter
	 * @param WebRequest $request
	 * @return array [ UserIdentity|string|null, DatabaseBlock::TYPE_ constant|null ]
	 * @phan-return array{0:UserIdentity|string|null,1:int|null}
	 */
	private function getTargetAndType( ?string $par, WebRequest $request ) {
		$possibleTargets = [
			$request->getVal( 'wpTarget', null ),
			$par,
			$request->getVal( 'ip', null ),
			// B/C @since 1.18
			$request->getVal( 'wpBlockAddress', null ),
		];
		foreach ( $possibleTargets as $possibleTarget ) {
			$targetAndType = $this->blockUtils->parseBlockTarget( $possibleTarget );
			// If type is not null then target is valid
			if ( $targetAndType[ 1 ] !== null ) {
				break;
			}
		}
		return $targetAndType;
	}

	/**
	 * Get a user page target for things like logs.
	 * This handles account and IP range targets.
	 * @param UserIdentity|string|null $target
	 * @return Title|null
	 */
	private function getTargetUserTitle( $target ): ?Title {
		if ( $target instanceof UserIdentity ) {
			return Title::makeTitle( NS_USER, $target->getName() );
		}

		if ( is_string( $target ) && IPUtils::isIPAddress( $target ) ) {
			return Title::makeTitle( NS_USER, $target );
		}

		return null;
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
			$type = $this->block->getType();
			$targetName = $this->block->getTargetName();

			// Autoblocks are logged as "autoblock #123 because the IP was recently used by
			// User:Foo, and we've just got any block, auto or not, that applies to a target
			// the user has specified.  Someone could be fishing to connect IPs to autoblocks,
			// so don't show any distinction between unblocked IPs and autoblocked IPs
			if ( $type == DatabaseBlock::TYPE_AUTO && $this->type == DatabaseBlock::TYPE_IP ) {
				$fields['Target']['default'] = $this->target;
				unset( $fields['Name'] );
			} else {
				$fields['Target']['default'] = $targetName;
				$fields['Target']['type'] = 'hidden';
				switch ( $type ) {
					case DatabaseBlock::TYPE_IP:
						$fields['Name']['default'] = $this->getLinkRenderer()->makeKnownLink(
							$this->getSpecialPageFactory()->getTitleForAlias( 'Contributions/' . $targetName ),
							$targetName
						);
						$fields['Name']['raw'] = true;
						break;
					case DatabaseBlock::TYPE_USER:
						$fields['Name']['default'] = $this->getLinkRenderer()->makeLink(
							new TitleValue( NS_USER, $targetName ),
							$targetName
						);
						$fields['Name']['raw'] = true;
						break;

					case DatabaseBlock::TYPE_RANGE:
						$fields['Name']['default'] = $targetName;
						break;

					case DatabaseBlock::TYPE_AUTO:
						$fields['Name']['default'] = $this->block->getRedactedName();
						$fields['Name']['raw'] = true;
						// Don't expose the real target of the autoblock
						$fields['Target']['default'] = "#{$this->target}";
						break;
				}
				// Target is hidden, so the reason is the first element
				$fields['Target']['autofocus'] = false;
				$fields['Reason']['autofocus'] = true;
			}
		} else {
			$fields['Target']['default'] = $this->target;
			unset( $fields['Name'] );
		}
		// Watchlist their user page? (Only if user is logged in)
		if ( $this->getUser()->isRegistered() ) {
			$fields['Watch'] = [
				'type' => 'check',
				'label-message' => 'ipbwatchuser',
			];
		}

		return $fields;
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
		$search = $this->userNameUtils->getCanonical( $search );
		if ( !$search ) {
			// No prefix suggestion for invalid user
			return [];
		}
		// Autocomplete subpage as user list - public to allow caching
		return $this->userNamePrefixSearch
			->search( UserNamePrefixSearch::AUDIENCE_PUBLIC, $search, $limit, $offset );
	}

	protected function getGroupName() {
		return 'users';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialUnblock::class, 'SpecialUnblock' );
