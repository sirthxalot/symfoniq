---
outline: deep
---

<script setup>
import { useData } from 'vitepress'
const { site } = useData()
</script>

# Mail

## Introduction

Sending email is not that hard. {{ site.title }} provides a clean, simple email API powered by the popular [Symfony Mailer component]. The build-in transport such as SMTP, send mail and native are ready from the get go. And there are many [additional drivers] to quickly get started sending mail through a local or cloud-based service of your choice.

## Configuration

{{ site.title }}'s email services may be configured via your application's `config/packages/mailer.yaml` configuration file.

### Transport Setup

Emails are delivered via a "transport". By default, you can deliver emails over SMTP by configuring the **DSN** in your `.env.local` file (the `user`, `pass` and `port` parameters are optional):

::: code-group
```env [.env.local]
MAILER_DSN=smtp://user:pass@smtp.example.com:port
```
:::

::: code-group
```yaml [config/packages/mailer.yaml]
framework:
    mailer:
        dsn: '%env(MAILER_DSN)%'
```
:::

:::warning
If the username, password or host contain any character considered special in a URI (such as `: / ? # [ ] @ ! $ & ' ( ) * + , ; =`), you must encode them. See [RFC 3986] for the full list of reserved characters or use the [urlencode] function to encode them.
:::

### Configuring Email From Address Globally

Instead of calling `->from()` on each Email you create, you can configure this value globally so that it is [set on all sent emails]. The same is true with `->to()` and headers.

{{ site.title }} allows to determine the from address within the following `.env.local` environment variables:

::: code-group
```env [.env.local]
MAILER_FROM_NAME='Bugs Bunny'
MAILER_FROM_MAIL='bugs@acme.local'
```
:::


:::warning
Some third-party providers don't support the usage of keywords like `from` in the `headers`. Check out your provider's documentation before setting any global header.
:::

## View Mail in Browser

If you would like to allow the client to review the message within the browser you can do this.

### Basic Usage

#### Use `ArchivableEmail`

To define the contents of your email with Twig and [Inky], use the `ArchivableEmail` class.

```php
use App\Bridge\Twig\Mime\ArchivableEmail;

$email = new ArchivableEmail()
    ->from('fabien@example.com')
    ->to(new Address('ryan@example.com'))
    ->subject('Thanks for signing up!')

    // path of the Twig template to render
    ->htmlTemplate('emails/inky/signup.html.twig')

    // change the locale used in the template, e.g. to match the user's locale
    ->locale('de')

    // pass variables (name => value) to the template
    ->context([
        'expiration_date' => new \DateTime('+7 days'),
        'username' => 'foo',
    ])
;
```

#### Extend Base Template

You can extend the `templates/emails/inky/base.html.twig` Twig file which contains a starting point for your email and includes the browser link by default. Your message content goes within the `content` block. The template uses the [Inky] template syntax.

```twig
{% extends 'emails/inky/base.html.twig' %}

{% block content %}
    <!-- template: emails/inky/signup.html.twig -->
{% endblock %}
```

#### Browser Link

If you would like to add the browser link manually you can do this.

```twig
{% if browser_view_url is defined %}
    <p data-browser-link 
       style="font-size: 12px; color: #666; text-align: center"
    >
        Is this email not displaying correctly? 
        <a href="{{ browser_view_url }}">Show in browser</a>
    </p>
{% endif %}
```

### How it Works

Whenever you send an `ArchivableMail` mail class the `App\EventListener\EmailArchiveListener` class will listen and catch the event.

The event listener will automatically generate a hex link token that will be used to generate the browser link.

In a next step the event listener will adjust the mail content for the browser view. It will replace all cid image attachments and transform them into base64 images. Than it will remove the browser link for the web preview. The adjusted HTML will be stored within the database.

The `App\Controller\EmailViewController` controller class is responsible to render the web preview. It searches the HTML content from the generated token and render it. If the token expires or does not exists it will throw a `404` or `403` response.

## Test Mail

Use the `mailer:test-mail` command to send a testing email. This email uses a template and Inky. It is meant to test and show some email functionality.

```bash
bin/console mailer:test-mail recipient@email.local de
```

The command asks for permission if the environment is `prod` (production). You can change the environment for the command using the `--env` option or accept the question with `y`.

![Mail preview](mail.png)

### Arguments

#### TO (First)

The `to` argument is required and expects a string that determines the recipient of the email. The email address must be valid or the command will fail.

#### LOCALE (Second)

The `locale` is the second argument and is optional. It expects a string (`en` or `de`) to determine the locale for the email. Default is `en`. If the translation is not available we fallback to `en`.

<!--                  That's all folks!                 -->

[additional drivers]: https://symfony.com/doc/current/mailer.html#using-a-3rd-party-transport
[inky]: https://get.foundation/emails/docs/inky.html
[rfc 3986]: https://www.ietf.org/rfc/rfc3986.txt
[set on all sent emails]: https://symfony.com/doc/current/mailer.html#configuring-emails-globally
[symfony mailer component]: https://symfony.com/doc/current/mailer.html
