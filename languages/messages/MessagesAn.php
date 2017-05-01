<?php
/** Aragonese (aragonés)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback = 'es';

$namespaceNames = [
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Especial',
	NS_TALK             => 'Descusión',
	NS_USER             => 'Usuario',
	NS_USER_TALK        => 'Descusión_usuario',
	NS_PROJECT_TALK     => 'Descusión_$1',
	NS_FILE             => 'Imachen',
	NS_FILE_TALK        => 'Descusión_imachen',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Descusión_MediaWiki',
	NS_TEMPLATE         => 'Plantilla',
	NS_TEMPLATE_TALK    => 'Descusión_plantilla',
	NS_HELP             => 'Aduya',
	NS_HELP_TALK        => 'Descusión_aduya',
	NS_CATEGORY         => 'Categoría',
	NS_CATEGORY_TALK    => 'Descusión_categoría',
];

$namespaceAliases = [
	'Espezial' => NS_SPECIAL,
];

// T113890: Setting $namespaceGenderAliases for Aragonese (an)
$namespaceGenderAliases = [
	NS_USER => [ 'male' => 'Usuario', 'female' => 'Usuaria' ],
	NS_USER_TALK => [ 'male' => 'Descusión_usuario', 'female' => 'Descusión_usuaria' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#ENDRECERA', '#REENDRECERA', '#REDIRECCIÓN', '#REDIRECCION', '#REDIRECT' ],
	'namespace'                 => [ '1', 'ESPACIODENOMBRES', 'ESPACIODENOMBRE', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'ESPACIODENOMBRESE', 'ESPACIODENOMBREC', 'NAMESPACEE' ],
	'img_right'                 => [ '1', 'dreita', 'derecha', 'dcha', 'der', 'right' ],
	'img_left'                  => [ '1', 'cucha', 'izquierda', 'zurda', 'izda', 'izq', 'left' ],
	'ns'                        => [ '0', 'EN:', 'EDN:', 'NS:' ],
	'displaytitle'              => [ '1', 'TÍTOL', 'MOSTRARTÍTULO', 'MOSTRARTITULO', 'DISPLAYTITLE' ],
	'currentversion'            => [ '1', 'BERSIÓNAUTUAL', 'BERSIONAUTUAL', 'REVISIÓNACTUAL', 'VERSIONACTUAL', 'VERSIÓNACTUAL', 'CURRENTVERSION' ],
	'language'                  => [ '0', '#LUENGA:', '#IDIOMA:', '#LANGUAGE:' ],
	'special'                   => [ '0', 'especial', 'espezial', 'special' ],
];

$specialPageAliases = [
	'Allmessages'               => [ 'Totz_os_mensaches', 'Toz_os_mensaches' ],
	'Allpages'                  => [ 'Todas_as_pachinas' ],
	'Ancientpages'              => [ 'Pachinas_mas_viellas', 'Pachinas_mas_antigas', 'Pachinas_más_biellas', 'Pachinas_biellas', 'Pachinas_antigas' ],
	'Block'                     => [ 'Bloqueyar' ],
	'Booksources'               => [ 'Fuents_de_libros' ],
	'BrokenRedirects'           => [ 'Endreceras_trencatas', 'Endreceras_trencadas', 'Reendrezeras_trencatas', 'Endrezeras_trencatas', 'Reendrezeras_crebatas', 'Endrezeras_crebatas', 'Endrezeras_trencadas', 'Endrezeras_crebadas' ],
	'Categories'                => [ 'Categorías' ],
	'ChangePassword'            => [ 'Cambiar_contrasenya' ],
	'Confirmemail'              => [ 'Confirmar_e-mail' ],
	'Contributions'             => [ 'Contrebucions', 'Contrebuzions' ],
	'CreateAccount'             => [ 'Creyar_cuenta' ],
	'Deadendpages'              => [ 'Pachinas_sin_salida', 'Pachinas_sin_de_salida' ],
	'DoubleRedirects'           => [ 'Endreceras_doples', 'Reendrezeras_dobles', 'Dobles_reendrezeras', 'Endrezeras_dobles', 'Dobles_endrezeras' ],
	'Emailuser'                 => [ 'Ninvía_mensache', 'Nimbía_mensache' ],
	'Export'                    => [ 'Exportar' ],
	'Fewestrevisions'           => [ 'Pachinas_con_menos_edicions', 'Pachinas_con_menos_edizions', 'Pachinas_menos_editatas', 'Pachinas_con_menos_bersions' ],
	'Import'                    => [ 'Importar' ],
	'BlockList'                 => [ 'Lista_d\'IPs_bloqueyatas', 'Lista_d\'IPs_bloquiatas', 'Lista_d\'adrezas_IP_bloqueyatas', 'Lista_d\'adrezas_IP_bloquiatas' ],
	'Listadmins'                => [ 'Lista_d\'almenistradors' ],
	'Listbots'                  => [ 'Lista_de_botz', 'Lista_de_bots' ],
	'Listfiles'                 => [ 'Lista_de_fichers', 'Lista_d\'imáchens', 'Lista_d\'imachens' ],
	'Listgrouprights'           => [ 'ListaDreitosGrupos' ],
	'Listusers'                 => [ 'Lista_d\'usuarios' ],
	'Log'                       => [ 'Rechistro', 'Rechistros' ],
	'Lonelypages'               => [ 'Pachinas_popiellas' ],
	'Longpages'                 => [ 'Pachinas_mas_largas' ],
	'Mostcategories'            => [ 'Pachinas_con_mas_categorías' ],
	'Mostimages'                => [ 'Fichers_mas_emplegatos', 'Imáchens_mas_emplegatas', 'Imachens_más_emplegatas' ],
	'Mostlinked'                => [ 'Pachinas_mas_enlazatas', 'Pachinas_mas_vinculatas' ],
	'Mostlinkedcategories'      => [ 'Categorías_mas_emplegatas', 'Categorías_más_enlazatas', 'Categorías_más_binculatas' ],
	'Mostlinkedtemplates'       => [ 'Plantillas_mas_emplegatas', 'Plantillas_mas_enlazatas', 'Plantillas_más_binculatas' ],
	'Mostrevisions'             => [ 'Pachinas_con_más_edicions', 'Pachinas_con_más_edizions', 'Pachinas_más_editatas', 'Pachinas_con_más_bersions' ],
	'Movepage'                  => [ 'TresladarPachina', 'Renombrar_pachina', 'Mober_pachina', 'Tresladar_pachina' ],
	'Mycontributions'           => [ 'As_mías_contrebucions', 'As_mías_contrebuzions' ],
	'Mypage'                    => [ 'A_mía_pachina', 'A_mía_pachina_d\'usuario' ],
	'Mytalk'                    => [ 'A_mía_descusión', 'A_mía_pachina_de_descusión' ],
	'Newimages'                 => [ 'Nuevos_fichers', 'Nuevas_imáchens', 'Nuevas_imachens', 'Nuebas_imachens' ],
	'Newpages'                  => [ 'Pachinas_nuevas', 'Pachinas_recients', 'Pachinas_nuebas', 'Pachinas_más_nuebas', 'Pachinas_más_rezients', 'Pachinas_rezients' ],
	'Preferences'               => [ 'Preferencias' ],
	'Prefixindex'               => [ 'Pachinas_por_prefixo', 'Mirar_por_prefixo' ],
	'Protectedpages'            => [ 'Pachinas_protechitas', 'Pachinas_protechidas' ],
	'Protectedtitles'           => [ 'Títols_protechitos', 'Títols_protexitos', 'Títols_protechius' ],
	'Randompage'                => [ 'Pachina_a_l\'azar', 'Pachina_aleatoria', 'Pachina_aliatoria' ],
	'Recentchanges'             => [ 'Zaguers_cambeos', 'Cambeos_recients' ],
	'Search'                    => [ 'Mirar' ],
	'Shortpages'                => [ 'Pachinas_más_curtas' ],
	'Specialpages'              => [ 'Pachinas_especials', 'Pachinas_espezials' ],
	'Statistics'                => [ 'Estatisticas', 'Estadisticas' ],
	'Uncategorizedcategories'   => [ 'Categorías_sin_categorizar._Categorías_sin_categorías' ],
	'Uncategorizedimages'       => [ 'Fichers_sin_categorizar', 'Fichers_sin_categorías', 'Imáchens_sin_categorías', 'Imachens_sin_categorizar', 'Imáchens_sin_categorizar' ],
	'Uncategorizedpages'        => [ 'Pachinas_sin_categorizar', 'Pachinas_sin_categorías' ],
	'Uncategorizedtemplates'    => [ 'Plantillas_sin_categorizar._Plantillas_sin_categorías' ],
	'Undelete'                  => [ 'Restaurar' ],
	'Unusedcategories'          => [ 'Categorías_no_emplegatas', 'Categorías_sin_emplegar' ],
	'Unusedimages'              => [ 'Fichers_no_emplegatos', 'Fichers_sin_emplegar', 'Imáchens_no_emplegatas', 'Imáchens_sin_emplegar' ],
	'Unwatchedpages'            => [ 'Pachinas_no_cosiratas', 'Pachinas_sin_cosirar' ],
	'Upload'                    => [ 'Cargar', 'Puyar' ],
	'Userlogin'                 => [ 'Encetar_sesión', 'Enzetar_sesión', 'Dentrar' ],
	'Userlogout'                => [ 'Salir', 'Rematar_sesión' ],
	'Version'                   => [ 'Versión', 'Bersión' ],
	'Wantedcategories'          => [ 'Categorías_requiestas', 'Categorías_demandatas' ],
	'Wantedfiles'               => [ 'Fichers_requiestos', 'Fichers_demandaus', 'Archibos_requiestos', 'Archibos_demandatos' ],
	'Wantedpages'               => [ 'Pachinas_requiestas', 'Pachinas_demandatas', 'Binclos_crebatos', 'Binclos_trencatos' ],
	'Wantedtemplates'           => [ 'Plantillas_requiestas', 'Plantillas_demandatas' ],
	'Watchlist'                 => [ 'Lista_de_seguimiento' ],
];
