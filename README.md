# [Base](https://packagist.org/packages/symbiote/silverstripe-base)

> The recommended foundation for a base project.

## Requirement

* SilverStripe 3.0 → **4.0**
* Composer
* [Phing](https://www.phing.info/)

## Getting Started

* `composer create-project symbiote/silverstripe-base . 4.0.3` for SS3
* `composer create-project symbiote/silverstripe-base . dev-master` for SS4
* `phing`

## Upgrading an existing 4.x site to use the Public directory structure

* Add to /.gitignore
  * /public/assets
  * /public/resources
  * /public/.htaccess
* Copy /public and contents to project root
* Align silverstripe-build module version in composer.json
* remove /.htaccess
* remove /resources
* `composer update symbiote/silverstripe-build`
* `phing`
* `composer vendor-expose`
