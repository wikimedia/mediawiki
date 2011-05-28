<?php

/**
 * HttpRequest was renamed to MWHttpRequest in order
 * to prevent conflicts with PHP's HTTP extension
 * which also defines an HttpRequest class.
 * http://www.php.net/manual/en/class.httprequest.php
 *
 * This is for backwards compatibility.
 * @since 1.17
 */

class HttpRequest extends MWHttpRequest { }
