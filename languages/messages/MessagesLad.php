<?php
/** Ladino (Ladino)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author ILVI
 * @author Jewbask
 * @author Maor X
 * @author Menachem.Moreira
 * @author Remember the dot
 * @author Runningfridgesrule
 * @author Taichi
 * @author Universal Life
 * @author לערי ריינהארט
 */

$fallback = 'es';

$namespaceNames = [
	NS_MEDIA            => 'Medya',
	NS_SPECIAL          => 'Especial',
	NS_TALK             => 'Diskusyón',
	NS_USER             => 'Usador',
	NS_USER_TALK        => 'Messaje_de_Usador',
	NS_PROJECT_TALK     => 'Diskusyón_de_$1',
	NS_FILE             => 'Dosya',
	NS_FILE_TALK        => 'Diskusyón_de_Dosya',
	NS_MEDIAWIKI        => 'MedyaViki',
	NS_MEDIAWIKI_TALK   => 'Diskusyón_de_MedyaViki',
	NS_TEMPLATE         => 'Xablón',
	NS_TEMPLATE_TALK    => 'Diskusyón_de_Xablón',
	NS_HELP             => 'Ayudo',
	NS_HELP_TALK        => 'Diskusyón_de_Ayudo',
	NS_CATEGORY         => 'Kateggoría',
	NS_CATEGORY_TALK    => 'Diskusyón_de_Kateggoría',
];

$namespaceAliases = [
	// Backward compat. Fallbacks from 'es'.
	'Especial'            => NS_SPECIAL,
	'Discusión'           => NS_TALK,
	'Usuario'             => NS_USER,
	'Usuario_Discusión'   => NS_USER_TALK,
	'$1_Discusión'        => NS_PROJECT_TALK,
	'Archivo'             => NS_FILE,
	'Archivo_Discusión'   => NS_FILE_TALK,
	'MediaWiki_Discusión' => NS_MEDIAWIKI_TALK,
	'Plantilla'           => NS_TEMPLATE,
	'Plantilla_Discusión' => NS_TEMPLATE_TALK,
	'Ayuda'               => NS_HELP,
	'Ayuda_Discusión'     => NS_HELP_TALK,
	'Categoría'           => NS_CATEGORY,
	'Categoría_Discusión' => NS_CATEGORY_TALK,

	'Meddia'                   => NS_MEDIA,
	'Diskussión'               => NS_TALK,
	'Empleador'                => NS_USER,
	'Message_de_Empleador'     => NS_USER_TALK,
	'Diskussión_de_$1'         => NS_PROJECT_TALK,
	'Dossia'                   => NS_FILE,
	'Diskussión_de_Dossia'     => NS_FILE_TALK,
	'Diskussión_de_Xabblón'    => NS_MEDIAWIKI_TALK,
	'Xabblón'                  => NS_TEMPLATE,
	'Diskusyón_de_Xabblón'     => NS_TEMPLATE_TALK,
	'Diskussión_de_Ayudo'      => NS_HELP_TALK,
	'Katēggoría'               => NS_CATEGORY,
	'Diskusyón_de_Katēggoría'  => NS_CATEGORY_TALK,
];

// Remove Spanish gender aliases (T39090)
$namespaceGenderAliases = [];

$specialPageAliases = [
	'Activeusers'               => [ 'UsadoresAktivos' ],
	'Allmessages'               => [ 'TodosLosMessajes' ],
	'Allpages'                  => [ 'TodasLasHojas' ],
	'Ancientpages'              => [ 'HojasViejas' ],
	'Blankpage'                 => [ 'VaziarHoja' ],
	'Block'                     => [ 'Bloquear' ],
	'Booksources'               => [ 'FuentesDeLivros' ],
	'BrokenRedirects'           => [ 'DireksionesBozeadas' ],
	'Categories'                => [ 'Katēggorías' ],
	'ChangePassword'            => [ 'TrocarKóddiche' ],
	'ComparePages'              => [ 'KompararHojas' ],
	'Confirmemail'              => [ 'AverdadearLetral' ],
	'Contributions'             => [ 'Àjustamientos' ],
	'CreateAccount'             => [ 'KrîarCuento' ],
	'Deadendpages'              => [ 'HojasSinAtamientos' ],
	'DeletedContributions'      => [ 'AjustamientosEfassados' ],
	'DoubleRedirects'           => [ 'DireksionesDobles' ],
	'EditWatchlist'             => [ 'TrocarLista_de_Akavidamiento' ],
	'Emailuser'                 => [ 'MandarLetralUsador' ],
	'ExpandTemplates'           => [ 'AlargarXabblones' ],
	'Export'                    => [ 'AktarearAfuera' ],
	'Fewestrevisions'           => [ 'MankoEddisyones' ],
	'FileDuplicateSearch'       => [ 'BuscarDosyasDobles' ],
	'Filepath'                  => [ 'Pozisyón_de_dosya' ],
	'Import'                    => [ 'AktarearAriento' ],
	'Invalidateemail'           => [ 'DesverdadearLetral' ],
	'BlockList'                 => [ 'UsadoresBloqueados' ],
	'LinkSearch'                => [ 'Busqueda_de_atamientos' ],
	'Listadmins'                => [ 'ListaDeAdministradores' ],
	'Listbots'                  => [ 'ListaDeBotes' ],
	'Listfiles'                 => [ 'ListaDosyas' ],
	'Listgrouprights'           => [ 'DerechosGruposUsadores' ],
	'Listredirects'             => [ 'TodasLasDireksyones' ],
	'Listusers'                 => [ 'ListaUsadores' ],
	'Lockdb'                    => [ 'BloquearBasa_de_dados' ],
	'Log'                       => [ 'Rējistro' ],
	'Lonelypages'               => [ 'HojasHuérfanas' ],
	'Longpages'                 => [ 'HojasLargas' ],
	'MergeHistory'              => [ 'ÀjuntarÎstoria' ],
	'MIMEsearch'                => [ 'BuscarPorMIME' ],
	'Mostcategories'            => [ 'MásKateggorizadas' ],
	'Mostimages'                => [ 'DosyasLoMásMunchoLinkeadas' ],
	'Mostlinked'                => [ 'HojasLoMásMunchoLinkeadas' ],
	'Mostlinkedcategories'      => [ 'KatēggoríasMásUsadas' ],
	'Mostlinkedtemplates'       => [ 'XablonesMásUsados' ],
	'Mostrevisions'             => [ 'MásEddisyones' ],
	'Movepage'                  => [ 'TaxirearHoja' ],
	'Mycontributions'           => [ 'MisÀjustamientos' ],
	'Mypage'                    => [ 'MiHoja' ],
	'Mytalk'                    => [ 'MiDiskusyón' ],
	'Myuploads'                 => [ 'MisCargamientos' ],
	'Newimages'                 => [ 'MuevasDosyas' ],
	'Newpages'                  => [ 'HojasMuevas' ],
	'PasswordReset'             => [ 'Meter_á_zero_el_kóddiche' ],
	'PermanentLink'             => [ 'AtamientoPermanente' ],
	'Preferences'               => [ 'Preferencias' ],
	'Prefixindex'               => [ 'Fijhrist_de_prefiksos' ],
	'Protectedpages'            => [ 'HojasGuardadas' ],
	'Protectedtitles'           => [ 'TítůlosGuardados' ],
	'Randompage'                => [ 'KualunkeHoja' ],
	'Randomredirect'            => [ 'KualunkeDireksyón' ],
	'Recentchanges'             => [ 'TrocamientosFreskos' ],
	'Recentchangeslinked'       => [ 'TrocamientosÈnterassados' ],
	'Revisiondelete'            => [ 'EfassarRēvizyón' ],
	'Search'                    => [ 'Buscar' ],
	'Shortpages'                => [ 'HojasKurtas' ],
	'Specialpages'              => [ 'HojasEspesyales' ],
	'Statistics'                => [ 'Estatistika' ],
	'Tags'                      => [ 'Etiketas' ],
	'Unblock'                   => [ 'Desblokea' ],
	'Uncategorizedcategories'   => [ 'KatēggoríasNoKateggorizadas' ],
	'Uncategorizedimages'       => [ 'DosyasNoKateggorizadas' ],
	'Uncategorizedpages'        => [ 'HojasNoKateggorizadas' ],
	'Uncategorizedtemplates'    => [ 'XablonesNoKateggorizados' ],
	'Undelete'                  => [ 'TraerAtrás' ],
	'Unlockdb'                  => [ 'DesblokearBasa_de_dados' ],
	'Unusedcategories'          => [ 'KatēggoríasSinUso' ],
	'Unusedimages'              => [ 'DosyasSinUso' ],
	'Unusedtemplates'           => [ 'XablonesSinUso' ],
	'Unwatchedpages'            => [ 'HojasSinKudiadas' ],
	'Upload'                    => [ 'KargarDosya' ],
	'UploadStash'               => [ 'Muchedumbre_de_kargamientos' ],
	'Userlogin'                 => [ 'Entrada_del_usador' ],
	'Userlogout'                => [ 'Salida_del_usador' ],
	'Userrights'                => [ 'DerechosUsadores' ],
	'Version'                   => [ 'Versión' ],
	'Wantedcategories'          => [ 'KatēggoríasDemandadas' ],
	'Wantedfiles'               => [ 'DosyasDemandadas' ],
	'Wantedpages'               => [ 'HojasDemandadas' ],
	'Wantedtemplates'           => [ 'XablonesDemandados' ],
	'Watchlist'                 => [ 'Lista_de_eskojidos' ],
	'Whatlinkshere'             => [ 'LoKeSeAtaKonAkí' ],
	'Withoutinterwiki'          => [ 'SinIntervikis' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#DIRIJAR', '#DIRECCIÓN', '#REDIRECCIÓN', '#REDIRECCION', '#REDIRECT' ],
	'fullpagename'              => [ '1', 'NOMBREDEHOJACOMPLETA', 'NOMBREDEPÁGINACOMPLETA', 'NOMBREDEPAGINACOMPLETA', 'NOMBREDEPÁGINAENTERA', 'NOMBREDEPAGINAENTERA', 'NOMBRECOMPLETODEPÁGINA', 'NOMBRECOMPLETODEPAGINA', 'FULLPAGENAME' ],
	'subpagename'               => [ '1', 'NOMBREDEHOJICA', 'NOMBREDESUBPAGINA', 'NOMBREDESUBPÁGINA', 'SUBPAGENAME' ],
	'msg'                       => [ '0', 'MSJ:', 'MSG:' ],
	'img_left'                  => [ '1', 'cierda', 'izquierda', 'izda', 'izq', 'left' ],
	'img_none'                  => [ '1', 'dinguna', 'dinguno', 'ninguna', 'nada', 'no', 'ninguno', 'none' ],
];
