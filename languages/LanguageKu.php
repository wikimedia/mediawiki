<?php

require_once( "LanguageUtf8.php" );

if ( $wgMetaNamespace == "Wikipedia" ) {
        $wgMetaNamespace = "Wîkîpediya";
}

/* private */ $wgNamespaceNamesKu = array(
        NS_MEDIA            => 'Medya',
        NS_SPECIAL          => 'Taybet',
        NS_MAIN             => '',
        NS_TALK             => 'Nîqaş',
        NS_USER             => 'Bikarhêner',
        NS_USER_TALK        => 'Bikarhêner_nîqaş',
        NS_PROJECT          => $wgMetaNamespace,
        NS_PROJECT_TALK     => $wgMetaNamespace . '_nîqaş',
        NS_IMAGE            => 'Wêne',
        NS_IMAGE_TALK       => 'Wêne_nîqaş',
        NS_MEDIAWIKI        => 'MediaWiki',
        NS_MEDIAWIKI_TALK   => 'MediaWiki_nîqaş',
        NS_TEMPLATE         => 'Şablon',
        NS_TEMPLATE_TALK    => 'Şablon_nîqaş',
        NS_HELP             => 'Alîkarî',
        NS_HELP_TALK        => 'Alîkarî_nîqaş',
        NS_CATEGORY         => 'Kategorî',
        NS_CATEGORY_TALK    => 'Kategorî_nîqaş'
) + $wgNamespaceNamesEn;

class LanguageKu extends LanguageUtf8
{
        function getNamespaces() {
                global $wgNamespaceNamesKu;
                return $wgNamespaceNamesKu;
        }

        function getNsText( $index ) {
                global $wgNamespaceNamesKu;
                return $wgNamespaceNamesKu[$index];
        }

        function getNsIndex( $text ) {
                global $wgNamespaceNamesKu;

                foreach ( $wgNamespaceNamesKu as $i => $n ) {
                        if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
                }
                return false;
        }
}

?>
