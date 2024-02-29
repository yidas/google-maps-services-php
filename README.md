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
    - [APIs](#apis)

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

The PHP Client for Google Maps Services is a PHP Client library for the following [Google Maps APIs](https://developers.google.com/maps):

- Maps
    - [Elevation API](#elevation-api) ([Google Doc](https://developers.google.com/maps/documentation/elevation/))
- Routes
    - [Routes API](#routes-api) ([Google Doc](https://developers.google.com/maps/documentation/routes))
    - [Roads API](#roads-api) ([Google Doc](https://developers.google.com/maps/documentation/roads))
    - [Directions API](#directions-api) ([Google Doc](https://developers.google.com/maps/documentation/directions/))
    - [Distance Matrix API](#distance-matrix-api) ([Google Doc](https://developers.google.com/maps/documentation/distancematrix/))
- Places
    - [Places API] (TBD)
    - [Geocoding API](#geocoding-api) ([Google Doc](https://developers.google.com/maps/documentation/geocoding/))
    - [Geolocation API](#geolocation-api) ([Google Doc](https://developers.google.com/maps/documentation/geolocation/))
    - [Time Zone API](#time-zone-api) ([Google Doc](https://developers.google.com/maps/documentation/timezone/))

---

REQUIREMENTS
------------

- PHP 7.0+ or higher

### API keys

Each Google Maps Web Service request requires an API key or client ID. API keys
are freely available with a Google Account at
https://developers.google.com/console. The type of API key you need is a
**Server key**.

To get an API key:

 1. Visit https://developers.google.com/console and log in with
    a Google Account.
 2. Select one of your existing projects, or create a new project.
 3. Enable the Google Maps Services API(s) you plan to use, such as:
    * Directions API
    * Distance Matrix API
    * Geocoding API
    * ...
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

> [list of supported languages - Google Maps Platform](https://developers.google.com/maps/faq#languagesupport)

Changing language during execution:

```php
$gmaps->setLanguage('zh-TW');
// ...
```

### APIs

#### Elevation API

[Elevation API overview | Google for Developers](https://developers.google.com/maps/documentation/elevation/overview)

```php
// Get elevation by locations parameter
$elevationResult = $gmaps->elevation([25.0339639, 121.5644722]);
$elevationResult = $gmaps->elevation('25.0339639, 121.5644722');
```

#### Routes API

[Get a route | Google for Developers](https://developers.google.com/maps/documentation/routes/compute_route_directions)

```php
$routes = $gmaps->computeRoutes($originArray, $destinationArray, $fullBodyArray, $fieldMask)

// Get the route data between two places simply
$routes = $gmaps->computeRoutes([
        "location" => [
           "latLng" => [
              "latitude" => 37.419734,
              "longitude" => -122.0827784
           ]
        ]
    ],
    [
        "location" => [
           "latLng" => [
              "latitude" => 37.41767,
              "longitude" => -122.079595
           ]
        ]
    ]);

// Get the full route data between two places with full request data
$routes = $gmaps->computeRoutes([...], [...], ["travelMode": "DRIVE", ...], '*');
```

#### Roads API

[Snap to Roads  | Google for Developers](https://developers.google.com/maps/documentation/roads/snap)

```php
$roads = $gmaps->snapToRoads([[-35.27801,149.12958], [-35.28032,149.12907], [-35.28099,149.12929]]);
```

#### Directions API

[Getting directions | Google for Developers](https://developers.google.com/maps/documentation/directions/get-directions)

```php
// Request directions via public transit
$directionsResult = $gmaps->directions('National Palace Museum', 'Taipei 101', [
    'mode' => "transit",
    'departure_time' => time(),
    ]);
```


#### Distance Matrix API

[Get started with the Distance Matrix API | Google for Developers](https://developers.google.com/maps/documentation/distance-matrix/start)

```php
// Get the distance matrix data between two places
$distanceMatrixResult = $gmaps->distanceMatrix('National Palace Museum', 'Taipei 101');

// With Imperial units
$distanceMatrixResult = $gmaps->distanceMatrix('National Palace Museum', 'Taipei 101', [
    'units' => 'imperial',
    ]);
```

#### Geocoding API

[Geocoding API overview | Google for Developers](https://developers.google.com/maps/documentation/geocoding/overview)

```php
// Geocoding an address
$geocodeResult = $gmaps->geocode('Taipei 101, Taipei City, Taiwan 110');

// Look up an address with reverse geocoding
$reverseGeocodeResult = $gmaps->reverseGeocode([25.0339639, 121.5644722]);
```

#### Geolocation API

[Geolocation API overview | Google for Developers](https://developers.google.com/maps/documentation/geolocation/overview)

```php
// Simple geolocate
$geolocateResult = $gmaps->geolocate([]);
```

#### Time Zone API

[Time Zone API overview | Google for Developers](https://developers.google.com/maps/documentation/timezone/overview)

```php
// requests the time zone data for giving location
$timezoneResult = $gmaps->timezone([25.0339639, 121.5644722]);
```

