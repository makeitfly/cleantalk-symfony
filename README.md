# Cleantalk Symfony bundle

Unofficial [Cleantalk](https://cleantalk.org/) anti-spam integration for 
Symfony. Currently only provides the most basic anti-spam protection, as per 
the docs of the [php-antispam](https://github.com/CleanTalk/php-antispam) 
package.

## Installation

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

By default your Symfony application automatically performs a `cache:clear` after 
a `composer require`. We haven't submitted a Flex recipe (yet), so this will 
error out because of missing configuration. For now, ideally create the config 
file before you require the bundle, as per the 
[configuration section](#configuration).

### Applications that use Symfony Flex

Open a command console, enter your project directory and execute:

```console
$ composer require makeitfly/cleantalk-symfony
```

### Applications that don't use Symfony Flex

#### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require makeitfly/cleantalk-symfony
```

#### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    MakeItFly\CleanTalkBundle\MakeItFlyCleanTalkBundle::class => ['all' => true],
];
```

## Configuration

All you need is the auth key, which you can find in your CleanTalk dashboard. 

```yaml
# config/packages/makeitfly_cleantalk.yaml
makeitfly_cleantalk:
    enabled: true
    auth_key: '%env(MAKEITFLY_CLEANTALK_AUTH_KEY)'

    # Other optional config.
    server_url: "https://moderate.cleantalk.org/api2.0/" # CleanTalk API endpoint
    agent: "makeitfly-symfony" # Sent on every API request
```

It is recommended to create a development configuration that disables the 
validation for your development environment.

```yaml
# config/packages/dev/makeitfly_cleantalk.yaml
makeitfly_cleantalk:
    enabled: false
```

## Usage

Add the `CleanTalkType` as a field to your form. It automatically defines a 
constraint that will be validated when you call `$form->isValid()`. 

Example usage:

```php
public function buildForm(
    FormBuilderInterface $builder,
    array $options
): void {
    $builder
        ->add('message', TextareaType::class, [
            'label' => 'form.contact.message.label'
        ])
        ->add('email', EmailType::class, [
            'label' => 'form.contact.email.label'
        ])
        ->add('cleantalk', CleanTalkType::class, [
            'sender_email_field' => 'email',
            'message_field' => 'message'
        ]);
}
```

### Passing form data

The CleanTalk API checks if a message is spam based on the submitted form 
values. The sender email is the only required field, but it is recommended to
pass as much data as you have for better spam detection.

When you configure these fields as a string, the library will use these to fetch
the data from the form data. This requires the `symfony/property-access` to be 
installed.

Alternatively you can use a callback function to return a custom value yourself.

```php
// Minimal configuration:
$builder->add('cleantalk', CleanTalkType::class, [
    'sender_email_field' => 'email', // Required
]);

// Full configuration:
$builder->add('cleantalk', CleanTalkType::class, [
    // One of CleanTalkCheck::MESSAGE or CleanTalkCheck::USER. This defines
    // which CleanTalk API endpoint is used:
    // @see https://cleantalk.org/help/api-check-message
    // @see https://cleantalk.org/help/api-check-newuser
    'check_type' => CleanTalkCheck::MESSAGE,
    // The email address of the person who submits the form. This will be
    // fetched from the 'someProperty' property of the form data.
    'sender_email_field' => 'someProperty',
    // Alternatively, for this and the following properties you can use a
    // callable instead, which will get passed the form data.
    'sender_email_field' => function ($formData) {
        // Use the form data directly here, or for example fetch the current
        // logged in account and get the email that way.
        return $formData->getEmail();
    },
    'sender_nickname_field' => 'senderNickname',
    'phone_field' => 'phone',
    'message_field' => 'message'
]);
```

### Roadmap

Following points are nice-to-haves:

- Cleantalk returns why a submission is refused. This could be logged.
