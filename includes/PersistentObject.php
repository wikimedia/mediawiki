<?php
/**
 * Sometimes one wants to make an extension that defines a class that one wants
 * to backreference somewhere else in the code, doing something like:
 * <code>
 * class Extension { ... }
 * function myExtension() { new Extension; }
 * </class>
 *
 * Won't work because PHP will destroy any reference to the initialized
 * extension when the function goes out of scope, furthermore one might want to
 * use some functions in the Extension class that won't exist by the time
 * extensions get parsed which would mean lots of nasty workarounds to get
 * around initialization and reference issues.
 *
 * This class allows one to write hir extension as:
 *
 * <code>
 * function myExtension() {
 *	class Extension { ... }
 *	new PersistentObject( new Extension );
 * }
 * </code>
 *
 * The class will then not get parsed until everything is properly initialized
 * and references to it won't get destroyed meaning that it's possible to do
 * something like:
 *
 * <code>
 * $wgParser->setHook( 'tag' , array( &$this, 'tagFunc' ) );
 * </code>
 *
 * And have it work as expected
 *
 * @package MediaWiki
 * @subpackage Extensions
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

$wgPersistentObjects = array();

class PersistentObject {
	function PersistentObject( &$obj ) {
		$wgPersistentObjects[] = $obj;
	}
}
?>
