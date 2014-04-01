<?php
/** Aragonese (aragonés)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback = 'es';

$namespaceNames = array(
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
);

$namespaceAliases = array(
	'Espezial' => NS_SPECIAL,
);

// Remove Spanish gender aliases (bug 37090)
$namespaceGenderAliases = array();

$magicWords = array(
	'redirect'                  => array( '0', '#ENDRECERA', '#REENDRECERA', '#REDIRECCIÓN', '#REDIRECCION', '#REDIRECT' ),
	'namespace'                 => array( '1', 'ESPACIODENOMBRES', 'ESPACIODENOMBRE', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'ESPACIODENOMBRESE', 'ESPACIODENOMBREC', 'NAMESPACEE' ),
	'img_right'                 => array( '1', 'dreita', 'derecha', 'dcha', 'der', 'right' ),
	'img_left'                  => array( '1', 'cucha', 'zurda', 'izquierda', 'izda', 'izq', 'left' ),
	'ns'                        => array( '0', 'EN:', 'EDN:', 'NS:' ),
	'displaytitle'              => array( '1', 'TÍTOL', 'MOSTRARTÍTULO', 'MOSTRARTITULO', 'DISPLAYTITLE' ),
	'currentversion'            => array( '1', 'BERSIÓNAUTUAL', 'BERSIONAUTUAL', 'REVISIÓNACTUAL', 'VERSIONACTUAL', 'VERSIÓNACTUAL', 'CURRENTVERSION' ),
	'language'                  => array( '0', '#LUENGA:', '#IDIOMA:', '#LANGUAGE:' ),
	'special'                   => array( '0', 'especial', 'espezial', 'special' ),
);

$specialPageAliases = array(
	'Allmessages'               => array( 'Totz_os_mensaches', 'Toz_os_mensaches' ),
	'Allpages'                  => array( 'Todas_as_pachinas' ),
	'Ancientpages'              => array( 'Pachinas_mas_viellas', 'Pachinas_mas_antigas', 'Pachinas_más_biellas', 'Pachinas_biellas', 'Pachinas_antigas' ),
	'Block'                     => array( 'Bloqueyar' ),
	'Booksources'               => array( 'Fuents_de_libros' ),
	'BrokenRedirects'           => array( 'Endreceras_trencatas', 'Endreceras_trencadas', 'Reendrezeras_trencatas', 'Endrezeras_trencatas', 'Reendrezeras_crebatas', 'Endrezeras_crebatas', 'Endrezeras_trencadas', 'Endrezeras_crebadas' ),
	'Categories'                => array( 'Categorías' ),
	'ChangePassword'            => array( 'Cambiar_contrasenya' ),
	'Confirmemail'              => array( 'Confirmar_e-mail' ),
	'Contributions'             => array( 'Contrebucions', 'Contrebuzions' ),
	'CreateAccount'             => array( 'Creyar_cuenta' ),
	'Deadendpages'              => array( 'Pachinas_sin_salida', 'Pachinas_sin_de_salida' ),
	'DoubleRedirects'           => array( 'Endreceras_doples', 'Reendrezeras_dobles', 'Dobles_reendrezeras', 'Endrezeras_dobles', 'Dobles_endrezeras' ),
	'Emailuser'                 => array( 'Ninvía_mensache', 'Nimbía_mensache' ),
	'Export'                    => array( 'Exportar' ),
	'Fewestrevisions'           => array( 'Pachinas_con_menos_edicions', 'Pachinas_con_menos_edizions', 'Pachinas_menos_editatas', 'Pachinas_con_menos_bersions' ),
	'Import'                    => array( 'Importar' ),
	'BlockList'                 => array( 'Lista_d\'IPs_bloqueyatas', 'Lista_d\'IPs_bloquiatas', 'Lista_d\'adrezas_IP_bloqueyatas', 'Lista_d\'adrezas_IP_bloquiatas' ),
	'Listadmins'                => array( 'Lista_d\'almenistradors' ),
	'Listbots'                  => array( 'Lista_de_botz', 'Lista_de_bots' ),
	'Listfiles'                 => array( 'Lista_de_fichers', 'Lista_d\'imáchens', 'Lista_d\'imachens' ),
	'Listgrouprights'           => array( 'ListaDreitosGrupos' ),
	'Listusers'                 => array( 'Lista_d\'usuarios' ),
	'Log'                       => array( 'Rechistro', 'Rechistros' ),
	'Lonelypages'               => array( 'Pachinas_popiellas' ),
	'Longpages'                 => array( 'Pachinas_mas_largas' ),
	'Mostcategories'            => array( 'Pachinas_con_mas_categorías' ),
	'Mostimages'                => array( 'Fichers_mas_emplegatos', 'Imáchens_mas_emplegatas', 'Imachens_más_emplegatas' ),
	'Mostlinked'                => array( 'Pachinas_mas_enlazatas', 'Pachinas_mas_vinculatas' ),
	'Mostlinkedcategories'      => array( 'Categorías_mas_emplegatas', 'Categorías_más_enlazatas', 'Categorías_más_binculatas' ),
	'Mostlinkedtemplates'       => array( 'Plantillas_mas_emplegatas', 'Plantillas_mas_enlazatas', 'Plantillas_más_binculatas' ),
	'Mostrevisions'             => array( 'Pachinas_con_más_edicions', 'Pachinas_con_más_edizions', 'Pachinas_más_editatas', 'Pachinas_con_más_bersions' ),
	'Movepage'                  => array( 'TresladarPachina', 'Renombrar_pachina', 'Mober_pachina', 'Tresladar_pachina' ),
	'Mycontributions'           => array( 'As_mías_contrebucions', 'As_mías_contrebuzions' ),
	'Mypage'                    => array( 'A_mía_pachina', 'A_mía_pachina_d\'usuario' ),
	'Mytalk'                    => array( 'A_mía_descusión', 'A_mía_pachina_de_descusión' ),
	'Newimages'                 => array( 'Nuevos_fichers', 'Nuevas_imáchens', 'Nuevas_imachens', 'Nuebas_imachens' ),
	'Newpages'                  => array( 'Pachinas_nuevas', 'Pachinas_recients', 'Pachinas_nuebas', 'Pachinas_más_nuebas', 'Pachinas_más_rezients', 'Pachinas_rezients' ),
	'Popularpages'              => array( 'Pachinas_populars', 'Pachinas_más_populars' ),
	'Preferences'               => array( 'Preferencias' ),
	'Prefixindex'               => array( 'Pachinas_por_prefixo', 'Mirar_por_prefixo' ),
	'Protectedpages'            => array( 'Pachinas_protechitas', 'Pachinas_protechidas' ),
	'Protectedtitles'           => array( 'Títols_protechitos', 'Títols_protexitos', 'Títols_protechius' ),
	'Randompage'                => array( 'Pachina_a_l\'azar', 'Pachina_aleatoria', 'Pachina_aliatoria' ),
	'Recentchanges'             => array( 'Zaguers_cambeos', 'Cambeos_recients' ),
	'Search'                    => array( 'Mirar' ),
	'Shortpages'                => array( 'Pachinas_más_curtas' ),
	'Specialpages'              => array( 'Pachinas_especials', 'Pachinas_espezials' ),
	'Statistics'                => array( 'Estatisticas', 'Estadisticas' ),
	'Uncategorizedcategories'   => array( 'Categorías_sin_categorizar._Categorías_sin_categorías' ),
	'Uncategorizedimages'       => array( 'Fichers_sin_categorizar', 'Fichers_sin_categorías', 'Imáchens_sin_categorías', 'Imachens_sin_categorizar', 'Imáchens_sin_categorizar' ),
	'Uncategorizedpages'        => array( 'Pachinas_sin_categorizar', 'Pachinas_sin_categorías' ),
	'Uncategorizedtemplates'    => array( 'Plantillas_sin_categorizar._Plantillas_sin_categorías' ),
	'Undelete'                  => array( 'Restaurar' ),
	'Unusedcategories'          => array( 'Categorías_no_emplegatas', 'Categorías_sin_emplegar' ),
	'Unusedimages'              => array( 'Fichers_no_emplegatos', 'Fichers_sin_emplegar', 'Imáchens_no_emplegatas', 'Imáchens_sin_emplegar' ),
	'Unwatchedpages'            => array( 'Pachinas_no_cosiratas', 'Pachinas_sin_cosirar' ),
	'Upload'                    => array( 'Cargar', 'Puyar' ),
	'Userlogin'                 => array( 'Encetar_sesión', 'Enzetar_sesión', 'Dentrar' ),
	'Userlogout'                => array( 'Salir', 'Rematar_sesión' ),
	'Version'                   => array( 'Versión', 'Bersión' ),
	'Wantedcategories'          => array( 'Categorías_requiestas', 'Categorías_demandatas' ),
	'Wantedfiles'               => array( 'Fichers_requiestos', 'Fichers_demandaus', 'Archibos_requiestos', 'Archibos_demandatos' ),
	'Wantedpages'               => array( 'Pachinas_requiestas', 'Pachinas_demandatas', 'Binclos_crebatos', 'Binclos_trencatos' ),
	'Wantedtemplates'           => array( 'Plantillas_requiestas', 'Plantillas_demandatas' ),
	'Watchlist'                 => array( 'Lista_de_seguimiento' ),
);

