<?php
/** Limburgish (Limburgs)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Aelske
 * @author Benopat
 * @author Cicero
 * @author Geitost
 * @author Kaganer
 * @author Matthias
 * @author Ooswesthoesbes
 * @author Pahles
 * @author Purodha
 * @author Reedy
 * @author Remember the dot
 * @author Tibor
 * @author לערי ריינהארט
 */

$fallback = 'nl';


$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Speciaal',
	NS_TALK             => 'Euverlèk',
	NS_USER             => 'Gebroeker',
	NS_USER_TALK        => 'Euverlèk_gebroeker',
	NS_PROJECT_TALK     => 'Euverlèk_$1',
	NS_FILE             => 'Plaetje',
	NS_FILE_TALK        => 'Euverlèk_plaetje',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Euverlèk_MediaWiki',
	NS_TEMPLATE         => 'Sjabloon',
	NS_TEMPLATE_TALK    => 'Euverlèk_sjabloon',
	NS_HELP             => 'Help',
	NS_HELP_TALK        => 'Euverlèk_help',
	NS_CATEGORY         => 'Categorie',
	NS_CATEGORY_TALK    => 'Euverlèk_categorie',
);

$namespaceAliases = array(
	'Kategorie'           => NS_CATEGORY,
	'Euverlèk_kategorie'  => NS_CATEGORY_TALK,
	'Aafbeilding'         => NS_FILE,
	'Euverlèk_afbeelding' => NS_FILE_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'Aktieve_gebroekers' ),
	'Allmessages'               => array( 'Alle_berichte' ),
	'Allpages'                  => array( 'Alle_pagina\'s' ),
	'Ancientpages'              => array( 'Audste_pagina\'s' ),
	'Blankpage'                 => array( 'Laeg_pagina\'s' ),
	'Block'                     => array( 'Blokkere' ),
	'Booksources'               => array( 'Bookwinkele' ),
	'BrokenRedirects'           => array( 'Gebraoke_doorverwiezinge' ),
	'Categories'                => array( 'Categorieë' ),
	'ChangePassword'            => array( 'Wachwaord_opnuuj_insjtèlle' ),
	'Confirmemail'              => array( 'E-mail_bevestige' ),
	'Contributions'             => array( 'Biedrage' ),
	'CreateAccount'             => array( 'Gebroeker_aonmake' ),
	'Deadendpages'              => array( 'Doedloupende_pagina\'s' ),
	'DeletedContributions'      => array( 'Eweggesjafde_biedrage' ),
	'DoubleRedirects'           => array( 'Dobbel_doorverwiezinge' ),
	'Emailuser'                 => array( 'E-maile' ),
	'Export'                    => array( 'Exportere' ),
	'Fewestrevisions'           => array( 'Winnigs_bewirkde_pagina\'s' ),
	'FileDuplicateSearch'       => array( 'Besjtandsduplicate_zeuke' ),
	'Filepath'                  => array( 'Besjtandspaad' ),
	'Import'                    => array( 'Importere' ),
	'Invalidateemail'           => array( 'E-mail_annulere' ),
	'BlockList'                 => array( 'Geblokkeerde_IP\'s' ),
	'LinkSearch'                => array( 'Verwiezinge_zeuke' ),
	'Listadmins'                => array( 'Systeemwèrkers' ),
	'Listbots'                  => array( 'Botlies' ),
	'Listfiles'                 => array( 'Plaetjes' ),
	'Listgrouprights'           => array( 'Groepsrechte_weergaeve' ),
	'Listredirects'             => array( 'Doorverwiezinge' ),
	'Listusers'                 => array( 'Gebroekers' ),
	'Lockdb'                    => array( 'DB_blokkere' ),
	'Log'                       => array( 'Logbeuk', 'Logbook' ),
	'Lonelypages'               => array( 'Weispagina\'s' ),
	'Longpages'                 => array( 'Lang_pagina\'s' ),
	'MergeHistory'              => array( 'Historie_sameveuge' ),
	'MIMEsearch'                => array( 'MIME_zeuke' ),
	'Mostcategories'            => array( 'Meiste_categorieë' ),
	'Mostimages'                => array( 'Meis_gebroekde_plaetjes' ),
	'Mostlinked'                => array( 'Meis_gelinkde_pazjena\'s' ),
	'Mostlinkedcategories'      => array( 'Meis_gelinkde_categorieë' ),
	'Mostlinkedtemplates'       => array( 'Meis_gebroekde_sjablone' ),
	'Mostrevisions'             => array( 'Meis_bewirkde_pagina\'s' ),
	'Movepage'                  => array( 'Verplaatse' ),
	'Mycontributions'           => array( 'Mien_biedrage' ),
	'Mypage'                    => array( 'Mien_pagina\'s' ),
	'Mytalk'                    => array( 'Mien_euverlèk' ),
	'Newimages'                 => array( 'Nuuj_plaetjes' ),
	'Newpages'                  => array( 'Nuuj_pagina\'s' ),
	'Popularpages'              => array( 'Populair_pagina\'s' ),
	'Preferences'               => array( 'Veurkäöre' ),
	'Prefixindex'               => array( 'Alle_artikele' ),
	'Protectedpages'            => array( 'Beveiligde_pagina\'s' ),
	'Protectedtitles'           => array( 'Beveiligde_titels' ),
	'Randompage'                => array( 'Willekäörig_artikel' ),
	'Randomredirect'            => array( 'Willekäörige_doorverwiezing' ),
	'Recentchanges'             => array( 'Lètste_verangeringe' ),
	'Recentchangeslinked'       => array( 'Verwante_verangeringe' ),
	'Revisiondelete'            => array( 'Versie_ewegsjaffe' ),
	'Search'                    => array( 'Zeuke' ),
	'Shortpages'                => array( 'Kórte_pagina\'s' ),
	'Specialpages'              => array( 'Speciaal_pagina\'s' ),
	'Statistics'                => array( 'Sjtattestieke' ),
	'Tags'                      => array( 'Labels' ),
	'Uncategorizedcategories'   => array( 'Óngecategoriseerde_categorieë' ),
	'Uncategorizedimages'       => array( 'Óngecategoriseerde_plaetjes' ),
	'Uncategorizedpages'        => array( 'Óngecategoriseerde_pagina\'s' ),
	'Uncategorizedtemplates'    => array( 'Óngecategorisserde_sjablone' ),
	'Undelete'                  => array( 'Hersjtèlle' ),
	'Unlockdb'                  => array( 'DB_vriegaeve' ),
	'Unusedcategories'          => array( 'Óngebroekde_categorieë' ),
	'Unusedimages'              => array( 'Óngebroekde_plaetjes' ),
	'Unusedtemplates'           => array( 'Óngebroekde_sjablone' ),
	'Unwatchedpages'            => array( 'Neet-gevolgde_pagina\'s' ),
	'Upload'                    => array( 'Uploade' ),
	'Userlogin'                 => array( 'Aanmelje' ),
	'Userlogout'                => array( 'Aafmelje' ),
	'Userrights'                => array( 'Gebroekersrechte' ),
	'Version'                   => array( 'Versie' ),
	'Wantedcategories'          => array( 'Gewunsjde_categorieë' ),
	'Wantedfiles'               => array( 'Gevraogde_besjtande' ),
	'Wantedpages'               => array( 'Gewunsjde_pagina\'s' ),
	'Wantedtemplates'           => array( 'Gevraogde_sjablone' ),
	'Watchlist'                 => array( 'Volglies' ),
	'Whatlinkshere'             => array( 'Verwiezinge_nao_hie' ),
	'Withoutinterwiki'          => array( 'Gein_interwiki' ),
);

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'M j, Y',
	'mdy both' => 'M j, Y H:i',

	'dmy time' => 'H:i',
	'dmy date' => 'j M Y',
	'dmy both' => 'j M Y H:i',

	'ymd time' => 'H:i',
	'ymd date' => 'Y M j',
	'ymd both' => 'Y M j H:i',
);

