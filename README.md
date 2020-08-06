# WPGraphQL Send Email Plugin

![WPGraphQl Yoast SEO Plugin](./banner.png)

One of the simple things about a traditional WordPress sites is sending emails, this plugin makes it easy to do this via a simple mutation when you are using WPGraphQL.

### Features

- Set list of allowed origins in admin
- Set CC address in admin
- Set default From address in admin

## Composer

```
composer require ashhitch/wp-graphql-send-mail
```

## Usage

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

To try stop unauthorised emails you can set a list of domains that can send emails through the mutation.

These are set under `Settings > WPGraphQL Mail` from your WordPress Admin

## Support

[Open an issue](https://github.com/ashhitch/wp-graphql-send-mail/issues)

## Other WpGraphQl Plugins

Want to get Yoast data via WPGraphQL? [Checkout my other plugin](https://github.com/ashhitch/wp-graphql-yoast-seo)

Mail Icon by [Good Ware](https://www.flaticon.com/authors/good-ware)
