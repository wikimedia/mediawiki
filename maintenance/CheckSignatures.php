<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Parser\ParserOptions;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to list users with invalid signatures.
 *
 * @ingroup Maintenance
 */
class CheckSignatures extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'List users with invalid signatures' );
		$this->setBatchSize( 1000 );
	}

	public function execute() {
		$dbr = $this->getReplicaDB();
		$userFactory = $this->getServiceContainer()->getUserIdentityLookup();
		$userOptions = $this->getServiceContainer()->getUserOptionsLookup();
		$signatureValidatorFactory = $this->getServiceContainer()->getSignatureValidatorFactory();
		$contentLanguage = $this->getServiceContainer()->getContentLanguage();

		$count = 0;
		$maxUserId = 0;
		do {
			// List users who may have a signature that needs validation
			$res = $dbr->newSelectQueryBuilder()
				->from( 'user_properties' )
				->select( 'up_user' )
				->where( [ 'up_property' => 'fancysig' ] )
				->andWhere( $dbr->expr( 'up_user', '>', $maxUserId ) )
				->orderBy( [ 'up_property', 'up_user' ] )
				->limit( $this->getBatchSize() )
				->caller( __METHOD__ )
				->fetchResultSet();

			foreach ( $res as $row ) {
				// Double-check effective preferences and check validation
				$user = $userFactory->getUserIdentityByUserId( $row->up_user );
				if ( !$user ) {
					continue;
				}
				$signature = $userOptions->getOption( $user, 'nickname' );
				$useFancySig = $userOptions->getBoolOption( $user, 'fancysig' );
				if ( $useFancySig && $signature !== '' ) {
					$parserOpts = new ParserOptions( $user, $contentLanguage );
					$validator = $signatureValidatorFactory->newSignatureValidator( $user, null, $parserOpts );
					$signatureErrors = $validator->validateSignature( $signature );
					if ( $signatureErrors ) {
						$count++;
						$this->output( $user->getName() . "\n" );
					}
				}

				$maxUserId = $row->up_user;
			}
		} while ( $res->numRows() );

		$this->output( "-- $count invalid signatures --\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = CheckSignatures::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
