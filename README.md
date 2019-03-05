# Teamleader API v2 PHP SDK

This is a PHP wrapper for the Teamleader API v2. Documentation of the API can be found here: [https://developer.teamleader.eu/]().
Please note that the Teamleader API v2 is not complete and some Entities and Actions are missing.

Currently we only support a few entities and per entity only a few actions:

1.  Company (create and update)
2.  Contact (create, update, linkToCompany)
3.  Deal (create and update)
4.  Deal phases (get)
5.  Deal sources (get)
6.  Activity types (get)
7.  Event (create, update, delete, get, getById)
8.  Business types (get)
9.  Tags (get)
10. Invoice (get)
11. Credit notes (get)
12. Payment terms (get)
13. Tax rates (get)
14. Withholding tax rates (get)
15. Departments (get)
16. User (me, get, getById)

Teamleader API v2 works with OAuth2. This means your application needs to be registered on the [Teamleader Marketplace](https://marketplace.teamleader.eu/nl/nl/ontwikkel/integraties) (you can keep them private though).

Please contact me at jan@jannesmannes.nl if you have any feedback or questions.

# Examples

See all examples in the examples directory.

## Request an access token

You need to do this once, after you get an access token you can use the refresh token to obtain a new access token after it expires.

```
<?php
require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/credentials.php';

$redirectUrl = 'https://teamleader-php-sdk.dev/examples/acquire-access-token.php';

$connection = new \Teamleader\Connection();
$connection->setClientId( $clientId );
$connection->setClientSecret( $clientSecret );
$connection->setRedirectUrl( $redirectUrl );

$connection->acquireAccessToken();
```

## Create a company

```
$company = $client->company( [
    'name' => 'Test API v2',
] )->save();
```

## Get deal phases

```
$dealPhases = $client->dealPhase()->get();
```

# TODO

- [ ] Support custom actions on Deals
- [ ] Add all other Entities

# Change log

## 1.2.0 (2018-08-23)
[Full change log](https://github.com/janhenkes/teamleader-php-sdk/compare/1.1.1...1.2.0)

**Features:**

- Added Contact->linkToCompany Action

## 1.1.1 (2018-08-23)
[Full change log](https://github.com/janhenkes/teamleader-php-sdk/compare/1.1.0...1.1.1)

**Features:**

- Added readme

**Fixes:**

- Switched back to GuzzleHttp 6


## 1.1.0 (2018-08-22)
[Full change log](https://github.com/janhenkes/teamleader-php-sdk/compare/1.0.2...1.1.0)

**Features:**

- Added more examples
- Implemented FindAll action
- Added DealPhase entity
- Added DealSource entity
