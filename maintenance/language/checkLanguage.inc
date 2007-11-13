<?php

# Blacklist some checks for some languages
$checkBlacklist = array(
#'code' => array( 'check1', 'check2' ... )
'hak' => array( 'plural' ),
'ja' => array( 'plural' ), // Does not use plural
'my' => array( 'chars' ), // Uses a lot zwnj
'tet' => array( 'plural' ),
'th' => array( 'plural' ),
'yue' => array( 'plural' ),
'zh-classical' => array( 'plural' ),
'zh-hans' => array( 'plural' ),
'zh-hant' => array( 'plural' ),
'gan' => array( 'plural' ),
);
