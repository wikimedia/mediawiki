<?php

# Blacklist some checks for some languages
$checkBlacklist = array(
#'code' => array( 'check1', 'check2' ... )
'ja' => array( 'plural' ), // Does not use plural
'my' => array( 'chars' ), // Uses a lot zwnj
);
