=== WPGraphQL Send Mail ===

Contributors: ash_hitch
Tags: Mail, WPGraphQL, GraphQL, Headless WordPress, Decoupled WordPress, JAMStack
Requires at least: 5.0
Tested up to: 5.6
Requires PHP: 7.0
Stable tag: 1.2.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

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