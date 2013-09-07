SEO Tools
=========

Group of basic tools for SEO purposes

Requirements
------------

- You need PHP 5.4 or greater
- Sqlite PDO driver (In ubuntu: sudo apt-get install php5-sqlite)
- Composer (http://getcomposer.org/download/)


Instructions
------------

Fork and install (you need composer for that!):

    git clone git@github.com:fiunchinho/seo-tools.git
    cd seo-tools

Then, if you have composer installed do:

    composer install
    
Otherwise, download the phar and do:

    php /path/to/composer.phar install

Then, go to folder and execute:

    ./seo find:interactive "Your keyword" your-domain.com other-domain.com

You will see the results after a while.

For further instructions type:

    ./seo help
    ./seo list
    

![Find your domain in Google Search Results](http://i.imgur.com/pOV3N4m.png "Google Search Sesults")
![Find your domain in Google Search Results](http://i.imgur.com/ZoRk8TF.png "Google Search Sesults")
