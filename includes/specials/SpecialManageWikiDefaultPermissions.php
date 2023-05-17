<?php

namespace Miraheze\ManageWiki\Specials;

use Config;
use ErrorPageError;
use GlobalVarConfig;
use Html;
use HTMLForm;
use ManualLogEntry;
use MediaWiki\MediaWikiServices;
use Miraheze\CreateWiki\RemoteWiki;
use Miraheze\ManageWiki\FormFactory\ManageWikiFormFactory;
use Miraheze\ManageWiki\Helpers\ManageWikiPermissions;
use Miraheze\ManageWiki\Hooks;
use Miraheze\ManageWiki\ManageWiki;
use SpecialPage;
use UserGroupMembership;

class SpecialManageWikiDefaultPermissions extends SpecialPage {
	/** @var Config */
	private $config;

	public function __construct() {
		parent::__construct( 'ManageWikiDefaultPermissions' );
		$this->config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'managewiki' );
	}

	public function execute( $par ) {
		$this->setHeaders();
		$out = $this->getOutput();
		$globalwiki = $this->config->get( 'CreateWikiGlobalWiki' );

		if ( !ManageWiki::checkSetup( 'permissions', true, $out ) ) {
			throw new ErrorPageError( 'managewiki-unavailable', 'managewiki-disabled', [ '1' => 'permissions' ] );
		}

		if ( $par != '' && ( $globalwiki == $this->config->get( 'DBname' ) ) ) {
			$this->getOutput()->addBacklinkSubtitle( $this->getPageTitle() );
			$this->buildGroupView( $par );
		} else {
			$this->buildMainView();
		}
	}

	public function buildMainView() {
		$canChangeDefaultPerms = MediaWikiServices::getInstance()->getPermissionManager()->userHasRight( $this->getContext()->getUser(), 'managewiki-editdefault' );
		$globalwiki = $this->config->get( 'CreateWikiGlobalWiki' );

		$out = $this->getOutput();

		if ( $globalwiki == $this->config->get( 'DBname' ) ) {
			$mwPermissions = new ManageWikiPermissions( 'default' );
			$groups = array_keys( $mwPermissions->list() );
			$craftedGroups = [];

			foreach ( $groups as $group ) {
				$craftedGroups[UserGroupMembership::getGroupName( $group )] = $group;
			}

			$out->addWikiMsg( 'managewiki-header-permissions' );

			$groupSelector = [];

			$groupSelector['groups'] = [
				'label-message' => 'managewiki-permissions-select',
				'type' => 'select',
				'options' => $craftedGroups,
			];

			$selectForm = HTMLForm::factory( 'ooui', $groupSelector, $this->getContext(), 'groupSelector' );
			$selectForm->setMethod( 'post' )->setFormIdentifier( 'groupSelector' )->setSubmitCallback( [ $this, 'onSubmitRedirectToPermissionsPage' ] )->prepareForm()->show();

			if ( $canChangeDefaultPerms ) {
				$createDescriptor = [];

				$createDescriptor['groups'] = [
					'type' => 'text',
					'label-message' => 'managewiki-permissions-create',
					'validation-callback' => [ $this, 'validateNewGroupName' ],
				];

				$createForm = HTMLForm::factory( 'ooui', $createDescriptor, $this->getContext() );
				$createForm->setMethod( 'post' )->setFormIdentifier( 'createForm' )->setSubmitCallback( [ $this, 'onSubmitRedirectToPermissionsPage' ] )->prepareForm()->show();
			}
		} elseif ( !( $globalwiki == $this->config->get( 'DBname' ) ) && !$canChangeDefaultPerms ) {
				throw new ErrorPageError( 'managewiki-unavailable', 'managewiki-unavailable-notglobalwiki' );
		}

		if ( !( $globalwiki == $this->config->get( 'DBname' ) ) && $canChangeDefaultPerms ) {
			$out->setPageTitle( $this->msg( 'managewiki-permissions-resetgroups-title' )->plain() );
			$out->addWikiMsg( 'managewiki-permissions-resetgroups-header' );

			$resetForm = HTMLForm::factory( 'ooui', [], $this->getContext() );
			$resetForm->setMethod( 'post' )->setFormIdentifier( 'resetform' )->setSubmitTextMsg( 'managewiki-permissions-resetgroups' )->setSubmitDestructive()->setSubmitCallback( [ $this, 'onSubmitResetForm' ] )->prepareForm()->show();
		}
	}

	public function onSubmitRedirectToPermissionsPage( array $params ) {
		header( 'Location: ' . SpecialPage::getTitleFor( 'ManageWikiDefaultPermissions' )->getFullURL() . '/' . $params['groups'] );

		return true;
	}

	public function onSubmitResetForm( $formData ) {
		$out = $this->getOutput();

		$dbw = MediaWikiServices::getInstance()->getDBLoadBalancerFactory()
			->getMainLB( $this->config->get( 'CreateWikiDatabase' ) )
			->getMaintenanceConnectionRef( DB_PRIMARY, [], $this->config->get( 'CreateWikiDatabase' ) );

		$dbw->delete(
			'mw_permissions',
			[
				'perm_dbname' => $this->config->get( 'DBname' )
			],
			__METHOD__
		);

		$cwConfig = new GlobalVarConfig( 'cw' );
		Hooks::onCreateWikiCreation( $this->config->get( 'DBname' ), $cwConfig->get( 'Private' ) );

		$logEntry = new ManualLogEntry( 'managewiki', 'rights-reset' );
		$logEntry->setPerformer( $this->getContext()->getUser() );
		$logEntry->setTarget( SpecialPage::getTitleValueFor( 'ManageWikiDefaultPermissions' ) );
		$logEntry->setParameters( [ '4::wiki' => $this->config->get( 'DBname' ) ] );
		$logID = $logEntry->insert();
		$logEntry->publish( $logID );

		$out->addHTML( Html::successBox( $this->msg( 'managewiki-success' )->escaped() ) );

		return true;
	}

	public static function validateNewGroupName( $newGroup, $nullForm ) {
		if ( in_array( $newGroup, MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'managewiki' )->get( 'ManageWikiPermissionsDisallowedGroups' ) ) ) {
			return 'The group you attempted to create is not allowed. Please select a different name and try again.';
		}

		return true;
	}

	public function buildGroupView( $group ) {
		$out = $this->getOutput();

		$out->addModules( [ 'ext.managewiki.oouiform' ] );
		$out->addModuleStyles( [
			'ext.managewiki.oouiform.styles',
			'mediawiki.widgets.TagMultiselectWidget.styles',
		] );
		$out->addModuleStyles( [ 'oojs-ui-widgets.styles' ] );

		$remoteWiki = new RemoteWiki( $this->config->get( 'CreateWikiGlobalWiki' ) );

		$formFactory = new ManageWikiFormFactory();
		$htmlForm = $formFactory->getForm( 'default', $remoteWiki, $this->getContext(), $this->config, 'permissions', $group );

		$htmlForm->show();
	}

	public function isListed() {
		$canChangeDefaultPerms = MediaWikiServices::getInstance()->getPermissionManager()->userHasRight( $this->getContext()->getUser(), 'managewiki-editdefault' );
		$globalwiki = $this->config->get( 'CreateWikiGlobalWiki' );

		return $globalwiki == $this->config->get( 'DBname' ) || $canChangeDefaultPerms;
	}

	protected function getGroupName() {
		return 'wikimanage';
	}
}
