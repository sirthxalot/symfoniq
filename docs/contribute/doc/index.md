---
outline: deep
prev:
  text: 'Contribution Guideline'
  link: '/contribute'
---

# Documentation

## Vitepress

The documentation is bundled with the source code which makes it available for offline reading and makes it easier to update it. It has been written in [Markdown] using [Vitepress] as site generator.

Before you can use the documentation you must install its Node dependencies:

```bash
npm install
```

Next you can start a build-in webserver to serve the documentation on localhost:

```bash
npm run docs:dev
```

<!--                  That's all folks!                 -->

[markdown]: https://vitepress.dev/guide/markdown
[vitepress]: https://vitepress.dev/
