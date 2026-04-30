<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials\Helpers;

use MediaWiki\Config\Config;
use MediaWiki\Context\IContextSource;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\Language\Language;
use MediaWiki\Language\MessageLocalizer;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\User\UserRequirementsConditionChecker;

/**
 * Trait providing methods to format user group requirement conditions as HTML.
 * Intended for use in special pages that display restricted group configuration.
 *
 * Classes using this trait must extend SpecialPage (or otherwise provide the required methods).
 *
 * @since 1.46
 * @stable to use
 */
trait UserRequirementsConditionFormatterTrait {

	/**
	 * Create a user-readable tree describing the given condition
	 * and format it as an HTML list (potentially nested).
	 */
	protected function formatCondition( string|int|array $condition ): string {
		if ( is_array( $condition ) && count( $condition ) > 0 ) {
			$condName = array_shift( $condition );
			if ( in_array( $condName, UserRequirementsConditionChecker::VALID_OPS ) ) {
				$listItems = '';
				foreach ( $condition as $subcond ) {
					$listItems .= Html::rawElement( 'li', [], $this->formatCondition( $subcond ) );
				}
				$htmlList = Html::rawElement( 'ul', [], $listItems );
				$condName = match ( $condName ) {
					'&' => 'listgrouprights-restrictedgroups-op-and',
					'|' => 'listgrouprights-restrictedgroups-op-or',
					'^' => 'listgrouprights-restrictedgroups-op-xor',
					// Even though '!' is usually understood as 'NOT', in fact it's 'NAND' as it can accept
					// multiple arguments
					'!' => 'listgrouprights-restrictedgroups-op-nand',
				};
				return $this->msg( $condName )
					->rawParams( $htmlList )
					->parse();
			} else {
				return $this->formatAtomicCondition( $condName, $condition );
			}
		} elseif ( is_array( $condition ) ) {
			return '';
		} else {
			return $this->formatAtomicCondition( $condition, [] );
		}
	}

	/**
	 * Prepares a message for atomic condition and its arguments.
	 * Atomic conditions are conditions that do not contain any other conditions.
	 */
	protected function formatAtomicCondition( string|int $condName, array $args ): string {
		$msgKey = match ( $condName ) {
			APCOND_EDITCOUNT => 'listgrouprights-restrictedgroups-cond-editcount',
			APCOND_AGE => 'listgrouprights-restrictedgroups-cond-age',
			APCOND_EMAILCONFIRMED => 'listgrouprights-restrictedgroups-cond-emailconfirmed',
			APCOND_INGROUPS => 'listgrouprights-restrictedgroups-cond-ingroups',
			APCOND_ISIP => 'listgrouprights-restrictedgroups-cond-isip',
			APCOND_IPINRANGE => 'listgrouprights-restrictedgroups-cond-ipinrange',
			APCOND_AGE_FROM_EDIT => 'listgrouprights-restrictedgroups-cond-age-from-edit',
			APCOND_BLOCKED => 'listgrouprights-restrictedgroups-cond-blocked',
			APCOND_ISBOT => 'listgrouprights-restrictedgroups-cond-isbot',
			default => null,
		};

		if ( $msgKey === null ) {
			$messageSpec = null;
			$context = $this->getContext();
			$this->getHookRunner()->onUserRequirementsConditionDisplay( $condName, $args, $context, $messageSpec );
			if ( $messageSpec !== null ) {
				return $this->msg( $messageSpec )->parse();
			} else {
				$msgKey = 'listgrouprights-restrictedgroups-cond-' . $condName;
			}
		}
		$msg = $this->msg( $msgKey );

		if ( $condName === APCOND_AGE || $condName === APCOND_AGE_FROM_EDIT ) {
			$minAge = $args[0] ?? $this->getConfig()->get( MainConfigNames::AutoConfirmAge );
			$msg->durationParams( $minAge );
		} elseif ( $condName === APCOND_INGROUPS ) {
			$groupNames = [];
			foreach ( $args as $group ) {
				$groupNames[] = $this->getLanguage()->getGroupName( $group );
			}
			$msg->numParams( count( $args ) )->params( Message::listParam( $groupNames ) );
		} elseif ( $condName === APCOND_EDITCOUNT ) {
			$minEdits = $args[0] ?? $this->getConfig()->get( MainConfigNames::AutoConfirmCount );
			$msg->numParams( $minEdits );
		} else {
			$msg->params( ...$args );
		}

		return $msg->parse();
	}

	// Abstract methods defined in SpecialPage and required by this trait

	/**
	 * @see MessageLocalizer::msg
	 * @param mixed $key
	 * @param mixed ...$params
	 * @return Message
	 */
	abstract protected function msg( $key, ...$params );

	/**
	 * @see IContextSource::getConfig
	 * @return Config
	 */
	abstract protected function getConfig();

	/**
	 * @see IContextSource::getLanguage
	 * @return Language
	 */
	abstract protected function getLanguage();

	/**
	 * @see SpecialPage::getContext
	 * @return IContextSource
	 */
	abstract protected function getContext();

	/**
	 * @see SpecialPage::getHookRunner
	 * @return HookRunner
	 */
	abstract protected function getHookRunner();
}
