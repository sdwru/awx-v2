### WORK IN PROGRESS

I am adding things as I need them so not all API features are implemented.  It should be relatively easy to look through the code and add anything that is not there yet.

#### Ansible Tower / AWX API V2

This is based on the Digital Ocean API V2
https://github.com/toin0u/DigitalOceanV2

===============

Installation
------------

If installing on Laravel use the install instructions at https://github.com/sdwru/Laravel-AwxV2. This library depends on oauth2-awx for obtaining tokens.  That package is based on a separate project and uses it's own instance of guzzlehttp independent of what this package does for the http client.

You can install the bindings via Composer. If installing standalone to some generic php framework add the following to your composer.json
```
"require": {
    "sdwru/awx-v2": "dev-master",
    "sdwru/oauth2-awx": "dev-master"
},
"repositories": [
    { "type": "git", "url": "https://github.com/sdwru/awx-v2.git" },
    { "type": "git", "url": "https://github.com/sdwru/oauth2-awx.git" }
],
```
And run `composer update` from cli.

To use the bindings, use Composer's autoload:
```
require_once('vendor/autoload.php');
```

This package should install guzzle automatically but if not install as follows:
```bash
$ php composer.phar require guzzlehttp/guzzle:^6.3
```

And then add **one** of the following:

```json
{
    "require": {
        "guzzlehttp/guzzle": "^6.0"
    }
}
```
## Oauth2

This library relies on the [AWX oauth2 client](https://github.com/sdwru/oauth2-awx) for obtaining tokens.

Ansible AWX uses Oauth 2 for generating access tokens.  This library assumes a high level of trust between your PHP application and AWX and therefore uses [password grant type](https://oauth.net/2/grant-types/password/) for creating the initial bearer token and refresh token.  Although this is not ideal, it is the only appropriate type for full time backend api integration currently provided by Ansible Tower / AWX.  If the developers of Ansible Tower / AWX decide to add [client credentials](https://www.oauth.com/oauth2-servers/access-tokens/client-credentials/) that would then be more suitable.

Using password grant requires that AWX be configured with an AWX user consisting of a username and password.  Once the API user is created in the AWX GUI logged in as admistrator, go to Applications and create a new application with password grant type.  When that is created it will provide a client ID and client secret.  Save a record of client secret as it is only shown once and cannot be retrieved ever again.

Add the above 4 pieces of information somewhere in your PHP application that is not tracked by your version control system.  In Laravel that file is typically `.env`.  Once you have added the information you can create a bearer token and refresh token using the following example code.

Example
-------

```php
<?php

require 'vendor/autoload.php';

$awxVars = array (
    'clientId' => 'AWX_CLIENT_ID', // The client ID assigned by AWX when you created the application
    'clientSecret' => 'AWX_CLIENT_SECRET',
    'username' => 'AWX_USERNAME', // The AWX username associated with the application
    'password' => 'AWX_PASSWORD',
    'apiUrl' => 'AWX_API_URL', // Ie. https://x.x.x.x/api
    'sslVerify' => false, //SSL verify can be false during development and true after public SSL certificates are obtained
    );


// Create oauth2 object
$oauth2 = new \AwxV2\Oauth\Oauth2($awxVars);

// Get access and refresh tokens and expire time
$tokens = $oauth2->passCredGrant();

// Get access token
$accessToken = $tokens->getToken();

// create an adapter and add access token
$adapter = new \AwxV2\Adapter\GuzzleHttpAdapter($accessToken, $awxVars['sslVerify']);

// create an Awx object with the previous adapter
$awx = new \AwxV2\AwxV2($adapter, $awxVars['apiUrl']);

// ...
```

Me
-------

```php
// ...
// return the the account api
$me = $awx->me();

// Get the info for the account
$userInformation = $me->getAll();
````

Job Template
------

```php
// ..
// return the job template api
$jobTemplate  = $awx->jobTemplate();

// return a collection of job template entity
$actions = $jobTemplate->getAll();

// return the Job Template entity 123
$JobTemplate123 = $jobTemplate->getById(123);
```

Contributing
------------

Please see [CONTRIBUTING](https://github.com/toin0u/AwxV2/blob/master/CONTRIBUTING.md) for details.

Changelog
---------

Please see [CHANGELOG](https://github.com/toin0u/AwxV2/blob/master/CHANGELOG.md) for details.

Credits
-------

* [Antoine Corcy](https://twitter.com/toin0u)
* [Graham Campbell](https://twitter.com/GrahamCampbell)
* [Yassir Hannoun](https://twitter.com/yassirh)
* [Liverbool](https://github.com/liverbool)
* [Marcos Sigueros](https://github.com/alrik11es)
* [Chris Fidao](https://github.com/fideloper)
* [All contributors](https://github.com/toin0u/AwxV2/contributors)

Support
-------

[Please open an issue in github](https://github.com/toin0u/AwxV2/issues)

Contributor Code of Conduct
---------------------------

As contributors and maintainers of this project, we pledge to respect all people
who contribute through reporting issues, posting feature requests, updating
documentation, submitting pull requests or patches, and other activities.

We are committed to making participation in this project a harassment-free
experience for everyone, regardless of level of experience, gender, gender
identity and expression, sexual orientation, disability, personal appearance,
body size, race, age, or religion.

Examples of unacceptable behavior by participants include the use of sexual
language or imagery, derogatory comments or personal attacks, trolling, public
or private harassment, insults, or other unprofessional conduct.

Project maintainers have the right and responsibility to remove, edit, or reject
comments, commits, code, wiki edits, issues, and other contributions that are
not aligned to this Code of Conduct. Project maintainers who do not follow the
Code of Conduct may be removed from the project team.

Instances of abusive, harassing, or otherwise unacceptable behavior may be
reported by opening an issue or contacting one or more of the project
maintainers.

This Code of Conduct is adapted from the [Contributor
Covenant](http:contributor-covenant.org), version 1.0.0, available at
[http://contributor-covenant.org/version/1/0/0/](http://contributor-covenant.org/version/1/0/0/).

License
-------

AwxV2 is released under the MIT License. See the bundled
[LICENSE](https://github.com/toin0u/AwxV2/blob/master/LICENSE) file for details.
