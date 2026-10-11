---
outline: deep
---

<script setup>
import { useData } from 'vitepress'
const { site } = useData()
</script>

# Installation

## Install Symfony CLI

Beginn by [installing the Symfony CLI]. You only need to do the first step and skip the others.

The symfony binary also provides a tool to check if your computer meets all requirements. Open your console terminal and run this command:

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
You can choose the name of the installation directory in this example we use the `my_project/` directory.
:::

When working on an existing Symfony application for the first time, it may be useful to run this command which displays information about the project:

```bash
php bin/console about
```

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

<!--                  That's all folks!                 -->

[composer]: https://getcomposer.org/
[configure it to run the app]: /guide/1/getting-started/configuration
[installing the Symfony CLI]: https://symfony.com/download#step-1-install-symfony-cli
