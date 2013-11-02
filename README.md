SEO Tools
=========
Group of basic tools for SEO purposes

[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/fiunchinho/seo-tools/badges/quality-score.png?s=942524b3a26a9475fa79ff1636b2f5268b650e84)](https://scrutinizer-ci.com/g/fiunchinho/seo-tools/)

Requirements
------------

- You need PHP 5.3.3 or greater
- Sqlite PDO driver (In ubuntu: sudo apt-get install php5-sqlite)
- Composer (http://getcomposer.org/download/)


Install
-------

Fork and install (you need composer for that!):

    git clone git@github.com:fiunchinho/seo-tools.git
    cd seo-tools

Then, if you have composer installed do:

    composer install

Otherwise, download the phar and do:

    php /path/to/composer.phar install


Usage
-----

After installing, execute:

    $ ./bin/seo-tools find:interactive "Your keyword" your-domain.com other-domain.com

You will see the results after a while.

    $ ./bin/seo-tools find:interactive "SEO" google.com
    Domain found in the position 13, in the page number 2.
    +----+--------------------------------+-----------+
    | #  | Url                            | Is Yours? |
    +----+--------------------------------+-----------+
    | 1  | es.wikipedia.org               | []        |
    | 2  | es.wikipedia.org               | []        |
    | 3  | www.seo.org                    | []        |
    | 4  | www.adrenalina.es              | []        |
    | 5  | www.danielperis.com            | []        |
    | 6  | www.seocom.es                  | []        |
    | 7  | glseobarcelona.com             | []        |
    | 8  | seoblog.es                     | []        |
    | 9  | www.oftalmoseo.com             | []        |
    | 10 | www.clinicseo.es               | []        |
    | 11 | www.google.es                  | []        |
    | 12 | es.majesticseo.com             | []        |
    | 13 | support.google.com             | [X]       |
    | 14 | www.congresoseoprofesional.com | []        |
    | 15 | www.senormunoz.es              | []        |
    | 16 | www.portal-seo.com             | []        |
    | 17 | www.tallerseo.com              | []        |
    | 18 | searchengineland.com           | []        |
    | 19 | www.analistaseo.es             | []        |
    | 20 | www.seosevilla.org             | []        |
    +----+--------------------------------+-----------+
    Elapsed time: 2.48

If you want to search in a local version of google, try --language and --google_domain, for instance:

    $ ./bin/seo-tools find:interactive "SEO" google.com --language=es --google_domain=google.es

More
----

For further instructions type:

    ./seo help
    ./seo list
