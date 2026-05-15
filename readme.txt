=== Add WPGraphql Send Mail ===

Contributors: ash_hitch
Tags: Mail, WPGraphQL, GraphQL, Headless WordPress, JAMStack
Requires at least: 5.0
Tested up to: 6.9
Requires PHP: 7.0
Stable tag: 1.6.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin enables to send email via WPGraphQL.

== Installation ==

1. Install & activate [WPGraphQL](https://www.wpgraphql.com/)
2. Install & activate this plugin to the `/wp-content/plugins/` directory


== Usage ==

```
mutation SEND_EMAIL {
  sendEmail(
    input: {
      to: "test@test.com"
      from: "test@test.com"
      subject: "test email"
      body: "test email"
      clientMutationId: "test"
    }
  ) {
    origin
    sent
    message
  }
}

```