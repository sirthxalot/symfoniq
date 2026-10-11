---
outline: deep
---

<script setup>
import { useData } from 'vitepress'
const { site } = useData()
</script>

# Configuration

## App Configuration

Make sure you make yourself comfortable with the [Symfony configuration].

:::warning
Don't store any secret credentials within the `.env` file use the `.env.local` file instead if you are using version control. The `.env` file is not ingored from commiting!
:::

## Environment Values

- `.env`: defines the default values of the env vars needed by the application;
- `.env.local`: overrides the default values for all environments but only on the machine which contains the file. This file should not be committed to the repository and it's ignored in the `test` environment (because tests should produce the same results for everyone);
- `.env.<environment>` (e.g. `.env.test`): overrides env vars only for one environment but for all machines (these files are committed);
- `.env.<environment>.local` (e.g. `.env.test.local`): defines machine-specific env var overrides only for one environment. It's similar to `.env.local`, but the overrides only apply to one environment.

The `.env` and `.env.<environment>` files should be committed to the repository because they are the same for all developers and machines. However, the env files ending in `.local` (`.env.local` and `.env.<environment>.local`) **should not be committed** because only you will use them. In fact, the `.gitignore` file that comes with {{ site.title }} prevents them from being committed.

<!--                  That's all folks!                 -->

[Symfony configuration]: https://symfony.com/doc/current/configuration.html
