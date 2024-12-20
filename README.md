# BrCommons Validators
[![Total Downloads](http://poser.pugx.org/fernandorech/br-commons/downloads)](https://packagist.org/packages/fernandorech/br-commons) [![License](http://poser.pugx.org/fernandorech/br-commons/license)](https://packagist.org/packages/fernandorech/br-commons)


BrCommons validator is a set of useful validators that helps you develop fast and reliable code.

## Installation by composer
Run `composer require fernandorech/br-commons`.


## BrCommons
It's a class that helps you to validate and format CPF and CNPJ by given a string.

## Examples 1: Simple document format
    use BrCommons\BrCommons;
        $document = BrCommons::from('44975583046');
        echo $document->toString(); // will print '449.755.830-46'

 ## Example 2: validation of a cpf
        if(BrCommons::isValid('44975583046') {
            // do some code
        } else {
            // other stuff
        }
## Example 3: throw an exception if a document is not valid
    try {
        $document = BrCommons::from('4497558', true); //it will thrown an exception
    } catch (DocumentException $e) {
        //do code
    }

## Example 4: Return formatted value without create an object
    echo BrCommons::format('44975583046');

## Example 5: Use CPF or CNPJ directly
    $cpf = CPF::from('44975583046');
    $cnpj  CNPJ::from('14328920000148');
