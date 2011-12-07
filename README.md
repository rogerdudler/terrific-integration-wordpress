# The Terrific WordPress Theme - A modular approach

## Theme Installation
1. Download the Terrific theme and unzip it into the /wp-content/themes directory
2. Create the /wp-content/themes/terrific/cache/ directory and make it writeable by webserver (chmod 777 ...)
3. Activate the theme in WordPress!

## Bundle Installation (with WordPress)

1. Create a install.sh with the following code in your document root and run it

    #/sbin/sh
    VERSION=3.3-RC1
    echo "Installing WordPress $VERSION with Terrific..."
    wget http://wordpress.org/wordpress-$VERSION.tar.gz
    tar xvfz wordpress-$VERSION.tar.gz
    cd wordpress
    echo -e "<?php define('WP_DEFAULT_THEME', 'terrific'); ?>\n$(cat wp-config-sample.php)" > wp-config-sample.php
    cd wp-content
    cd themes
    wget https://github.com/rogerdudler/terrific-integration-wordpress/tarball/master --no-check-certificate
    tar xvfz master
    mv rogerdudler-terrific-integration-wordpress-* terrific
    rm master
    cd terrific
    mkdir cache
    chmod 777 cache
    echo "Finished."

2. Delete the shell script
3. Open up your browser on your domain and install wordpress with the installer

## Included
* TerrificJS 1.0
* JQuery 1.6.2

## CSS Libraries (optional)
* YUI Reset 2.9.0

## JS Libraries (optional)
* Modernizr 1.0.6

## JQuery Plugins (optional)
* Appear 1.1.1 (for async share buttons)
* Fancybox 1.3.4 (lightbox)
* Validation 1.8.1 (for comment form validation)

## Further Reading
Read more on http://www.rogerdudler.com/terrific-theme-framework-for-wordpress/
