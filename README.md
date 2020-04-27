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

Account
-------

```php
// ...
// return the account api
$account = $awx->account();

// return the Account entity
$userInformation = $awx->getUserInformation();
````

Action
------

```php
// ..
// return the action api
$action  = $awx->action();

// return a collection of Action entity
$actions = $action->getAll();

// return the Action entity 123
$action123 = $action->getById(123);
```

Domain
------

```php
// ..
// return the domain api
$domain = $awx->domain();

// return a collection of Domain entity
$domains = $domain->getAll();

// return the Domain entity 'foo.dk'
$domainFooDk = $domain->getByName('foo.dk');

// return the created Domain named 'bar.dk' and pointed to ip '127.0.0.1'
$created = $domain->create('bar.dk', '127.0.0.1');

// delete the domain named 'baz.dk'
$domain->delete('baz.dk');

```

Domain Record
-------------

```php
// ..
// return the domain record api
$domainRecord = $awx->domainRecord();

// return a collection of DomainRecord entity of the domain 'foo.dk'
$domainRecords = $domainRecord->getAll('foo.dk');

// return the DomainRecord entity 123 of the domain 'foo.dk'
$domainRecord123 = $domainRecord->getById('foo.dk', 123);

// return the created DomainRecord entity of the domain 'bar.dk'
$created = $domainRecord->create('bar.dk', 'AAAA', 'bar-name', '2001:db8::ff00:42:8329');

// return the DomainRecord entity 123 of the domain 'baz.dk' updated with new-name, new-data, priority 1, port 2, weight 3, flags 0, tag issue (name, data, priority, port, weight, flags and tag are nullable)
$updated = $domainRecord->update('baz.dk', 123, 'new-name', 'new-data', 1, 2, 3, 0, 'issue');

// delete domain record 123 of the domain 'qmx.dk'
$domainRecord->delete('qmx.dk', 123);
```

Droplet
-------

```php
// ..
// return the droplet api
$droplet = $awx->droplet();

// return a collection of Droplet entity
$droplets = $droplet->getAll();

// return a collection of Droplet neighbor to Droplet entity 123
$droplets = $droplet->getNeighborsById(123);

// return a collection of Droplet that are running on the same physical hardware
$neighbors = $droplet->getAllNeighbors();

// return a collection of Upgrade entity
$upgrades = $droplet->getUpgrades();

// return the Droplet entity 123
$droplet123 = $droplet->getById(123);

// create and return the created Droplet entity
$created = $droplet->create('the-name', 'nyc1', '512mb', 449676388);

// create and return the created Droplet entity using an image slug
$created = $droplet->create('the-name', 'nyc1', '512mb', 'ubuntu-14-04-x64');

// delete the droplet 123
$droplet->delete(123);

// return a collection of Kernel entity
$kernels = $droplet->getAvailableKernels(123);

// return a collection of Image entity
$images = $droplet->getSnapshots(123);

// return a collection of Image entity
$backups = $droplet->getBackups(123);

// return a collection og Action entity of the droplet 123
$actions = $droplet->getActions(123);

// return the Action entity 456 of the droplet 123
$action123 = $droplet->getActionById(123, 456);

// reboot droplet 123 and return the Action entity
$rebooted = $droplet->reboot(123);

// power cycle droplet 123 and return the Action entity
$powerCycled = $droplet->powerCycle(123);

// shutdown droplet 123 and return the Action entity
$shutdown = $droplet->shutdown(123);

// power off droplet 123 and return the Action entity
$powerOff = $droplet->powerOff(123);

// power on droplet 123 and return the Action entity
$powerOn = $droplet->powerOn(123);

// reset password droplet 123 and return the Action entity
$passwordReseted = $droplet->passwordReset(123);

// resize droplet 123 with the image 789 and return the Action entity
$resized = $droplet->resize(123, 789);

// restore droplet 123 with the image 789 and return the Action entity
$restored = $droplet->restore(123, 789);

// rebuild droplet 123 with image 789 and return the Action entity
$rebuilt = $droplet->rebuild(123, 789);

// rename droplet 123 to 'new-name' and return the Action entity
$renamed = $droplet->rename(123, 'new-name');

// take a snapshot of droplet 123 and name it 'my-snapshot'. Returns the an Action entity
$snapshot = $droplet->snapshot(123, 'my-snapshot');

// change kernel to droplet 123 with kernel 321 and return the Action entity
$kernelChanged = $droplet->changeKernel(123, 321);

// enable IPv6 to droplet 123 and return the Action entity
$ipv6Enabled = $droplet->enableIpv6(123);

// disable backups to droplet 123 and return the Action entity
$backupsDisabled = $droplet->disableBackups(123);

// enable private networking to droplet 123 and return the Action entity
$privateNetworkingEnabled = $droplet->enablePrivateNetworking(123);
```

Image
-----

```php
// ..
// return the image api
$image = $awx->image();

// return a collection of Image entity
$images = $image->getAll();

// return a collection of distribution Image entity
$images = $image->getAll(['type' => 'distribution']);

// return a collection of application Image entity
$images = $image->getAll(['type' => 'application']);

// return a collection of private Image entity
$images = $image->getAll(['private' => true]);

// return a collection of private application Image entity
$images = $image->getAll(['type' => 'application', 'private' => true]);

// return the Image entity 123
$image123 = $image->getById(123);

// return the Image entity with the given image slug
$imageFoobar = $image->getBySlug('foobar');

// return the updated Image entity
$updatedImage = $image->update(123, 'new-name');

// delete the image 123
$image->delete(123);

// return the Action entity of the transferred image 123 to the given region slug
$transferredImage = $image->transfer(123, 'region-slug');

// return the Action entity 456 of the image 123
$actionImage = $image->getAction(123, 456);
```

Key
---

```php
// ..
// return the key api
$key = $awx->key();

// return a collection of Key entity
$keys = $key->getAll();

// return the Key entity 123
$key123 = $key->getById(123);

// return the Key entity with the given fingerprint
$key = $key->getByFingerprint('f5:de:eb:64:2d:6a:b6:d5:bb:06:47:7f:04:4b:f8:e2');

// return the created Key entity
$createdKey = $key->create('my-key', 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDPrtBjQaNBwDSV3ePC86zaEWu0....');

// return the updated Key entity
$updatedKey = $key->update(123, 'new-key-name');

// return void if deleted successfully
$key->delete(123);
```

Load Balancer
-------------

```php
// ..
// return the load balancer api
$loadBalancer = $awx->loadbalancer();

//returns a collection of Load Balancer entities
$loadBalancers = $loadBalancer->getAll();

//return a Load Balancer entity by id
$myLoadBalancer = $loadBalancer->getById('506f78a4-e098-11e5-ad9f-000f53306ae1');

/**
* updates an existing load balancer, the method will except a LoadBalancer
* entity or a load balancer representation in array form, the digitial
* Ocean API requires a full representation of your load
* balancer, any attribute that is missing will
* be reset to it's default setting.
*/
$myUpdatedLoadBalancer = $loadBalancer->update('506f78a4-e098-11e5-ad9f-000f53306ae1', $myLoadBalancer);

//create a standard load balancer that listens on port 80 and 443 with ssl passthrough enabled
$myNewLoadBalancer = $loadBalancer->create('my-new-load-balancer', 'nyc1');
```

Region
------

```php
// ..
// return the region api
$region = $digitalocean->region();

// return a collection of Region entity
$regions = $region->getAll();
```

Size
----

```php
// ..
// return the size api
$size = $digitalocean->size();

// return a collection of Size entity
$sizes = $size->getAll();
```

RateLimit
---------

```php
// ..
// returns the rate limit api
$rateLimit = $digitalocean->rateLimit();

// returns the rate limit returned by the latest request
$currentLimit = $rateLimit->getRateLimit();
```

Volume
------

```php
// ..
// return the volume api
$volume = $digitalocean->volume();

// returns the all volumes
$volumes = $volume->getAll();

// returns the all volumes by region
$volumes = $volume->getAll('nyc1');

// returns volumes by name and region
$volumes = $volume->getByNameAndRegion('example', 'nyc1');

// returns a volume by id
$myvolume = $volume->getById('506f78a4-e098-11e5-ad9f-000f53306ae1');

// returns a volumes snapshots by volume id
$mySnapshots = $volume->getSnapshots('506f78a4-e098-11e5-ad9f-000f53306ae1');

// creates a volume
$myvolume = $volume->create('example', 'Block store for examples', 10, 'nyc1');

// deletes a volume by id
$volume->delete('506f78a4-e098-11e5-ad9f-000f53306ae1');

// deletes a volume by name and region
$volume->delete('example', 'nyc1');

// attach a volume to a Droplet 
$volume->attach('506f78a4-e098-11e5-ad9f-000f53306ae1', 123, 'nyc1');

// detach a volume from a Droplet 
$volume->detach('506f78a4-e098-11e5-ad9f-000f53306ae1', 123, 'nyc1');

// resize a volume 
$volume->resize('506f78a4-e098-11e5-ad9f-000f53306ae1', 20, 'nyc1');

// take a snapshot of volume and name it 'my-snapshot'. Returns the Snapshot entity
$snapshot = $volume->snapshot('506f78a4-e098-11e5-ad9f-000f53306ae1', 'my-snapshot');

// get a volume action by its id 
$volume->getActionById(123, '506f78a4-e098-11e5-ad9f-000f53306ae1');

// get all actions related to a volume
$volume->getActions('506f78a4-e098-11e5-ad9f-000f53306ae1');

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
