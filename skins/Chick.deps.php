<?php
// This file exists to ensure that base classes are preloaded before
// Chick.php is compiled, working around a bug in the APC opcode
// cache on PHP 5, where cached code can break if the include order
// changed on a subsequent page view.

require_once('includes/SkinTemplate.php');
require_once('MonoBook.php');
?>