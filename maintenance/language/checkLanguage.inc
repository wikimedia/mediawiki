<?php

# Blacklist some checks for some languages
$checkBlacklist = array(
#'code'        => array( 'check1', 'check2' ... )
'gan'          => array( 'plural' ),
'hak'          => array( 'plural' ),
'ja'           => array( 'plural' ), // Does not use plural
'my'           => array( 'chars' ),  // Uses a lot zwnj
'tet'          => array( 'plural' ),
'th'           => array( 'plural' ),
'yue'          => array( 'plural' ),
'zh'           => array( 'plural' ),
'zh-classical' => array( 'plural' ),
'zh-cn'        => array( 'plural' ),
'zh-hans'      => array( 'plural' ),
'zh-hant'      => array( 'plural' ),
'zh-hk'        => array( 'plural' ),
'zh-sg'        => array( 'plural' ),
'zh-tw'        => array( 'plural' ),
'zh-yue'       => array( 'plural' ),
);
