<?php
/**
 * Change the password of a given user
 *
 * @addtogroup Maintenance
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

class ChangePassword {
	var $dbw;
	var $user, $password;

	function ChangePassword( $user, $password ) {
		$this->user = User::newFromName( $user );
		$this->password = $password;

		$this->dbw = wfGetDB( DB_MASTER );
	}

	function main() {
		$fname = 'ChangePassword::main';

		$this->dbw->update( 'user',
			array(
				'user_password' => wfEncryptPassword( $this->user->getID(), $this->password )
			),
			array(
				'user_id' => $this->user->getID()
			),
			$fname
		);
	}
}

$optionsWithArgs = array( 'user', 'password' );
require_once 'commandLine.inc';

if( in_array( '--help', $argv ) )
	wfDie(
		"Usage: php changePassword.php [--user=user --password=password | --help]\n" .
		"\toptions:\n" .
		"\t\t--help\tshow this message\n" .
		"\t\t--user\tthe username to operate on\n" .
		"\t\t--password\tthe password to use\n"
	);

$cp = new ChangePassword( @$options['user'], @$options['password'] );
$cp->main();
?>
