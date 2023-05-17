<?php

namespace Miraheze\ManageWiki\FormFactory;

use Config;
use Html;
use HTMLForm;
use IContextSource;
use MediaWiki\MediaWikiServices;
use Miraheze\CreateWiki\RemoteWiki;
use Miraheze\ManageWiki\Helpers\ManageWikiOOUIForm;
use Miraheze\ManageWiki\ManageWiki;
use MWException;
use OutputPage;
use Wikimedia\Rdbms\DBConnRef;

class ManageWikiFormFactory {
	public function getFormDescriptor(
		string $module,
		string $dbName,
		bool $ceMW,
		IContextSource $context,
		RemoteWiki $wiki,
		Config $config,
		string $special = '',
		string $filtered = ''
	) {
		OutputPage::setupOOUI(
			strtolower( $context->getSkin()->getSkinName() ),
			$context->getLanguage()->getDir()
		);

		return ManageWikiFormFactoryBuilder::buildDescriptor( $module, $dbName, $ceMW, $context, $wiki, $special, $filtered, $config );
	}

	public function getForm(
		string $wiki,
		RemoteWiki $remoteWiki,
		IContextSource $context,
		Config $config,
		string $module,
		string $special = '',
		string $filtered = '',
		string $formClass = ManageWikiOOUIForm::class
	) {
		$dbw = MediaWikiServices::getInstance()->getDBLoadBalancerFactory()
			->getMainLB( $config->get( 'CreateWikiDatabase' ) )
			->getMaintenanceConnectionRef( DB_PRIMARY, [], $config->get( 'CreateWikiDatabase' ) );

		$ceMW = ManageWiki::checkPermission( $remoteWiki, $context->getUser() );

		$formDescriptor = $this->getFormDescriptor( $module, $wiki, $ceMW, $context, $remoteWiki, $config, $special, $filtered );

		$htmlForm = new $formClass( $formDescriptor, $context, $module );

		if ( !$ceMW ) {
			$htmlForm->suppressDefaultSubmit();
		}

		$htmlForm->setSubmitTextMsg( 'managewiki-save' );

		$htmlForm->setId( 'managewiki-form' );
		$htmlForm->setSubmitID( 'managewiki-submit' );

		$htmlForm->setSubmitCallback(
			function ( array $formData, HTMLForm $form ) use ( $module, $ceMW, $remoteWiki, $special, $filtered, $dbw, $wiki, $config ) {
				return $this->submitForm( $formData, $form, $module, $ceMW, $wiki, $remoteWiki, $dbw, $config, $special, $filtered );
			}
		);

		return $htmlForm;
	}

	protected function submitForm(
		array $formData,
		HTMLForm $form,
		string $module,
		bool $ceMW,
		string $dbName,
		RemoteWiki $wiki,
		DBConnRef $dbw,
		Config $config,
		string $special = '',
		string $filtered = ''
	) {
		$context = $form->getContext();
		$out = $context->getOutput();

		if ( !$ceMW ) {
			throw new MWException( "User '{$context->getUser()->getName()}' without 'managewiki' right tried to change wiki {$module}!" );
		}

		$form->getButtons();
		$formData['reason'] = $form->getField( 'reason' )->loadDataFromRequest( $form->getRequest() );

		$mwReturn = ManageWikiFormFactoryBuilder::submissionHandler( $formData, $form, $module, $dbName, $context, $wiki, $dbw, $config, $special, $filtered );

		if ( !empty( $mwReturn ) ) {
			$errorOut = [];
			foreach ( $mwReturn as $errors ) {
				foreach ( $errors as $msg => $params ) {
					$errorOut[] = wfMessage( $msg, $params )->escaped();
				}
			}

			$out->addHTML( Html::errorBox( 'The following errors occurred:<br>' . implode( '<br>', $errorOut ) ) );
			return null;
		}

		$out->addHTML( Html::successBox( wfMessage( 'managewiki-success' )->escaped() ) );
	}
}
