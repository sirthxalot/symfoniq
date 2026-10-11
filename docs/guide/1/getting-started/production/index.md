---
outline: deep
---

# Production

This article is not a step-by-step guide, but rather a general list of the most common requirements and ideas for deployment.

## Technical Requirements

Install PHP 8.4 or higher and these PHP extensions (which are installed and enabled by default in most PHP 8 installations): [Ctype], [iconv], [PCRE], [Session], [SimpleXML], and [Tokenizer];

[Install Composer], which is used to install PHP packages.

[Install NPM], which is used to install Node.js modules.

## Basic File Transfer

The most basic way of deploying an application is copying the files manually via FTP/SCP (or similar method).

## Install the Dependencies

Run the following commands to install the dependencies:

```bash
composer install --no-dev --optimize-autoloader
npm install --production
```

:::tip
The `--optimize-autoloader` flag improves Composer's autoloader performance significantly by building a "class map". The `--no-dev` flag ensures that development packages are not installed in the production environment.

With the `--production` flag (or when the `NODE_ENV` environment variable is set to `production`), npm will not install modules listed in devDependencies.
:::

:::warning
If you get a "class not found" error during this step, you may need to run `export APP_ENV=prod` (or `export SYMFONY_ENV=prod` if you're not using Symfony Flex) before running this command so that the `post-install-cmd` scripts run in the `prod` environment.
:::

## Configure your Environment Variables

The application read its configuration from environment variables. While developing locally, you'll usually store these in `.env` files. On production, you have two options:

1. Create "real" environment variables. How you set environment variables depends on your setup: they can be set at the command line, in your Nginx configuration, or via other methods provided by your hosting service;

2. Or, create a `.env.prod.local` file that contains values specific to your production environment.

### Optimized Environment Variables

You might not want your application to process the `.env.*` files on every request. You can generate an optimized `.env.local.php` which overrides all other configuration files:

```bash
composer dump-env prod
```

The generated file will contain all the configuration stored in `.env`. If you want to rely only on environment variables, generate one without any values using:

```bash
composer dump-env prod --empty
```

## Clear Cache

Make sure you clear and warm-up your Symfony cache:

```bash
APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear
```

## Other Things!

There may be lots of other things that you need to do, depending on your setup:

- Running any database migrations
- Clearing your APCu cache
- Add/edit CRON jobs
- Restarting your workers
- [Dumping error pages as static HTML files]
- On a shared hosting platform using the Apache web server, you may need to install the [symfony/apache-pack] package
- etc.

<!--                  That's all folks!                 -->

[ctype]: https://www.php.net/book.ctype
[dumping error pages as static html files]: https://symfony.com/doc/current/controller/error_pages.html#dumping-error-pages-as-static-html-files
[iconv]: https://www.php.net/book.iconv
[install composer]: https://getcomposer.org/download/
[install npm]: https://docs.npmjs.com/downloading-and-installing-node-js-and-npm
[pcre]: https://www.php.net/book.pcre
[session]: https://www.php.net/book.session
[simplexml]: https://www.php.net/book.simplexml
[symfony/apache-pack]: https://packagist.org/packages/symfony/apache-pack
[tokenizer]: https://www.php.net/book.tokenizer
