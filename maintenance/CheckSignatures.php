<?php
/**
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
 * @ingroup Maintenance
 */

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

$maintClass = CheckSignatures::class;
