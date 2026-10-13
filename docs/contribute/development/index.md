---
outline: deep
prev:
  text: 'Contribution Guideline'
  link: '/contribute'
---

# Setup Developing Environment

## Windows

This documenation focuses on running a development environment on Windows. While its possible to run the environment on a regular Windows installation, I recommend to use [Windows Pro].

Make yourself comfortable with [Powershell].

## Editor / IDE

While you can use any editor or IDE of your choice, we recommend using [Visual Studio Code]. To get started, download and [install the latest version of Visual Studio Code] on your local machine.

:::tip
For the best development experience, we also recommend installing our official extension from the [Visual Studio Marketplace] to get advanced autocomplete and syntax highlighting.
:::

### Visual Studio Code Extension Recommdation

* [Composer Intelephense](https://marketplace.visualstudio.com/items?itemName=muath-ye.composer-Intelephense)
* [Container Tools](https://marketplace.visualstudio.com/items?itemName=ms-azuretools.vscode-containers)
* [Docker](https://marketplace.visualstudio.com/items?itemName=ms-azuretools.vscode-docker)
* [DotENV](https://marketplace.visualstudio.com/items?itemName=mikestead.dotenv)
* [EditorConfig](https://marketplace.visualstudio.com/items?itemName=EditorConfig.EditorConfig)
* [Error Lens](https://marketplace.visualstudio.com/items?itemName=usernamehw.errorlens)
* [GitHub Copilot Chat](https://marketplace.visualstudio.com/items?itemName=GitHub.copilot-chat)
* [Modern PHP Pack](https://marketplace.visualstudio.com/items?itemName=vix.modern-php)
* [npm Intellisense](https://marketplace.visualstudio.com/items?itemName=christian-kohler.npm-intellisense)
* [php cs fixer](https://marketplace.visualstudio.com/items?itemName=junstyle.php-cs-fixer)
* [PHP Debug](https://marketplace.visualstudio.com/items?itemName=xdebug.php-debug)
* [PHP DocBlocker](https://marketplace.visualstudio.com/items?itemName=neilbrayfield.php-docblocker)
* [PHP Intelephense](https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client)
* [PHP Magic Number Detector](https://marketplace.visualstudio.com/items?itemName=vix.php-magic-number-detector)
* [PHP Mess Detector](https://marketplace.visualstudio.com/items?itemName=ecodes.vscode-phpmd)
* [PHP Namespace Resolver](https://marketplace.visualstudio.com/items?itemName=MehediDracula.php-namespace-resolver)
* [PHP Sniffer & Beautifier](https://marketplace.visualstudio.com/items?itemName=ValeryanM.vscode-phpsab)
* [PHP Type Hints](https://marketplace.visualstudio.com/items?itemName=vix.php-typehints-vsc)
* [Symfony for VSCode](https://marketplace.visualstudio.com/items?itemName=TheNouillet.symfony-vscode)
* [Twig Language 2](https://marketplace.visualstudio.com/items?itemName=mblode.twig-language-2)
* [Vue (Official)](https://marketplace.visualstudio.com/items?itemName=Vue.volar)
* [WSL](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-wsl)

## Git Version Control System

[Git] is a free and open source distributed version control system designed to handle everything from small to very large projects with speed and efficiency.

While Git provides helpful GUI's such as [GitHub Desktop], I recommend to use the regular Git commands.

You can verify your git installation using the `git -v` command.

### GitHub

This project is hosted on [GitHub] therefore you will need a GitHub account. GitHub provides many helpful articles such as how to use [Git in combination with GitHub] <- They are worth reading.

### Set Username and Email

Each commit needs to have a username and an email. I recommend to set them globally so you don't need to provide them with every commit, we will do this via config globally:

#### Set Git Username Globally

```bash
git config --global user.name "Bugs Bunny"
```

Change "Bugs Bunny" with your username.

#### Set Git Email Globally

```bash
git config --global user.email "bugs@bunny.com"
```

Change "bugs@bunny.com" with your email.

## WSL (Windows Subsystem for Linux)

[Windows Subsystem for Linux (WSL)] is a feature of Windows that allows you to run a Linux environment on your Windows machine, without the need for a separate virtual machine or dual booting. WSL is designed to provide a seamless and productive experience for developers who want to use both Windows and Linux at the same time.

Please [install WSL] on your Windows computer before you continue.

You can verify your WSL installation using the `wsl -v` command.

### PHP

We use a popular general-purpose scripting language that is especially suited to web development, called [PHP]. It suits perfect for the [Symfony] framework we will use. Let's quickly review the [technical requirements of Symfony] so we understand what needs to be installed.

Run the following installation within the Ubuntu console:

#### Update System

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y software-properties-common ca-certificates lsb-release apt-transport-https
```

#### Add PHP PPA Repository

```bash
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
```

#### Install PHP and Core Extensions

```bash
sudo apt install -y php8.4-cli php8.4-common php8.4-xml php8.4-mbstring php8.4-intl php8.4-pgsql php8.4-curl
```

#### Verify PHP Installation

```bash
php -v
```

### Xdebug

[Xdebug] is a debugging tool for PHP. It provides step-debugging and a whole range of development helpers, such as stack traces, a code profiler, features to dump the full execution of your script to a file, and more.

We install Xdebug on top of PHP. Run the following installation within the Ubuntu console, but make sure you have already installed PHP before continue:

#### Install Xdebug

```bash
sudo apt-get update
sudo apt-get install php8.4-xdebug
```

#### Configure Xdebug

Lets adjust the `xdebug.ini` configuration file:

```bash
sudo nano /etc/php/8.4/mods-available/xdebug.ini
```

Set the xdebug configuration:

```bash
zend_extension=xdebug.so
xdebug.mode=debug
xdebug.start_with_request=trigger
xdebug.client_automart_host=1
xdebug.client_port=9003
xdebug.max_nesting_level=512

opcache.jit=off
```

Save the file with `Ctrl + O` and `Ctrl + X`.

#### Reconnect CLI and FPM

```bash
sudo phpenmod xdebug
sudo service php8.4-fpm restart
```

### Composer

[Composer] is a tool for dependency management in PHP. It allows you to declare the libraries your project depends on and it will manage (install/update) them for you.

Run the following installation within the Ubuntu console, but make sure you have already installed PHP before continue:

#### Install PHP CLI and Unzip

```bash
sudo apt update
sudo apt install -y curl php-cli unzip
```

#### Download Composer Installation

```bash
curl -sS https://getcomposer.org/composer-2.phar -o composer.phar
```

#### Move Composer Installation for Global Usage

```bash
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
```

#### Verify Composer Installation

```bash
composer --version
```

### Node.js / NPM

Another dependency manager we are going to use called [NPM] is based on [Node.js].

Run the following installation within the Ubuntu console:

#### Prepare System

```bash
sudo apt update
sudo apt install -y ca-certificates curl gnupg
```

#### Add NodeSource Repository

```bash
curl -fsSL https://nodesource.com -o nodesource_setup.sh
sudo bash nodesource_setup.sh
```

#### Install Node.js and NPM

```bash
sudo apt install -y nodejs
```

#### Verify Installation

```bash
node -v
npm -v
```

#### Install Build Tools

```bash
sudo apt install -y build-essential
```

### Symfony CLI

The [Symfony CLI] is a free and open source developer tool to help you build, run, and manage your Symfony applications directly from your terminal.

Run the following installation within the Ubuntu console, but make sure you have already installed PHP before continue:

#### Run Installer

```bash
wget https://get.symfony.com/cli/installer -O - | bash
```

#### Move Binary to Path

```bash
sudo mv ~/.symfony5/bin/symfony /usr/local/bin/symfony
```

##### Verify Installation

```bash
symfony -v
```

##### Check Symfony Requirements

```bash
symfony check:requirements
```

Please ensure that no requirements are missing. If something is missing please install it.

## Docker

[Docker] is an open platform for developing, shipping, and running applications. We will use [Docker Desktop] - a one-click-install application for your Mac, Linux, or Windows environment that lets you build, share, and run containerized applications and microservices. 

Please make sure you [install Docker on Windows]. 

You can verify your Docker installation using the `docker -v` command.

## Browser (Chrome)

[Google Chrome] is a free, cross-platform web browser developed by Google. This browser has an integrated [dev tools] console - Chrome DevTools is a set of web developer tools built directly into the Google Chrome browser. DevTools lets you edit pages on-the-fly and diagnose problems quickly, which helps you build better websites, faster.

The [Chrome Web Store] allows you to easily install new extensions or designs.

Here is a list of recommended tools that you should install:

* [Web Developer](https://chromewebstore.google.com/detail/web-developer/bfbameneiokkgbdmiekhjnmfkcnldhhm)
* [Xdebug Chrome Extension](https://chromewebstore.google.com/detail/xdebug-chrome-extension/oiofkammbajfehgpleginfomeppgnglk)
* [Vue.js devtools](https://chromewebstore.google.com/detail/vuejs-devtools/nhdogjmejiglipccpnnnanhbledajbpd)

<!--                    That's all folks!                     -->

[chrome web store]: https://chromewebstore.google.com/
[composer]: https://getcomposer.org/
[dev tools]: https://developer.chrome.com/docs/devtools?hl=en
[docker desktop]: https://docs.docker.com/desktop/
[docker]: https://www.docker.com/
[Git in combination with GitHub]: https://docs.github.com/en/get-started/start-your-journey/what-is-github
[git]: https://git-scm.com/
[github desktop]: https://github.com/apps/desktop
[github]: https://github.com/
[google chrome]: https://www.google.com/intl/en_us/chrome/
[install docker on windows]: https://docs.docker.com/desktop/setup/install/windows-install/
[install the latest version of visual studio code]: https://code.visualstudio.com/docs/getstarted/overview#_install-vs-code
[install wsl]: https://learn.microsoft.com/en-us/windows/wsl/install
[node.js]: https://nodejs.org/en
[npm]: https://www.npmjs.com/
[php]: https://www.php.net/
[powershell]: https://learn.microsoft.com/en-us/powershell/scripting/windows-powershell/starting-windows-powershell?view=powershell-7.6
[symfony cli]: https://symfony.com/doc/current/setup/symfony_cli.html
[symfony]: https://symfony.com/
[technical requirements of symfony]: https://symfony.com/doc/current/setup.html#technical-requirements
[visual studio code]: https://code.visualstudio.com/
[visual studio marketplace]: https://marketplace.visualstudio.com/vscode
[windows pro]: https://www.microsoft.com/en-us/windows/business/windows-11-pro
[windows subsystem for linux (wsl)]: https://learn.microsoft.com/en-us/windows/wsl/about
[xdebug]: https://xdebug.org/
