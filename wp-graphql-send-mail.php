<?php

/**
 * Plugin Name:     Add WPGraphql Send Mail
 * Plugin URI:      https://github.com/ashhitch/wp-graphql-send-mail
 * Description:     A WPGraphQL Extension that adds support for Sending Mail via a mutation
 * Author:          Ash Hitchcock
 * Author URI:      https://www.ashleyhitchcock.com
 * Text Domain:     wp-graphql-send-mail
 * Domain Path:     /languages
 * Version:         1.2.0
 *
 * @package         WP_Graphql_SEND_MAIL
 */



add_action('admin_menu', 'wpgraphql_send_mail_add_admin_menu');
add_action('admin_init', 'wpgraphql_send_mail_settings_init');

function wpgraphql_send_mail_add_admin_menu()
{
  add_options_page('WPGraphQL Send Mail Settings', 'WPGraphQL Mail', 'manage_options', 'wpgraphql-send-mail-page', 'wpgraphql_send_mail_options_page');
}

function wpgraphql_send_mail_settings_init()
{
  register_setting('wsmPlugin', 'wpgraphql_send_mail_settings');
  add_settings_section(
    'wpgraphql_send_mail_wsmPlugin_section',
    __('Security', 'wp-graphql-send-mail'),
    'wpgraphql_send_mail_settings_section_callback',
    'wsmPlugin'
  );

  add_settings_field(
    'wpgraphql_send_mail_allowed_origins',
    __('Allowed Origins', 'wp-graphql-send-mail'),
    'wpgraphql_send_mail_origins_textarea_render',
    'wsmPlugin',
    'wpgraphql_send_mail_wsmPlugin_section'
  );

  add_settings_field(
    'wpgraphql_send_mail_cc',
    __('CC address', 'wp-graphql-send-mail'),
    'wpgraphql_send_mail_cc_render',
    'wsmPlugin',
    'wpgraphql_send_mail_wsmPlugin_section'
  );

  add_settings_field(
    'wpgraphql_send_mail_to',
    __('Default To address', 'wp-graphql-send-mail'),
    'wpgraphql_send_mail_to_render',
    'wsmPlugin',
    'wpgraphql_send_mail_wsmPlugin_section'
  );

  add_settings_field(
    'wpgraphql_send_mail_from',
    __('Default From address', 'wp-graphql-send-mail'),
    'wpgraphql_send_mail_from_render',
    'wsmPlugin',
    'wpgraphql_send_mail_wsmPlugin_section'
  );
}

function wpgraphql_send_mail_origins_textarea_render()
{
  $options = get_option('wpgraphql_send_mail_settings');
?>
  <textarea rows="6" name='wpgraphql_send_mail_settings[wpgraphql_send_mail_allowed_origins]'><?php echo isset($options['wpgraphql_send_mail_allowed_origins']) ? trim($options['wpgraphql_send_mail_allowed_origins']) : ''; ?></textarea>
<?php
}

function wpgraphql_send_mail_cc_render()
{
  $options = get_option('wpgraphql_send_mail_settings');
?>
  <input type="email" name='wpgraphql_send_mail_settings[wpgraphql_send_mail_cc]' value="<?php echo isset($options['wpgraphql_send_mail_cc']) ? trim($options['wpgraphql_send_mail_cc']) : ''; ?>" />
<?php
}
function wpgraphql_send_mail_to_render()
{
  $options = get_option('wpgraphql_send_mail_settings');
?>
  <input type="email" name='wpgraphql_send_mail_settings[wpgraphql_send_mail_to]' value="<?php echo isset($options['wpgraphql_send_mail_to']) ? trim($options['wpgraphql_send_mail_to']) : ''; ?>" />
<?php
}

function wpgraphql_send_mail_from_render()
{
  $options = get_option('wpgraphql_send_mail_settings');
?>
  <input type="email" name='wpgraphql_send_mail_settings[wpgraphql_send_mail_from]' value="<?php echo isset($options['wpgraphql_send_mail_from']) ? trim($options['wpgraphql_send_mail_from']) : ''; ?>" />
<?php
}


function wpgraphql_send_mail_settings_section_callback()
{
  echo __('Enter a comma separated list of domains that can send emails, remembering to include the protocol e.g. https://wordpress.com', 'wp-graphql-send-mail');
}

function wpgraphql_send_mail_options_page()
{
?>
  <form action='options.php' method='post'>

    <h2>WPGraphQl Send Mail Settings</h2>

    <?php
    settings_fields('wsmPlugin');
    do_settings_sections('wsmPlugin');
    submit_button();
    ?>

  </form>
<?php
}


# This is the action that is executed as the GraphQL Schema is being built
add_action('graphql_register_types', function () {

  # This function registers a mutation to the Schema.
  # The first argument, in this case `emailMutation`, is the name of the mutation in the Schema
  # The second argument is an array to configure the mutation.
  # The config array accepts 3 key/value pairs for: inputFields, outputFields and mutateAndGetPayload.
  register_graphql_mutation('sendEmail', [

    # inputFields expects an array of Fields to be used for inputting values to the mutation
    'inputFields'         => [
      'to' => [
        'type' => 'String',
        'description' => __('Who to send the email to', 'wp-graphql-send-mail'),
      ],
      'from' => [
        'type' => 'String',
        'description' => __('Who to send the email from', 'wp-graphql-send-mail'),
      ],
      'replyTo' => [
        'type' => 'String',
        'description' => __('Reply to address', 'wp-graphql-send-mail'),
      ],
      'subject' => [
        'type' => 'String',
        'description' => __('Subject of email', 'wp-graphql-send-mail'),
      ],
      'body' => [
        'type' => 'String',
        'description' => __('Body of email', 'wp-graphql-send-mail'),
      ],
    ],

    # outputFields expects an array of fields that can be asked for in response to the mutation
    # the resolve function is optional, but can be useful if the mutateAndPayload doesn't return an array
    # with the same key(s) as the outputFields
    'outputFields'        => [
      'sent' => [
        'type' => 'Boolean',
        'description' => __('Was the email sent', 'wp-graphql-send-mail'),
        'resolve' => function ($payload, $args, $context, $info) {
          return isset($payload['sent']) ? $payload['sent'] : null;
        }
      ],
      'origin' => [
        'type' => 'String',
        'description' => __('Origin that sent the request', 'wp-graphql-send-mail'),
        'resolve' => function ($payload, $args, $context, $info) {
          return isset($payload['origin']) ? $payload['origin'] : null;
        }
      ],
      'to' => [
        'type' => 'String',
        'description' => __('Who the email got sent to', 'wp-graphql-send-mail'),
        'resolve' => function ($payload, $args, $context, $info) {
          return isset($payload['to']) ? $payload['to'] : null;
        }
      ],
      'replyTo' => [
        'type' => 'String',
        'description' => __('reply To address used', 'wp-graphql-send-mail'),
        'resolve' => function ($payload, $args, $context, $info) {
          return isset($payload['replyTo']) ? $payload['replyTo'] : null;
        }
      ],
      'message' => [
        'type' => 'String',
        'description' => __('Message', 'wp-graphql-send-mail'),
        'resolve' => function ($payload, $args, $context, $info) {
          return isset($payload['message']) ? $payload['message'] : null;
        }
      ]
    ],

    # mutateAndGetPayload expects a function, and the function gets passed the $input, $context, and $info
    # the function should return enough info for the outputFields to resolve with
    'mutateAndGetPayload' => function ($input, $context, $info) {

      // Do any logic here to sanitize the input, check user capabilities, etc
      $options = get_option('wpgraphql_send_mail_settings');
      $allowedOrigins = array_map('trim', explode(',', $options['wpgraphql_send_mail_allowed_origins']));
      $cc = trim($options['wpgraphql_send_mail_cc']);
      $defaultFrom = trim($options['wpgraphql_send_mail_from']);
      $defaultTo = trim($options['wpgraphql_send_mail_to']);
      $http_origin = trim($_SERVER['HTTP_ORIGIN']);
      $message = null;
      $canSend = false;
      $to = isset($input['to']) ? trim($input['to']) : trim($defaultTo);
      $replyTo = trim($input['replyTo']) ;

      if ($allowedOrigins) {
        if (in_array($http_origin, $allowedOrigins)) {
          $canSend = true;
        } else {
          $message = __('Origin not allowed, set origin in settings', 'wp-graphql-send-mail');
        }
      } else {
        // if they did not enter any then we will allow any
        $canSend = true;
      }

      // Above tests passed and there is a to address and an email body
      if ($canSend && !empty($to)  && !empty($input['body'])) {


        $subject = trim($input['subject']);
        $body = $input['body'];
        $from = trim($input['from']);
        $headers = array('Content-Type: text/html; charset=UTF-8');

        if (isset($cc) && !empty($cc)) {
          $headers[] = 'Cc: ' . $cc;
        }

        if (isset($replyTo) && !empty($replyTo)) {
          $headers[] = 'Reply-To: ' . $replyTo;
        }
   

        if (isset($from) && !empty($from)) {
          $headers[] = 'From: ' . $from;
        } else if (isset($defaultFrom) && !empty($defaultFrom)) {
          $headers[] = 'From: ' . $defaultFrom;
        }

        $sent = wp_mail($to, $subject, $body, $headers);

        $message = $sent ? __('Email Sent', 'wp-graphql-send-mail') : __('Email failed to send', 'wp-graphql-send-mail');
      } else {
        $sent = false;
        $message =  $message ? $message : __('Email Not Sent', 'wp-graphql-send-mail');
      }

      return [
        'sent' => $sent,
        'origin' => $http_origin,
        'to' => $to,
        'replyTo' => $replyTo,
        'message' => $message,
      ];
    }
  ]);
});
