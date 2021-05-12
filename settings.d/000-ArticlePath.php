<?php

/*
 *
 * Make MediaWiki reachable via ShortUrl /wiki/
 * Documentation: https://www.mediawiki.org/wiki/Manual:Short_URL
 *
 * For apache integration please add the following rewrite rule to your vhosts:
 *
 * RewriteEngine On
 * RewriteRule ^/*$ %{DOCUMENT_ROOT}/w/index.php [L]
 * RewriteRule ^/?wiki(/.*)?$ %{DOCUMENT_ROOT}/w/index.php [L]
 *
 * For IIS integration please add the following rewrite ruke to you web.config:
 *
 * <?xml version="1.0" encoding="utf-8"?>
 * <configuration>
 *   <system.webServer>
 *     <rewrite>
 *       <rules>
 *         <rule name="Wiki-Short-URL-1" stopProcessing="true">
 *           <match url="^wiki/(.*)$" />
 *           <action type="Rewrite" url="/w/index.php?title={UrlEncode:{R:1}}" />
 *         </rule>
 *         <rule name="Wiki-Short-URL-2" stopProcessing="true">
 *           <match url="^wiki/$" />
 *           <action type="Rewrite" url="/w/index.php" />
 *         </rule>
 *         <rule name="Wiki-Short-URL-3" stopProcessing="true">
 *           <match url="^/*$" />
 *           <action type="Rewrite" url="/w/index.php" />
 *         </rule>
 *       </rules>      
 *     </rewrite>
 *   </system.webServer>
 * </configuration>
 *
 */

return;
$wgArticlePath = "/wiki/$1";
 

