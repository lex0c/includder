# How to Contribute
=====================

##Pull Requests

1. Make fork the repository
2. Create a new branch for each feature or improvement
3. Send a pull request from each feature branch to the "develop" branch

> It is very important to separate new features or improvements into separate feature branches, and to send a
pull request for each branch. This allows me to review and pull in new features or improvements individually.

##### Follow the default of git flow [develop, features, releases, hotfixes, bugfixes, support]

## Style Guide

> All pull requests must adhere to the [my style guide for PHP](https://github.com/lleocastro/styles-guide/blob/master/php/README.md).

###### Style guide preview:

```php
<?php namespace Package\Subpackage;

/*
 ===========================================================================
 = Preview About Class Content
 ===========================================================================
 =
 = Brief description about functionality of class...
 = 
 */

use \Package\Subpackage\Something;
use \Package\Subpackage\IOtherSomethting;
use \RuntimeException;

/**
 * PHPDoc
 * ...
 */
 
 //Names in UpperCamelCase
class ClassName extends Something implements IOtherSomethting
{
    //Names in LowerCamelCase and initialize hever all variables
    //and for variables in same group define the equals aligned
    protected $variable  = '';
    private $arrExample  = [];
    public $status       = false;
    
    /**
     * ...
     * @var int
     */
    protected static $some = 1;
    
    //Functions in LowerCamelCase
    public function thing()
    {
        echo 'Hello!';
    }
    
    public function otherThing(array $arr)
    {
        for($i = 0; $i >= $this->some; $i++):
        endfor;
        
        if(!empty($this->arrExample)):
        endif;
        
        etc...
    }
    
}

```

## Unit Testing

> All pull requests must be accompanied by passing unit tests and complete code coverage. uses phpunit for testing.

[Learn about PHPUnit](https://github.com/sebastianbergmann/phpunit/)


## Security

> If you discover security related issues, please email leonardo_carvalho@outlook.com instead of using the issue tracker.
