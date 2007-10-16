<?php
/**
 * West Flemish (West-Vlams)
 *
 * @addtogroup Language
 *
 * @author SPQRobin
*/

$fallback = 'nl';

$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Specioal',
	NS_MAIN           => '',
	NS_TALK           => 'Discuusje',
	NS_USER           => 'Gebruker',
	NS_USER_TALK      => 'Discuusje_gebruker',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => 'Discuusje_$1',
	NS_IMAGE          => 'Ofbeeldienge',
	NS_IMAGE_TALK     => 'Discuusje_ofbeeldienge',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'Discuusje_MediaWiki',
	NS_TEMPLATE       => 'Patrôon',
	NS_TEMPLATE_TALK  => 'Discuusje_patrôon',
	NS_HELP           => 'Ulpe',
	NS_HELP_TALK      => 'Discuusje_ulpe',
	NS_CATEGORY       => 'Categorie',
	NS_CATEGORY_TALK  => 'Discuusje_categorie',
);

$messages = array(
# Dates
'tuesday' => 'diensdag',

'history_short' => 'Geschiedenisse',
'edit'          => "Bewerk'n",
'delete'        => 'Wegdoen',
'unprotect'     => 'beveiliginge wegdoen',
'toolbox'       => 'Ulpmiddeln',

# Upload
'upload'            => 'Bestand toevoegn',
'uploadbtn'         => 'Bestand toevoegn',
'uploadnologin'     => 'Ge zyt nie angemeld',
'uploadlog'         => 'logboek upgeloade bestandn',
'uploadlogpage'     => 'Logboek upgeloade bestandn',
'uploadlogpagetext' => 'Hier stoa e lyste met de mêest recente upgeloade bestandn.',
'uploadedfiles'     => 'Upgeloade bestandn',
'uploadedimage'     => '"[[$1]]" upgeload',

# Unwatched pages
'unwatchedpages' => "Pagina's die ip niemands volglyste stoan",

# Watchlist
'unwatch' => 'Nie volgn',

# Displayed when you click the "watch" button and it's in the process of watching
'unwatching' => 'Stoppn me volgn...',

# Undelete
'undelete'               => 'Weggedoane bloadn bekykn',
'undeletepage'           => 'Weggedoane bloadn erstelln of bekykn',
'undeletehistorynoadmin' => "'t Artikel is weggedoan. De reden davôorn ku je zien in de soamnvattienge ieronder, tôpe me uutleg over wie dat 't blad bewerkt èt vôorn dat weggedoan es gewist. <br>Den tekst van die weggedoane versies kan allêene door [[Wikipedia:Sysop|sysops]] gelezen wordn.",
'undeletebtn'            => 'Erstelln',
'undeletedarticle'       => '"[[$1]]" ersteld',
'undeletedfiles'         => '{{PLURAL:$1|1 bestand|$1 bestandn}} ersteld',

# Contributions
'uctop' => ' (latste veranderienge)',

# Move page
'delete_and_move' => 'Wegdoen en ernoemn',

# Tooltip help for the actions
'tooltip-n-mainpage' => "Noar 't voorblad goane",

);
