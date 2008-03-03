<?php
# This contains basic configuration values that are needed
# for RunTests.php.

# Full path to the mediawiki source code you want to test
$IP = '/var/www/mediawiki-cvs';

# Now we add that path to the default include_path
ini_set('include_path',ini_get('include_path').':'.$IP);

# Some options needed for database testing
$testOptions = array(
    'mysql3' => array(
        'server' => null,
        'user' => null,
        'password' => null,
        'database' => null ),
    'mysql4' => array(
        'server' => null,
        'user' => null,
        'password' => null,
        'database' => null ),
    'postgres' => array(
        'server' => null,
        'user' => null,
        'password' => null,
        'database' => null ),
    );
?>
