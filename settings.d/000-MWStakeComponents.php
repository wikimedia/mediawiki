<?php

/**
 * This is required to ensure that the MWStakeComponents have a chance to register their services
 * via `$wServiceWiringFiles`_before_ any other extension can instantiate the `MediaWikiServices`
 * service container.
 *
 * Background: Even though it should not be done, some extensions (e.g. "Extension:EmbedVideo")
 * will make a call to `MediaWikiServices::getInstance` at "manifest.callback" time ( = "on 
 * registration" in `$IP/includes/Setup.php`)
 *
 * ERM23675
 */
mwsInitComponents();
