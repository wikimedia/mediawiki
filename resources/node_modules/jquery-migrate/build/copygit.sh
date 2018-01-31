#
scp ../dist/jquery-migrate.js jqadmin@code.origin.jquery.com:/var/www/html/code.jquery.com/jquery-migrate-git.js
curl http://code.origin.jquery.com/jquery-migrate-git.js?reload

scp ../dist/jquery-migrate.min.js jqadmin@code.origin.jquery.com:/var/www/html/code.jquery.com/jquery-migrate-git.min.js
curl http://code.origin.jquery.com/jquery-migrate-git.min.js?reload

