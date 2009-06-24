<?php
/**
 * Communications protocol...
 *
 * @file
 * @ingroup Maintenance
 */

require_once( "Maintenance.php" );

class FetchText extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Fetch the revision text from an old_id";
	}

	public function execute() {
		$db = wfGetDB( DB_SLAVE );
		$stdin = $this->getStdin();
		while( !feof( $stdin ) ) {
			$line = fgets( $stdin );
			if( $line === false ) {
				// We appear to have lost contact...
				break;
			}
			$textId = intval( $line );
			$text = $this->doGetText( $db, $textId );
			$this->output( strlen( $text ) . "\n". $text );
		}
	}
	
	/**
 	 * May throw a database error if, say, the server dies during query.
	 * @param $db Database object
	 * @param $id int The old_id
	 * @return String
 	 */
	private function doGetText( $db, $id ) {
		$id = intval( $id );
		$row = $db->selectRow( 'text',
			array( 'old_text', 'old_flags' ),
			array( 'old_id' => $id ),
			'TextPassDumper::getText' );
		$text = Revision::getRevisionText( $row );
		if( $text === false ) {
			return false;
		}
		return $text;
	}
}

$maintClass = "FetchText";
require_once( DO_MAINTENANCE );
