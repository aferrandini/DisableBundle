DisableBundle
=============

This Bundle has been created to show how to create a custom Annotation in [deSymfony 2013 Conference](http://desymfony.com).

You will find more information about Annotations at [Annotations: it’s not a part of my program, but it’s my program](http://desymfony.com/ponencia/2013/anotaciones-en-sf2).

This bundle provides an easy way to disable an Action or a Controller.
You will be able to disable directly, after a date/time, until a date/time or by a date/time range.
You also be able to show a disabled message or redirect the request to another route.

[![Build Status](https://secure.travis-ci.org/aferrandini/DisableBundle.png)](http://travis-ci.org/aferrandini/DisableBundle)

## Installation

### Step 1: Install vendors

#### Symfony 2.0.x: `bin/vendors.php` method

If you're using the `bin/vendors.php` method to manage your vendor libraries,
add the following entries to the `deps` in the root of your project file:

```
[FerrandiniDisableBundle]
    git=http://github.com/aferrandini/DisableBundle.git
    target=/bundles/Ferrandini/Bundle/DisableBundle
```

Next, update your vendors by running:

``` bash
$ ./bin/vendors
```

Finally, add the following entry to your autoloader:

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    // ...
    'Ferrandini'        => __DIR__.'/../vendor/bundles',
));
```

#### Symfony >=2.1.x: Composer

[Composer](http://packagist.org/about-composer) is a project dependency manager for PHP. You have to list
your dependencies in a `composer.json` file:

``` json
{
    "require": {
        "aferrandini/disable-bundle": "dev-master"
    }
}
```
To actually install DisableBundle in your project, download the composer binary and run it:

``` bash
wget http://getcomposer.org/composer.phar
# or
curl -O http://getcomposer.org/composer.phar

php composer.phar install
```

### Step 2: Enable the bundle

Finally, enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...

        new Ferrandini\Bundle\DisableBundle\FerrandiniDisableBundle(),
    );
}
```

## Usage

This Bundle provides an easy way to disable a Controller or an Action as you can
see in the following examples.

### Disabling a Controller

``` php
<?php

namespace Foo\Bundle\FooBundle\Controller;

use Ferrandini\Bundle\DisableBundle\Annotations\Disable;

/**
 * @Disable()
 */
class FooController {

}
```

### Disabling an Action

``` php
<?php

namespace Foo\Bundle\FooBundle\Controller;

use Ferrandini\Bundle\DisableBundle\Annotations\Disable;

class FooController {

    /**
     * @Disable()
     */
    public function fooAction()
    {
        // ...
    }
}
```

### Disabling with custom message

``` php
<?php

namespace Foo\Bundle\FooBundle\Controller;

use Ferrandini\Bundle\DisableBundle\Annotations\Disable;

/**
 * @Disable(message="This controller has been disabled with DisableBundle")
 */
class FooController {

}
```

### Disabling by date/time

The date/time has to be defined as a PHP supported date and time format.
You can see the supported formats in [Supported Date and Time Formats](http://www.php.net/manual/en/datetime.formats.php)

#### Disabling until a date/time

``` php
<?php

namespace Foo\Bundle\FooBundle\Controller;

use Ferrandini\Bundle\DisableBundle\Annotations\Disable;

/**
 * @Disable(until="2013-11-11 11:11")
 */
class FooController {

}
```

#### Disabling after a date/time

``` php
<?php

namespace Foo\Bundle\FooBundle\Controller;

use Ferrandini\Bundle\DisableBundle\Annotations\Disable;

/**
 * @Disable(after="2013-11-11 11:11")
 */
class FooController {

}
```

#### Disabling by date/time range

``` php
<?php

namespace Foo\Bundle\FooBundle\Controller;

use Ferrandini\Bundle\DisableBundle\Annotations\Disable;

/**
 * @Disable(until="2013-06-11", after="2013-11-11")
 */
class FooController {

}
```

### Disabling and redirect to route

The route should be a defined route name in the routing configuration.

``` php
<?php

namespace Foo\Bundle\FooBundle\Controller;

use Ferrandini\Bundle\DisableBundle\Annotations\Disable;

/**
 * @Disable(redirect="_welcome")
 */
class FooController {

}
```

### Disabling with a custom response status code

``` php
<?php

namespace Foo\Bundle\FooBundle\Controller;

use Ferrandini\Bundle\DisableBundle\Annotations\Disable;

/**
 * @Disable(statusCode=404)
 */
class FooController {

}
```
