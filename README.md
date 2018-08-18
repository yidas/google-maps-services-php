<p align="center">
    <a href="https://cloud.google.com/maps-platform/" target="_blank">
        <img src="https://cloud.google.com/images/maps-platform/google-maps-lockup.svg" width="300px">
    </a>
    <h1 align="center">Google Maps Services <i>for</i> PHP</h1>
    <br>
</p>

PHP client library(SDK) for Google Maps API Web Services

[![Latest Stable Version](https://poser.pugx.org/yidas/google-maps-services/v/stable?format=flat-square)](https://packagist.org/packages/yidas/google-maps-services)
[![License](https://poser.pugx.org/yidas/google-maps-services/license?format=flat-square)](https://packagist.org/packages/yidas/google-maps-services)
[![Total Downloads](https://poser.pugx.org/yidas/google-maps-services/downloads?format=flat-square)](https://packagist.org/packages/yidas/google-maps-services)
[![Monthly Downloads](https://poser.pugx.org/yidas/google-maps-services/d/monthly?format=flat-square)](https://packagist.org/packages/yidas/google-maps-services)


OUTLINE
-------

- [Demonstration](#demonstration)
- [Description](#description)
- [Requirements](#requirements)
    - [API keys](#api-keys)
- [Installation](#installation)
- [Usage](#usage)
    - [Client](#client)
        - [Google Maps APIs Premium Plan license](#google-maps-apis-premium-plan-license)
        - [Language](#language)
    - [Directions API](#directions-api)
    - [Distance Matrix API](#distance-matrix-api)
    - [Elevation API](#elevation-api)
    - [Geocoding API](#geocoding-api)
    - [Geolocation API](#geolocation-api)
    - [Time Zone API](#time-zone-api)

---

DEMONSTRATION
-------------


```php
$gmaps = new \yidas\googleMaps\Client(['key'=>'Your API Key']);

// Geocoding an address
$geocodeResult = $gmaps->geocode('Taipei 101, Taipei City, Taiwan 110');

// Look up an address with reverse geocoding
$reverseGeocodeResult = $gmaps->reverseGeocode([25.0339639, 121.5644722]);

// Request directions via public transit
$directionsResult = $gmaps->directions('National Palace Museum', 'Taipei 101', [
    'mode' => "transit",
    'departure_time' => time(),
    ]);
```

---


DESCRIPTION
-----------

The PHP Client for Google Maps Services is a PHP Client library for the following Google Maps APIs:

 - [Directions API]
 - [Distance Matrix API]
 - [Elevation API]
 - [Geocoding API]
 - [Geolocation API]
 - [Time Zone API]
 - [Roads API] (Required)
 - [Places API] (Required)

---

REQUIREMENTS
------------
This library requires the following:

- PHP 5.4.0+\|7.0+
- guzzlehttp/guzzle 5.3.1+\|6.0+
- Google Maps API key or credential

### API keys

Each Google Maps Web Service request requires an API key or client ID. API keys
are freely available with a Google Account at
https://developers.google.com/console. The type of API key you need is a
**Server key**.

To get an API key:

 1. Visit https://developers.google.com/console and log in with
    a Google Account.
 2. Select one of your existing projects, or create a new project.
 3. Enable the API(s) you want to use. The Client for Google Maps Services
    accesses the following APIs:
    * Directions API
    * Distance Matrix API
    * Elevation API
    * Geocoding API
    * Geolocation API
    * Places API
    * Roads API
    * Time Zone API
 4. Create a new **Server key**.
 5. If you'd like to restrict requests to a specific IP address, do so now.

For guided help, follow the instructions for the [Directions API][directions-key]. You only need one API key, but remember to enable all the APIs you need.
For even more information, see the guide to [API keys][apikey].

**Important:** This key should be kept secret on your server.

---

INSTALLATION
------------

Run Composer in your project:

    composer require yidas/google-maps-services
    
Then you could call it after Composer is loaded depended on your PHP framework:

```php
require __DIR__ . '/vendor/autoload.php';

use yidas\googleMaps\Client;
```

---

USAGE
-----

Before using any Google Maps Services, first you need to create a Client with configuration, then use the client to access Google Maps Services.

### Client

Create a Client using [API key]((#api-keys)):

```php
$gmaps = new \yidas\googleMaps\Client(['key'=>'Your API Key']);
```

#### Google Maps APIs Premium Plan license

If you use [Google Maps APIs Premium Plan license](https://developers.google.com/maps/documentation/directions/get-api-key#client-id) instead of an API key, you could create Client using client ID and client secret (digital signature) for authentication.

```php
$gmaps = new \yidas\googleMaps\Client([
    'clientID' => 'Your client ID', 
    'clientSecret' => 'Your digital signature'
    ]);
```

#### Language

You could set language for Client for all services:

```php
$gmaps = new \yidas\googleMaps\Client(['key'=>'Your API Key', 'language'=>'zh-TW']);
```

Changing language during execution:

```php
$gmaps->setLanguage('zh-TW');
// ...
```

### Directions API

```php
// Request directions via public transit
$directionsResult = $gmaps->directions('National Palace Museum', 'Taipei 101', [
    'mode' => "transit",
    'departure_time' => time(),
    ]);
```


### Distance Matrix API

```php
// Get the distance matrix data between two places
$distanceMatrixResult = $gmaps->distanceMatrix('National Palace Museum', 'Taipei 101');

// With Imperial units
$distanceMatrixResult = $gmaps->distanceMatrix('National Palace Museum', 'Taipei 101', [
    'units' => 'imperial',
    ]);
```

### Elevation API

```php
// Get elevation by locations parameter
$elevationResult = $gmaps->elevation([25.0339639, 121.5644722]);
$elevationResult = $gmaps->elevation('25.0339639, 121.5644722');
```

### Geocoding API

```php
// Geocoding an address
$geocodeResult = $gmaps->geocode('Taipei 101, Taipei City, Taiwan 110');

// Look up an address with reverse geocoding
$reverseGeocodeResult = $gmaps->reverseGeocode([25.0339639, 121.5644722]);
```

### Geolocation API

```php
// Simple geolocate
$geolocateResult = $gmaps->geolocate([]);
```

### Time Zone API

```php
// requests the time zone data for giving location
$timezoneResult = $gmaps->timezone([25.0339639, 121.5644722]);
```



[Google Maps API Web Services]: https://developers.google.com/maps/documentation/webservices/
[Directions API]: https://developers.google.com/maps/documentation/directions/
[directions-key]: https://developers.google.com/maps/documentation/directions/get-api-key#key
[directions-client-id]: https://developers.google.com/maps/documentation/directions/get-api-key#client-id
[Distance Matrix API]: https://developers.google.com/maps/documentation/distancematrix/
[Elevation API]: https://developers.google.com/maps/documentation/elevation/
[Geocoding API]: https://developers.google.com/maps/documentation/geocoding/
[Geolocation API]: https://developers.google.com/maps/documentation/geolocation/
[Time Zone API]: https://developers.google.com/maps/documentation/timezone/
[Roads API]: https://developers.google.com/maps/documentation/roads/
[Places API]: https://developers.google.com/places/
