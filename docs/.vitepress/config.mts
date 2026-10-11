import { defineConfig } from 'vitepress'

// https://vitepress.dev/reference/site-config
export default defineConfig({
  title: "Symfoniq",
  description: "A demonstration web application based on the Symfony PHP framework following best practices and design patterns.",
  base: '/symfoniq/',
  head: [
    ['link', { rel: 'icon', href: '/symfoniq/favicon.ico', sizes: 'any' }],
    ['link', { rel: 'icon', href: '/symfoniq/icon.svg', type: 'image/svg+xml' }],
    ['link', { rel: 'apple-touch-icon', href: '/symfoniq/apple-touch-icon.png' }],
  ],
  lastUpdated: true,
  themeConfig: {
    // https://vitepress.dev/reference/default-theme-config
    logo: '/logo.svg',
    siteTitle: false,

    nav: [
      { text: 'Ecosystem', items: [
        {
                text: 'Source Code',
                link: 'https://github.com/sirthxalot/symfoniq'
              },
              {
                text: 'Support Questions',
                link: 'https://github.com/sirthxalot/symfoniq/discussions'
              },
              {
                text: 'Issue Tracking',
                link: 'https://github.com/sirthxalot/symfoniq/issues'
              },
              {
                text: 'Changelog',
                link: 'https://github.com/sirthxalot/symfoniq/blob/main/CHANGELOG.md'
              },
      ]},
      { text: 'Contribution', items: [
        {
          text: 'Code of Conduct',
          link: '/contribute/code-of-conduct'
        },
        {
          text: 'Development Environment',
          link: '/contribute/development'
        },
        {
          text: 'Artwork',
          link: '/contribute/artwork'
        },
        {
          text: 'Coding',
          link: '/contribute/coding'
        },
        {
          text: 'Issue Tracking',
          link: '/contribute/issues'
        },
        {
          text: 'Documentation',
          link: '/contribute/doc'
        },
        {
          text: 'Testing',
          link: '/contribute/testing'
        },
        {
          text: 'Versions',
          link: '/contribute/vcs'
        },
      ]},
      { text: 'Guide', items: [
        {
          text: '1.x',
          link: 'guide/1/getting-started/roadmap'
        },
      ]},
      { text: 'License', link: '/license' },
    ],

    sidebar: {
         '/guide/1': [
        {
          text: 'Getting Started',
          items: [
            {
                text: 'Roadmap',
                link: '/guide/1/getting-started/roadmap'
            },
            {
                text: 'Installation',
                link: '/guide/1/getting-started/installation'
            },
            {
                text: 'Configuration',
                link: '/guide/1/getting-started/configuration'
            },
            {
                text: 'Directory Structure',
                link: '/guide/1/getting-started/structure'
            },
            {
                text: 'Production',
                link: '/guide/1/getting-started/production'
            },
          ]
        }
      ],
    },

    socialLinks: [
      { icon: 'github', link: 'https://github.com/sirthxalot/symfoniq' }
    ]
  }
})
