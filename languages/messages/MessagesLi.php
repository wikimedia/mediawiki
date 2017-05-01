<?php
/** Limburgish (Limburgs)
 *
 * To improve a translation please visit https://translatewiki.net
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

$namespaceNames = [
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
];

$namespaceAliases = [
	'Kategorie'           => NS_CATEGORY,
	'Euverlèk_kategorie'  => NS_CATEGORY_TALK,
	'Aafbeilding'         => NS_FILE,
	'Euverlèk_afbeelding' => NS_FILE_TALK,
];

$specialPageAliases = [
	'Activeusers'               => [ 'Aktieve_gebroekers' ],
	'Allmessages'               => [ 'Alle_berichte' ],
	'Allpages'                  => [ 'Alle_pagina\'s' ],
	'Ancientpages'              => [ 'Audste_pagina\'s' ],
	'Blankpage'                 => [ 'Laeg_pagina\'s' ],
	'Block'                     => [ 'Blokkere' ],
	'Booksources'               => [ 'Bookwinkele' ],
	'BrokenRedirects'           => [ 'Gebraoke_doorverwiezinge' ],
	'Categories'                => [ 'Categorieë' ],
	'ChangePassword'            => [ 'Wachwaord_opnuuj_insjtèlle' ],
	'Confirmemail'              => [ 'E-mail_bevestige' ],
	'Contributions'             => [ 'Biedrage' ],
	'CreateAccount'             => [ 'Gebroeker_aonmake' ],
	'Deadendpages'              => [ 'Doedloupende_pagina\'s' ],
	'DeletedContributions'      => [ 'Eweggesjafde_biedrage' ],
	'DoubleRedirects'           => [ 'Dobbel_doorverwiezinge' ],
	'Emailuser'                 => [ 'E-maile' ],
	'Export'                    => [ 'Exportere' ],
	'Fewestrevisions'           => [ 'Winnigs_bewirkde_pagina\'s' ],
	'FileDuplicateSearch'       => [ 'Besjtandsduplicate_zeuke' ],
	'Filepath'                  => [ 'Besjtandspaad' ],
	'Import'                    => [ 'Importere' ],
	'Invalidateemail'           => [ 'E-mail_annulere' ],
	'BlockList'                 => [ 'Geblokkeerde_IP\'s' ],
	'LinkSearch'                => [ 'Verwiezinge_zeuke' ],
	'Listadmins'                => [ 'Systeemwèrkers' ],
	'Listbots'                  => [ 'Botlies' ],
	'Listfiles'                 => [ 'Plaetjes' ],
	'Listgrouprights'           => [ 'Groepsrechte_weergaeve' ],
	'Listredirects'             => [ 'Doorverwiezinge' ],
	'Listusers'                 => [ 'Gebroekers' ],
	'Lockdb'                    => [ 'DB_blokkere' ],
	'Log'                       => [ 'Logbeuk', 'Logbook' ],
	'Lonelypages'               => [ 'Weispagina\'s' ],
	'Longpages'                 => [ 'Lang_pagina\'s' ],
	'MergeHistory'              => [ 'Historie_sameveuge' ],
	'MIMEsearch'                => [ 'MIME_zeuke' ],
	'Mostcategories'            => [ 'Meiste_categorieë' ],
	'Mostimages'                => [ 'Meis_gebroekde_plaetjes' ],
	'Mostlinked'                => [ 'Meis_gelinkde_pazjena\'s' ],
	'Mostlinkedcategories'      => [ 'Meis_gelinkde_categorieë' ],
	'Mostlinkedtemplates'       => [ 'Meis_gebroekde_sjablone' ],
	'Mostrevisions'             => [ 'Meis_bewirkde_pagina\'s' ],
	'Movepage'                  => [ 'Verplaatse' ],
	'Mycontributions'           => [ 'Mien_biedrage' ],
	'Mypage'                    => [ 'Mien_pagina\'s' ],
	'Mytalk'                    => [ 'Mien_euverlèk' ],
	'Newimages'                 => [ 'Nuuj_plaetjes' ],
	'Newpages'                  => [ 'Nuuj_pagina\'s' ],
	'Preferences'               => [ 'Veurkäöre' ],
	'Prefixindex'               => [ 'Alle_artikele' ],
	'Protectedpages'            => [ 'Beveiligde_pagina\'s' ],
	'Protectedtitles'           => [ 'Beveiligde_titels' ],
	'Randompage'                => [ 'Willekäörig_artikel' ],
	'Randomredirect'            => [ 'Willekäörige_doorverwiezing' ],
	'Recentchanges'             => [ 'Lètste_verangeringe' ],
	'Recentchangeslinked'       => [ 'Verwante_verangeringe' ],
	'Revisiondelete'            => [ 'Versie_ewegsjaffe' ],
	'Search'                    => [ 'Zeuke' ],
	'Shortpages'                => [ 'Kórte_pagina\'s' ],
	'Specialpages'              => [ 'Speciaal_pagina\'s' ],
	'Statistics'                => [ 'Sjtattestieke' ],
	'Tags'                      => [ 'Labels' ],
	'Uncategorizedcategories'   => [ 'Óngecategoriseerde_categorieë' ],
	'Uncategorizedimages'       => [ 'Óngecategoriseerde_plaetjes' ],
	'Uncategorizedpages'        => [ 'Óngecategoriseerde_pagina\'s' ],
	'Uncategorizedtemplates'    => [ 'Óngecategorisserde_sjablone' ],
	'Undelete'                  => [ 'Hersjtèlle' ],
	'Unlockdb'                  => [ 'DB_vriegaeve' ],
	'Unusedcategories'          => [ 'Óngebroekde_categorieë' ],
	'Unusedimages'              => [ 'Óngebroekde_plaetjes' ],
	'Unusedtemplates'           => [ 'Óngebroekde_sjablone' ],
	'Unwatchedpages'            => [ 'Neet-gevolgde_pagina\'s' ],
	'Upload'                    => [ 'Uploade' ],
	'Userlogin'                 => [ 'Aanmelje' ],
	'Userlogout'                => [ 'Aafmelje' ],
	'Userrights'                => [ 'Gebroekersrechte' ],
	'Version'                   => [ 'Versie' ],
	'Wantedcategories'          => [ 'Gewunsjde_categorieë' ],
	'Wantedfiles'               => [ 'Gevraogde_besjtande' ],
	'Wantedpages'               => [ 'Gewunsjde_pagina\'s' ],
	'Wantedtemplates'           => [ 'Gevraogde_sjablone' ],
	'Watchlist'                 => [ 'Volglies' ],
	'Whatlinkshere'             => [ 'Verwiezinge_nao_hie' ],
	'Withoutinterwiki'          => [ 'Gein_interwiki' ],
];

$dateFormats = [
	'mdy time' => 'H:i',
	'mdy date' => 'M j, Y',
	'mdy both' => 'M j, Y H:i',

	'dmy time' => 'H:i',
	'dmy date' => 'j M Y',
	'dmy both' => 'j M Y H:i',

	'ymd time' => 'H:i',
	'ymd date' => 'Y M j',
	'ymd both' => 'Y M j H:i',
];
