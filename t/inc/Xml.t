#!/usr/bin/env php
<?php

require 't/Test.php';

plan( 8 );

require_ok( 'includes/Sanitizer.php' );
require_ok( 'includes/Xml.php' );

#
# element
#

cmp_ok(
	Xml::element( 'element', null, null ),
	'==',
	'<element>',
	'Opening element with no attributes'
);

cmp_ok(
	Xml::element( 'element', null, '' ),
	'==',
	'<element />',
	'Terminated empty element'
);

cmp_ok(
	Xml::element( 'element', null, 'hello <there> you & you' ),
	'==',
	'<element>hello &lt;there&gt; you &amp; you</element>',
	'Element with no attributes and content that needs escaping'
);

cmp_ok(
	Xml::element( 'element', array( 'key' => 'value', '<>' => '<>' ), null ),
	'==',
	'<element key="value" <>="&lt;&gt;">',
	'Element attributes, keys are not escaped'
);

#
# open/close element
#

cmp_ok(
	Xml::openElement( 'element', array( 'k' => 'v' ) ),
	'==',
	'<element k="v">',
	'openElement() shortcut'
);

cmp_ok( Xml::closeElement( 'element' ), '==', '</element>', 'closeElement() shortcut' );

/* vim: set filetype=php: */
