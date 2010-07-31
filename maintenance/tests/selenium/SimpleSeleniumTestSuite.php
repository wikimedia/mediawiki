 <?php
 
require_once(dirname( __FILE__ ) . '/SimpleSeleniumTestCase.php');

class SimpleSeleniumTestSuite extends SeleniumTestSuite
{
	public function __construct( $name = 'Basic selenium test suite') {
		parent::__construct( $name );
	}

	public function addTests() {
		$test = new SimpleSeleniumTestCase();
		parent::addTest( $test );
	}
}
