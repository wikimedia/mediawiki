ifndef MW_INSTALL_PATH
$(error MW_INSTALL_PATH is not set. Please set it to your root mediawiki installation.)
endif

# Compile mustache templates into PHP templates
templates:
	php ${MW_INSTALL_PATH}/includes/templates/compileTemplates.php
