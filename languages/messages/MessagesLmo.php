<?php
/** lumbaart (lumbaart)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Amgine
 * @author Clamengh
 * @author Dakrismeno
 * @author DracoRoboter
 * @author Flavio05
 * @author GatoSelvadego
 * @author Geitost
 * @author Insübrich
 * @author Kemmótar
 * @author Malafaya
 * @author Reedy
 * @author Remulazz
 * @author SabineCretella
 * @author Snowdog
 * @author Sprüngli
 */

$fallback = 'it';

$namespaceNames = array(
	NS_SPECIAL          => 'Special',
	NS_TALK             => 'Ciciarada',
	NS_USER             => 'Druvadur',
	NS_USER_TALK        => 'Ciciarada_Druvadur',
	NS_PROJECT_TALK     => '$1_Ciciarada',
	NS_FILE             => 'Archivi',
	NS_FILE_TALK        => 'Ciciarada_Archivi',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Ciciarada_MediaWiki',
	NS_TEMPLATE         => 'Mudel',
	NS_TEMPLATE_TALK    => 'Ciciarada_Mudel',
	NS_HELP             => 'Jüt',
	NS_HELP_TALK        => 'Ciciarada_Jüt',
	NS_CATEGORY         => 'Categuria',
	NS_CATEGORY_TALK    => 'Ciciarada_Categuria',
);

$namespaceAliases = array(
	'Speciale'              => NS_SPECIAL,
	'Discussione'           => NS_TALK,
	'Utente'                => NS_USER,
	'Druvat'                => NS_USER,
	'Dovrat'                => NS_USER,
	'Discussioni_utente'    => NS_USER_TALK,
	'Ciciarada_Druvat'      => NS_USER_TALK,
	'Ciciarada_Dovrat'      => NS_USER_TALK,
	'Discussioni_$1'        => NS_PROJECT_TALK,
	'Immagine'              => NS_FILE,
	'Discussioni_file'      => NS_FILE_TALK,
	'Discussioni_immagine'  => NS_FILE_TALK,
	'Discussioni_MediaWiki' => NS_MEDIAWIKI_TALK,
	'Model'                 => NS_TEMPLATE,
	'Discussioni_template'  => NS_TEMPLATE_TALK,
	'Ciciarada_Model'       => NS_TEMPLATE_TALK,
	'Aiuto'                 => NS_HELP,
	'Aida'                  => NS_HELP,
	'Discussioni_aiuto'     => NS_HELP_TALK,
	'Ciciarada_Aida'        => NS_HELP_TALK,
	'Categoria'             => NS_CATEGORY,
	'Discussioni_categoria' => NS_CATEGORY_TALK,
	'Ciciarada_Categoria'   => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Allmessages'               => array( 'Messagg' ),
	'BrokenRedirects'           => array( 'RedirezionS-cepada' ),
	'Categories'                => array( 'Categurij' ),
	'CreateAccount'             => array( 'CreaCünt' ),
	'DoubleRedirects'           => array( 'RedirezionDubia' ),
	'Listadmins'                => array( 'ListaAministradur' ),
	'Listfiles'                 => array( 'Imagin' ),
	'Listgrouprights'           => array( 'Lista_di_dirit_di_grüp' ),
	'Listusers'                 => array( 'Dupradur' ),
	'Lonelypages'               => array( 'PaginnDaPerLur' ),
	'Newimages'                 => array( 'ImaginNöv' ),
	'Preferences'               => array( 'Preferenz' ),
	'Randompage'                => array( 'PaginaAzardada' ),
	'Recentchanges'             => array( 'CambiamentRecent' ),
	'Recentchangeslinked'       => array( 'MudifeghCulegaa' ),
	'Specialpages'              => array( 'PaginnSpecial' ),
	'Statistics'                => array( 'Statìstegh' ),
	'Uncategorizedpages'        => array( 'PaginnMingaCategurizaa' ),
	'Upload'                    => array( 'CaregaSü' ),
	'Userlogin'                 => array( 'VenaDenter' ),
	'Userlogout'                => array( 'VaFö' ),
	'Watchlist'                 => array( 'SutOeugg' ),
);

$magicWords = array(
	'img_right'                 => array( '1', 'drita', 'destra', 'right' ),
	'img_left'                  => array( '1', 'manzína', 'sinistra', 'left' ),
	'img_none'                  => array( '1', 'nissön', 'nessuno', 'none' ),
	'sitename'                  => array( '1', 'NUMSIT', 'NOMESITO', 'SITENAME' ),
);

