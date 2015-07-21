<?php
/** Ligure (Ligure)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Dario vet
 * @author Dedee
 * @author Gastaz
 * @author Giromin Cangiaxo
 * @author Malafaya
 * @author Urhixidur
 * @author ZeneizeForesto
 */

$fallback = 'it';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Speçiale',
	NS_TALK             => 'Discûscion',
	NS_USER             => 'Utente',
	NS_USER_TALK        => 'Discûscioîn_ûtente',
	NS_PROJECT_TALK     => 'Discûscioîn_$1',
	NS_FILE             => 'Immaggine',
	NS_FILE_TALK        => 'Discûscioîn_immaggine',
	NS_MEDIAWIKI_TALK   => 'Discûscioîn_MediaWiki',
	NS_TEMPLATE_TALK    => 'Discûscioîn_template',
	NS_HELP             => 'Agiûtto',
	NS_HELP_TALK        => 'Discûscioîn_agiûtto',
	NS_CATEGORY         => 'Categorîa',
	NS_CATEGORY_TALK    => 'Discûscioîn_categorîa',
);

$namespaceAliases = array(
	'Speciale' => NS_SPECIAL,
	'Discussione' => NS_TALK,
	'Discussioni_utente' => NS_USER_TALK,
	'Discussioni_$1' => NS_PROJECT_TALK,
	'Immagine' => NS_FILE,
	'Discussioni_immagine' => NS_FILE_TALK,
	'Discussioni_MediaWiki' => NS_MEDIAWIKI_TALK,
	'Discussioni_template' => NS_TEMPLATE_TALK,
	'Aiuto' => NS_HELP,
	'Discussioni_aiuto' => NS_HELP_TALK,
	'Categoria' => NS_CATEGORY,
	'Discussioni_categoria' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Allmessages'               => array( 'Messaggi' ),
	'Allpages'                  => array( 'Tûtte e paggine' ),
	'Ancientpages'              => array( 'Paggine meno reçenti' ),
	'Block'                     => array( 'Blocca' ),
	'Booksources'               => array( 'RiçercaISBN' ),
	'Categories'                => array( 'Categorîe' ),
	'ChangePassword'            => array( 'Rimposta paròlla d\'ordine' ),
	'Confirmemail'              => array( 'Comferma l\'e-mail' ),
	'Contributions'             => array( 'Contribûti' ),
	'Deadendpages'              => array( 'Paggine sensa sciortîa' ),
	'Emailuser'                 => array( 'Mandighe \'n\'e-mail' ),
	'Export'                    => array( 'Esporta' ),
	'Fewestrevisions'           => array( 'Paggine con meno revixoîn' ),
	'Import'                    => array( 'Importa' ),
	'BlockList'                 => array( 'IP bloccæ' ),
	'Listadmins'                => array( 'Amministratoî' ),
	'Listbots'                  => array( 'Bot' ),
	'Listfiles'                 => array( 'Immaggini' ),
	'Listredirects'             => array( 'Rediression' ),
	'Listusers'                 => array( 'Utenti' ),
	'Lockdb'                    => array( 'BloccaDB' ),
	'Log'                       => array( 'Registri', 'Registro' ),
	'Lonelypages'               => array( 'Paggine orfane' ),
	'Longpages'                 => array( 'Paggine ciû longhe' ),
	'MIMEsearch'                => array( 'RiçercaMIME' ),
	'Mostcategories'            => array( 'Paggine con ciû categorîe' ),
	'Mostimages'                => array( 'Immaggini ciû domandæ' ),
	'Mostlinked'                => array( 'Paggine ciû domandæ' ),
	'Mostlinkedcategories'      => array( 'Categorîe ciû domandæ' ),
	'Mostlinkedtemplates'       => array( 'Template ciû domandæ' ),
	'Mostrevisions'             => array( 'Paggine con ciû revixoîn' ),
	'Movepage'                  => array( 'Sposta' ),
	'Mycontributions'           => array( 'Mæ Contribûti' ),
	'Mypage'                    => array( 'Mæ Paggina Utente' ),
	'Mytalk'                    => array( 'Mæ Discûscioîn' ),
	'Newimages'                 => array( 'Immaggini reçenti' ),
	'Newpages'                  => array( 'Paggine ciû reçenti' ),

	'Preferences'               => array( 'Preferense' ),
	'Prefixindex'               => array( 'Prefisci' ),
	'Protectedpages'            => array( 'Paggine protezûe' ),
	'Protectedtitles'           => array( 'Tittoli protezûi' ),
	'Randompage'                => array( 'Paggina a brettio' ),
	'Randomredirect'            => array( 'Rediression a brettio' ),
	'Recentchanges'             => array( 'Ûrtime modiffiche' ),
	'Recentchangeslinked'       => array( 'Modiffiche correlæ' ),
	'Revisiondelete'            => array( 'Scassa revixon' ),
	'Search'                    => array( 'Riçerca', 'Çerca' ),
	'Shortpages'                => array( 'Paggine ciû cûrte' ),
	'Specialpages'              => array( 'Paggine speçiali' ),
	'Statistics'                => array( 'Statistighe' ),
	'Uncategorizedcategories'   => array( 'Categorîe sensa categorîa' ),
	'Uncategorizedimages'       => array( 'Immaggini sensa categorîa' ),
	'Uncategorizedpages'        => array( 'Paggine sensa categorîa' ),
	'Uncategorizedtemplates'    => array( 'Template sensa categorîa' ),
	'Unlockdb'                  => array( 'SbloccaDB' ),
	'Unusedcategories'          => array( 'Categorîe sensa ûso' ),
	'Unusedimages'              => array( 'Immaggini sensa ûso' ),
	'Unusedtemplates'           => array( 'Template sensa ûso' ),
	'Unwatchedpages'            => array( 'Paggine no osservæ' ),
	'Upload'                    => array( 'Carrega' ),
	'Userlogin'                 => array( 'Intra', 'Registrate' ),
	'Userlogout'                => array( 'Sciorti' ),
	'Userrights'                => array( 'Permissi utente' ),
	'Version'                   => array( 'Verscion' ),
	'Wantedcategories'          => array( 'Categorîe domandæ' ),
	'Wantedpages'               => array( 'Paggine domandæ' ),
	'Watchlist'                 => array( 'Osservæ speçiali' ),
	'Whatlinkshere'             => array( 'Cose appunta chì' ),
	'Withoutinterwiki'          => array( 'Sensa Interwiki' ),
);

