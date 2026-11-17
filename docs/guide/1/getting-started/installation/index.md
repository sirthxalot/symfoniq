---
outline: deep
---

<script setup>
import { useData } from 'vitepress'
const { site } = useData()
</script>

# Installation

## Install Symfony CLI

Install [the Symfony CLI]. You only need to do this once, so if you already have it then skip this step.


:::tabs
== Docker
To add the Symfony CLI binary to existing projects that already use Docker, update your Dockerfile with the following instruction:

```yaml
COPY --link \
    --from=ghcr.io/symfony-cli/symfony-cli:latest \
    /usr/local/bin/symfony /usr/local/bin/symfony
```

== Linux
Choose one of the following installation methods:

**Installer — via** `wget` or `curl`

```bash
wget https://get.symfony.com/cli/installer -O - | bash
```

```bash
curl -sS https://get.symfony.com/cli/installer | bash
```

**Homebrew** — [install Homebrew]

```bash
brew install symfony-cli/tap/symfony-cli
```

**Debian/Ubuntu** — APT based Linux

```bash
curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | sudo -E bash
sudo apt install symfony-cli
```

Download packages from GitHub: [amd64](https://github.com/symfony-cli/symfony-cli/releases/download/v5.22.2/symfony-cli_5.22.2_amd64.deb), [arm64](https://github.com/symfony-cli/symfony-cli/releases/download/v5.22.2/symfony-cli_5.22.2_arm64.deb), [armhf](https://github.com/symfony-cli/symfony-cli/releases/download/v5.22.2/symfony-cli_5.22.2_armhf.deb), [i386](https://github.com/symfony-cli/symfony-cli/releases/download/v5.22.2/symfony-cli_5.22.2_i386.deb).

**Fedora/CentOS/SUSE/RedHat** — YUM based Linux

Use `yum` instead of `dnf` on older versions.

```bash
curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.rpm.sh' | sudo -E bash
sudo dnf install symfony-cli
```

Download packages from GitHub: [aarch64](https://github.com/symfony-cli/symfony-cli/releases/download/v5.22.2/symfony-cli-5.22.2-1.aarch64.rpm), [armv6hl](https://github.com/symfony-cli/symfony-cli/releases/download/v5.22.2/symfony-cli-5.22.2-1.armv6hl.rpm), [i386](https://github.com/symfony-cli/symfony-cli/releases/download/v5.22.2/symfony-cli-5.22.2-1.i386.rpm), [x86_64](https://github.com/symfony-cli/symfony-cli/releases/download/v5.22.2/symfony-cli-5.22.2-1.x86_64.rpm).

**Alpine** — APK based Linux

```bash
sudo apk add --no-cache bash
curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | sudo -E bash
sudo apk add symfony-cli
```

Download packages from GitHub: [aarch64](https://github.com/symfony-cli/symfony-cli/releases/download/v5.22.2/symfony-cli_5.22.2_aarch64.apk), [armhf](https://github.com/symfony-cli/symfony-cli/releases/download/v5.22.2/symfony-cli_5.22.2_armhf.apk), [x86](https://github.com/symfony-cli/symfony-cli/releases/download/v5.22.2/symfony-cli_5.22.2_x86.apk), [x86_64](https://github.com/symfony-cli/symfony-cli/releases/download/v5.22.2/symfony-cli_5.22.2_x86_64.apk).

**Binaries**

Download binaries from GitHub: [386](https://github.com/symfony-cli/symfony-cli/releases/download/v5.22.2/symfony-cli_linux_386.tar.gz), [amd64](https://github.com/symfony-cli/symfony-cli/releases/download/v5.22.2/symfony-cli_linux_amd64.tar.gz), [arm64](https://github.com/symfony-cli/symfony-cli/releases/download/v5.22.2/symfony-cli_linux_arm64.tar.gz), [armv6](https://github.com/symfony-cli/symfony-cli/releases/download/v5.22.2/symfony-cli_linux_armv6.tar.gz).

== macOS

Choose one of the following installation methods:

**Installer — via** `wget` or `curl`

```bash
wget https://get.symfony.com/cli/installer -O - | bash
```

```bash
curl -sS https://get.symfony.com/cli/installer | bash
```

**Homebrew** — [install Homebrew]

```bash
brew install symfony-cli/tap/symfony-cli
```

**Binaries**

[Download binaries](https://github.com/symfony-cli/symfony-cli/releases/download/v5.22.2/symfony-cli_darwin_all.tar.gz) from GitHub.

== Windows

Choose one of the following installation methods:

**Scoop** — [install Scoop]

```bash
scoop install symfony-cli
```

**Binaries**

Download binaries from GitHub: [386](https://github.com/symfony-cli/symfony-cli/releases/download/v5.22.2/symfony-cli_windows_386.zip), [amd64](https://github.com/symfony-cli/symfony-cli/releases/download/v5.22.2/symfony-cli_windows_amd64.zip).
:::

### Check Requirements

The Symfony binary also provides a tool to check if your computer meets all requirements. Open your console terminal and run this command:

```bash
symfony check:requirements
```

## Composer Installation

Run these commands to create the new {{ site.title }} application using [Composer]:

```bash
composer create-project sirthxalot/symfoniq my_project
cd my_project
```

:::info
You can choose the name of the installation directory. In this example, we use the `my_project/` directory.
:::

When working on an existing Symfony application for the first time, it may be useful to run this command which displays information about the project:

```bash
php bin/console about
```

## Run Docker Containers

Check the port binding in the `.env` file before you start your containers. Ensure that the ports are not already in use and change them if necessary.

You can simply start the containers by using the `docker compose` command:

```bash
docker compose up -d
```

This command will start the [PostgreSQL] database, [Redis] and the [Mailpit] SMTP testing tool.

:::warning
You should change the database password by using the `POSTGRES_PASSWORD` in your `.env.local` file. And don't forget to change the password in the `DATABASE_URL` environment variable aswell.
:::

## Create and Migrate Database

After starting the Docker containers you should create and migrate the database:

```bash
php bin/console doctrine:database:create
```

```bash
php bin/console doctrine:migrations:migrate
```

And for testing run:

```bash
php bin/console doctrine:database:create --env=test
```

```bash
php bin/console doctrine:migrations:migrate --env=test
```

:::tip
We use the [`dama/doctrine-test-bundle`] which isolates and refreshes the doctrine database for each test.
:::

## Run Application

In production, you should install a web server like Nginx or Apache and [configure it to run the app]. This method can also be used if you're not using the Symfony local web server for development.

However for local development, the most convenient way of running Symfony is by using the local web server provided by the Symfony CLI tool. This local server provides support for HTTP/2, concurrent requests, TLS/SSL and automatic generation of security certificates, among other things.

Open your terminal, move into your new project directory and start the local web server as follows:

This blocks your terminal with the server logs; press `Ctrl + C` to stop it:

```bash
symfony server:start
```

This starts the server in the background so you can keep using the terminal:

```bash
symfony serve -d
```

Browse your application by visiting the URL displayed by Symfony Server (for example, `http://localhost:8000/`).

:::warning
We have set the `DEFAULT_URI` environment variable (`.env`) to `http://localhost:8000`. If this port is blocked or another port is in use change the variable to prevent issues.
:::

:::tip
If you want to change any port you should do this in your `.env.local` file instead of ~~`.env`~~.
:::

<!--                  That's all folks!                 -->

[`dama/doctrine-test-bundle`]: https://packagist.org/packages/dama/doctrine-test-bundle
[composer]: https://getcomposer.org/
[configure it to run the app]: /guide/1/getting-started/configuration
[install homebrew]: https://brew.sh/
[install scoop]: https://scoop.sh/
[mailpit]: https://mailpit.axllent.org/
[postgresql]: https://www.postgresql.org/
[redis]: https://redis.io
[the symfony cli]: https://symfony.com/doc/current/setup/symfony_cli.html
