== Details==

Automated Selenium test scripts written for MediaWiki Installer is available at svn.wikimedia.org/svnroot/mediawiki/trunk/phase3/tests/selenium/installer.
Detailed test cases available at http://www.mediawiki.org/wiki/New_installer/Test_plan.

Version : MediaWiki 1.18alpha
Date 	: 27/12/2010

== Running tests ==

Test cases can be run independently or can run all the test cases using MediaWikiInstallerTestSuite.php within PHPUnit/Selenium.


== Dependencies == 

MediaWikiInstallationConfig.php

Value of the 'DB_NAME_PREFIX' should be replace with the database name prefix. Several DB instances will get created to cover different installation scenarios starting with the above prefix.
You need to change the value of the 'DB_NAME_PREFIX' in MediaWikiInstallationConfig everytime you planned to
run the tests.
'DIRECTORY_NAME', 'PORT' and the 'HOST_NAME' should be replaced with your local values.
You may specify the test browser you wish to run the test using 'TEST_BROWSER'.  Default browser is Firefox.

Note : MediaWikiInstallerTestSuite.php has no dependency on 'Selenium' test framework.


== Known problems ==

If you run the MediaWikiInstallerTestSuite.php twice without changing the name of the database, the second run should be falied.
(Please read the more information on how to change the database name which is avaialable under 'Dependencies' section)


