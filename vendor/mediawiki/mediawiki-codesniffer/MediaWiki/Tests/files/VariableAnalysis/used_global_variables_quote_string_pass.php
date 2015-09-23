<?php

function wfFooFoo() {
	global $wgSomething;
	$foo = "foo$wgSomething";
}
