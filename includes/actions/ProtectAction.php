<?php
/**
 * User interface/form for page restriction actions.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @file
 * @ingroup Actions
 */

/**
 * User interface/form for page restriction actions.
 *
 * @ingroup Actions
 */
class ProtectAction extends FormAction {

	/**
	 * @var array List of expiry times for existing restrictions on this page, if any.
	 */
	protected $existingExpiry = array();

	/**
	 * @var array Valid restriction types for this page.
	 */
	protected $restrictionTypes = array();

	/**
	 * Constructor to initialize and filter valid restriction types for this page.
	 */
	public function __construct( Page $page, IContextSource $context ) {
		parent::__construct( $page, $context );
		global $wgCascadingRestrictionLevels, $wgRestrictionTypes;
		$this->getOutput()->addJsConfigVars( 'wgCascadeableLevels', $wgCascadingRestrictionLevels );
		foreach ( $wgRestrictionTypes as $type ) {
			if ( $this->getTitle()->exists() ) {
				if ( $type === 'create' ) {
					continue;
				}
			} else {
				if ( $type !== 'create' ) {
					continue;
				}
			}
			if ( $this->getTitle()->getNamespace() !== NS_FILE && $type === 'upload' ) {
				continue;
			}
			$this->existingExpiry[$type] = $this->getTitle()->getRestrictionExpiry( $type );
			$this->restrictionTypes[] = $type;
		}
	}

	/**
	 * Overloaded to provide additional error checking for this page.
	 *
	 * @param User $user
	 * @see Action::checkCanExecute()
	 */
	public function checkCanExecute( User $user ) {
		if ( MWNamespace::getRestrictionLevels( $this->getTitle()->getNamespace() ) === array( '' ) ) {
			throw new ErrorPageError( 'protect-badnamespace-title', 'protect-badnamespace-text' );
		}
		if ( !$this->restrictionTypes ) {
			throw new ErrorPageError(
				'protect-norestrictiontypes-title',
				'protect-norestrictiontypes-text'
			);
		}
		parent::checkCanExecute( $user );
	}

	public function getFormFields() {
		$fields = array();
		$title = $this->getTitle();
		$user = $this->getUser();
		$optsLevel = array();
		$levels = MWNamespace::getRestrictionLevels( $title->getNamespace(), $user );
		foreach ( $levels as $level ) {
			$msgLevel = null;
			if ( $level === '' ) {
				$msgLevel = $this->msg( 'protect-default' );
			} else {
				$msgLevel = $this->msg( "protect-level-{$level}" );
				if ( !$msgLevel->exists() ) {
					$msgLevel = $this->msg( 'protect-fallback', $level );
				}
			}
			$optsLevel[$msgLevel->text()] = $level;
		}
		foreach ( $this->restrictionTypes as $type ) {
			$optsExpiry = array();
			if ( $this->existingExpiry[$type] && ( $this->existingExpiry[$type] !== 'infinity' ) ) {
				$date = $this->getLanguage()->date( $this->existingExpiry[$type], true );
				$time = $this->getLanguage()->time( $this->existingExpiry[$type], true );
				$optsExpiry[$this->msg( 'protect-existing-expiry', null, $date, $time )->text()] = 'existing';
			}
			$optsExpiry[$this->msg( 'protect-othertime-op' )->text()] = 'othertime';
			$msgExpiryOptions = $this->msg( 'protect-expiry-options' )->inContentLanguage()->text();
			foreach ( explode( ',', $msgExpiryOptions ) as $option ) {
				if ( strpos( $option, ":" ) === false ) {
					$show = $value = $option;
				} else {
					list( $show, $value ) = explode( ":", $option );
				}
				$optsExpiry[htmlspecialchars( $show )] = htmlspecialchars( $value );
			}
			$uctype = ucfirst( $type );
			$restrictions = $title->getRestrictions( $type ); // otherwise array_shift will emit a warning
			// Messages: protect-restriction-edit, protect-restriction-move,
			// protect-restriction-create, protect-restriction-upload
			$fields["{$uctype}Level"] = array(
				'default' => array_shift( $restrictions ),
				'id' => "mwProtect-level-{$type}",
				'name' => "mwProtect-level-{$type}",
				'options' => $optsLevel,
				'section' => "legend/restriction-{$type}",
				'size' => count( $levels ),
				'type' => 'select',
			);
			$fields["{$uctype}Expiry"] = array(
				'id' => "mwProtectExpirySelection-{$type}",
				'label-message' => 'protectexpiry',
				'name' => "mwProtectExpirySelection-{$type}",
				'options' => $optsExpiry,
				'section' => "legend/restriction-{$type}",
				'type' => 'select',
			);
			$fields["{$uctype}OtherExpiry"] = array(
				'id' => "mwProtect-{$type}-expires",
				'label-message' => 'protect-othertime',
				'name' => "mwProtect-{$type}-expires",
				'section' => "legend/restriction-{$type}",
				'size' => 60,
				'type' => 'text',
			);
		}
		$fields['Reason'] = array(
			'id' => 'wpReason',
			'label-message' => 'protectcomment',
			'maxlength' => 255,
			'options-message' => 'protect-dropdown',
			'section' => 'legend',
			'size' => 60,
			'type' => 'selectandother',
		);
		if ( $title->exists() ) {
			$fields['Cascade'] = array(
				'default' => $title->areRestrictionsCascading(),
				'id' => 'mwProtect-cascade',
				'label-message' => 'protect-cascade',
				'name' => 'mwProtect-cascade',
				'section' => 'legend',
				'type' => 'check',
			);
		}
		if ( !$user->isAnon() && $user->isAllowed( 'editmywatchlist' ) ) {
			$watchitem = WatchedItem::fromUserTitle( $user, $title, WatchedItem::CHECK_USER_RIGHTS );
			$checkWatch = $user->getBoolOption( 'watchdefault' ) || $watchitem->isWatched();
			$fields['Watch'] = array(
				'default' => $checkWatch,
				'id' => 'wpWatch',
				'label-message' => 'watchthis',
				'section' => 'legend',
				'type' => 'check',
			);
		}
		return $fields;
	}

	public function getName() {
		return 'protect';
	}

	public function getRestriction() {
		return 'protect';
	}

	public function onSubmit( $data ) {
		$title = $this->getTitle();
		$user = $this->getUser();
		$levels = array();
		$expiry = array();
		foreach ( $this->restrictionTypes as $type ) {
			$uctype = ucfirst( $type );
			$levels[$type] = $data["{$uctype}Level"];
			if ( !$levels[$type] ) {
				continue;
			}
			$value = $data["{$uctype}Expiry"];
			switch ( $value ) {
				case 'existing':
					$expiry[$type] = $title->getRestrictionExpiry( $type );
					break;
				case 'infinity':
					$expiry[$type] = wfGetDB( DB_SLAVE )->getInfinity();
					break;
				default:
					if ( $value === 'othertime' ) {
						$value = $data["{$uctype}OtherExpiry"];
					}
					$unix = strtotime( $value );
					if ( !$unix || $unix === -1 ) {
						return Status::newFatal( 'protect_expiry_invalid' )->getErrorsArray();
					}
					$expiry[$type] = wfTimestamp( TS_MW, $unix );
					if ( $expiry[$type] < wfTimestampNow() ) {
						return Status::newFatal( 'protect_expiry_old' )->getErrorsArray();
					}
			}
		}
		$cascade = false;
		if ( isset( $data['Cascade'] ) && $data['Cascade'] ) {
			$cascade = true;
		}
		$reason = $data['Reason'][0];
		$status = $this->page->doUpdateRestrictions( $levels, $expiry, $cascade, $reason, $user );
		if ( !$status->isGood() ) {
			return $status->getErrorsArray();
		}
		$watchitem = WatchedItem::fromUserTitle( $user, $title, WatchedItem::CHECK_USER_RIGHTS );
		if ( isset( $data['Watch'] ) && $data['Watch'] && !$watchitem->isWatched() ) {
			$watchitem->addWatch();
			$user->invalidateCache();
		} elseif ( !$data['Watch'] && $watchitem->isWatched() ) {
			$watchitem->removeWatch();
			$user->invalidateCache();
		}
		return true;
	}

	public function onSuccess() {
		$query = array();
		if ( $this->page->isRedirect() ) {
			$query['redirect'] = 'no';
		}
		$this->getOutput()->redirect( $this->getTitle()->getFullURL( $query ) );
	}

	public function preText() {
		$form = '';
		$cascadesources = $this->getTitle()->getCascadeProtectionSources( true );
		$cascadesources = $cascadesources[0];
		if ( $cascadesources && count( $cascadesources ) ) {
			$form .= Html::openElement( 'div', array( 'id' => 'mw-protect-cascadeon' ) ) . "\n";
			$form .= $this->msg( 'protect-cascadeon' )->numParams( count( $cascadesources ) )->parse();
			$form .= Xml::openElement( 'ul' ) . "\n";
			foreach ( $cascadesources as $source ) {
				$form .= Html::rawElement( 'li', null, Linker::link( $source ) ) . "\n";
			}
			$form .= Xml::closeElement( 'ul' ) . "\n";
			$form .= Html::closeElement( 'div' ) . "\n";
		}
		$form .= $this->msg( 'protect-text', $this->getTitle() )->parseAsBlock();
		return $form;
	}

	public function postText() {
		$form = '';
		if ( $this->getUser()->isAllowed( 'editinterface' ) ) {
			$mwtitle = Title::makeTitle( NS_MEDIAWIKI, 'Protect-dropdown' );
			$form .= Html::rawElement( 'p', array( 'class' => 'mw-protect-editreasons' ),
				Linker::link(
					$mwtitle,
					$this->msg( 'protect-edit-reasonlist' )->escaped(),
					array(),
					array( 'action' => 'edit' )
				)
			) . "\n";
		}
		$options = array(
			'tableId' => 'mwProtectSet',
			'labelText' => $this->msg( 'protect-unchain-permissions' )->plain(),
			'numTypes' => count( $this->restrictionTypes ),
			'existingMatch' => count( array_unique( $this->existingExpiry ) ) === 1,
		);
		$script = Xml::encodeJsCall( 'ProtectionForm.init', array( $options ) );
		$form .= Html::inlineScript( ResourceLoader::makeLoaderConditionalScript( $script ) );
		$protectLogPage = new LogPage( 'protect' );
		$protLogMsg = $protectLogPage->getName()->text();
		$form .= Xml::openElement( 'h2' ) .
			Xml::element( 'span', array( 'class' => 'mw-headline', 'id' => $protLogMsg ), $protLogMsg ) .
		Xml::closeElement( 'h2' ) . "\n";
		$html = '';
		LogEventsList::showLogExtract( $html, 'protect', $this->getTitle() );
		$form .= $html;
		return $form;
	}

	public function alterForm( HTMLForm $form ) {
		$form->setSubmitID( 'mw-Protect-submit' );
		$form->setSubmitText( $this->msg( 'confirm' )->text() );
	}

	public function setHeaders() {
		$output = $this->getOutput();
		$output->setRobotPolicy( 'noindex,nofollow' );
		$output->setPageTitle( $this->msg( 'protect-title', $this->getTitle() ) );
		$output->addBacklinkSubtitle( $this->getTitle() );
		$output->setArticleRelated( true );
		$output->addModules( 'mediawiki.legacy.protect' );
	}
}
