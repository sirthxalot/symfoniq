---
outline: deep
prev:
  text: 'Contribution Guideline'
  link: '/contribute'
---

# Testing

Whenever you write a new line of code, you also potentially add new bugs. To build better and more reliable code, you should test it.

### Run Tests

```bash
php bin/phpunit
```

## PHP Code

Symfony provides an excellent [build-in testing suite]. Symfony integrates with an independent library called [PHPUnit] to give you a rich testing framework. 

## PHPStan

[PHPStan] scans your whole codebase and looks for both obvious & tricky bugs. Even in those rarely executed if statements that certainly aren't covered by tests.

To let PHPStan analyse your codebase, you have to use the analyse command and point it to the right directories:

```bash
./bin/phpstan analyse src tests
```

:::tip
You should only analyse files with the code you’ve written yourself. There’s no need to analyse the `vendor/` directory with 3rd party dependencies because it’s not in your power to fix all the mistakes made by the developers you don’t work with directly.
:::

<!--                  That's all folks!                 -->

[build-in testing suite]: https://symfony.com/doc/current/testing.html#types-of-tests
[phpstan]: https://phpstan.org/
[phpunit]: https://docs.phpunit.de/
