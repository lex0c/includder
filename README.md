Includder Autoloader
=========

[![Build Status](https://travis-ci.org/dwyl/esta.svg?branch=master)](https://twitter.com/leobcastro94)
[![codecov.io Code Coverage](https://img.shields.io/codecov/c/github/dwyl/hapi-auth-jwt2.svg?maxAge=2592000)](https://github.com/lleocastro/includder/tree/master/tests)
[![contributions welcome](https://img.shields.io/badge/contributions-welcome-brightgreen.svg?style=flat)](https://github.com/lleocastro/includder/issues)

> "Module developed for my framework "[Genniuz](https://github.com/lleocastro/genniuz-framework)", 
but works perfectly independent of the framework.

## Installation

> It's recommended that you download this package and import it for your project.

```bash
git clone https://github.com/lleocastro/includder.git
```
<hr>

## How Use

Create a json file at the root of the project to define configs and paths the autoload.

```json
{
  "autoload": {
    "configs": {
      "ext": "php"
    },
    "paths": {
      "Src\\": "src/",
      "Tests\\": "tests/"
    }
  }
}
```

## Definitions

This autoload makes the dynamic mapping independent of where this module is coupled, so some extra settings are required 
for it to work properly. In file 'autoload.php' on includder root.

```php
Autoloader::run([
    'dirlevel' => -1,
    'namespaces' => true,
    'filename' => 'autoload-paths-for-test',
    'projectname' => ''
]);
```

### Parameters for Initialization

- Dirlevel:
> ``` Set the subdirectories level ``` considering the directory where this module "includder" this root, for the autoload 
``` search the folders and subdirectories ``` the classes defined in the file at the root of the project.
 
- Namespaces:
> Activate support to namespaces. By ``` default the namespace support is 'true' ``` internally in the Autoloader, ``` to disable 
set to 'namespaces' define the key to 'false' ```.

- Filename: 
> By ``` default this autoloader looks for paths in an 'appdata.json' file ``` in the project root based on dirlevel. To change this, 
define your filename. Example ``` "['filename' => 'popcorn']" ```, so it will look for a ``` "popcorn.json" ``` 
file in the project root.

- Projectname: 
> Define the custom namespace for  your project. ``` namespace ProjectName\Package\Subpackage; ```

<hr>

##### After defining the parameters, include this file 'autoload.php' at the boot of your application!

<hr>

## Tests

For internal tests define the ``` dirlevel => -1 ```.

## To contributions 

Please, see [doc for contribute](https://github.com/lleocastro/includder/blob/master/CONTRIBUTE.md). Thanks!


## License

The Includder is licensed under the MIT license. See [License File](https://github.com/lleocastro/includder/blob/master/LICENSE) for more information.
