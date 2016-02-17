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

$namespaceNames = [
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
];

$namespaceAliases = [
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
];

$specialPageAliases = [
	'Allmessages'               => [ 'Messaggi' ],
	'Allpages'                  => [ 'Tûtte e paggine' ],
	'Ancientpages'              => [ 'Paggine meno reçenti' ],
	'Block'                     => [ 'Blocca' ],
	'Booksources'               => [ 'RiçercaISBN' ],
	'Categories'                => [ 'Categorîe' ],
	'ChangePassword'            => [ 'Rimposta paròlla d\'ordine' ],
	'Confirmemail'              => [ 'Comferma l\'e-mail' ],
	'Contributions'             => [ 'Contribûti' ],
	'Deadendpages'              => [ 'Paggine sensa sciortîa' ],
	'Emailuser'                 => [ 'Mandighe \'n\'e-mail' ],
	'Export'                    => [ 'Esporta' ],
	'Fewestrevisions'           => [ 'Paggine con meno revixoîn' ],
	'Import'                    => [ 'Importa' ],
	'BlockList'                 => [ 'IP bloccæ' ],
	'Listadmins'                => [ 'Amministratoî' ],
	'Listbots'                  => [ 'Bot' ],
	'Listfiles'                 => [ 'Immaggini' ],
	'Listredirects'             => [ 'Rediression' ],
	'Listusers'                 => [ 'Utenti' ],
	'Lockdb'                    => [ 'BloccaDB' ],
	'Log'                       => [ 'Registri', 'Registro' ],
	'Lonelypages'               => [ 'Paggine orfane' ],
	'Longpages'                 => [ 'Paggine ciû longhe' ],
	'MIMEsearch'                => [ 'RiçercaMIME' ],
	'Mostcategories'            => [ 'Paggine con ciû categorîe' ],
	'Mostimages'                => [ 'Immaggini ciû domandæ' ],
	'Mostlinked'                => [ 'Paggine ciû domandæ' ],
	'Mostlinkedcategories'      => [ 'Categorîe ciû domandæ' ],
	'Mostlinkedtemplates'       => [ 'Template ciû domandæ' ],
	'Mostrevisions'             => [ 'Paggine con ciû revixoîn' ],
	'Movepage'                  => [ 'Sposta' ],
	'Mycontributions'           => [ 'Mæ Contribûti' ],
	'Mypage'                    => [ 'Mæ Paggina Utente' ],
	'Mytalk'                    => [ 'Mæ Discûscioîn' ],
	'Newimages'                 => [ 'Immaggini reçenti' ],
	'Newpages'                  => [ 'Paggine ciû reçenti' ],
	'Preferences'               => [ 'Preferense' ],
	'Prefixindex'               => [ 'Prefisci' ],
	'Protectedpages'            => [ 'Paggine protezûe' ],
	'Protectedtitles'           => [ 'Tittoli protezûi' ],
	'Randompage'                => [ 'Paggina a brettio' ],
	'Randomredirect'            => [ 'Rediression a brettio' ],
	'Recentchanges'             => [ 'Ûrtime modiffiche' ],
	'Recentchangeslinked'       => [ 'Modiffiche correlæ' ],
	'Revisiondelete'            => [ 'Scassa revixon' ],
	'Search'                    => [ 'Riçerca', 'Çerca' ],
	'Shortpages'                => [ 'Paggine ciû cûrte' ],
	'Specialpages'              => [ 'Paggine speçiali' ],
	'Statistics'                => [ 'Statistighe' ],
	'Uncategorizedcategories'   => [ 'Categorîe sensa categorîa' ],
	'Uncategorizedimages'       => [ 'Immaggini sensa categorîa' ],
	'Uncategorizedpages'        => [ 'Paggine sensa categorîa' ],
	'Uncategorizedtemplates'    => [ 'Template sensa categorîa' ],
	'Unlockdb'                  => [ 'SbloccaDB' ],
	'Unusedcategories'          => [ 'Categorîe sensa ûso' ],
	'Unusedimages'              => [ 'Immaggini sensa ûso' ],
	'Unusedtemplates'           => [ 'Template sensa ûso' ],
	'Unwatchedpages'            => [ 'Paggine no osservæ' ],
	'Upload'                    => [ 'Carrega' ],
	'Userlogin'                 => [ 'Intra', 'Registrate' ],
	'Userlogout'                => [ 'Sciorti' ],
	'Userrights'                => [ 'Permissi utente' ],
	'Version'                   => [ 'Verscion' ],
	'Wantedcategories'          => [ 'Categorîe domandæ' ],
	'Wantedpages'               => [ 'Paggine domandæ' ],
	'Watchlist'                 => [ 'Osservæ speçiali' ],
	'Whatlinkshere'             => [ 'Cose appunta chì' ],
	'Withoutinterwiki'          => [ 'Sensa Interwiki' ],
];

