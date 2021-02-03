# Package Wizard

`Package Wizard` is a composer plugin for creating a new packages using CLI tool.

[![Stable Version][badge_stable]][link_packagist]
[![Unstable Version][badge_unstable]][link_packagist]
[![Total Downloads][badge_downloads]][link_packagist]
[![License][badge_license]][link_license]

## Table of contents

* [Installation](#installation)
* [Using](#using)

## Installation

To get the latest version of `Package Wizard`, simply require the project using [Composer](https://getcomposer.org):

```bash
$ composer global require andrey-helldar/package-wizard
```

## Using

Once in the folder, call the `composer package:init` command and follow the prompts.

The wizard will ask some questions and generate initial files for your project.

The following files and folders will be created:

* src/
* tests/
* .codecov.yml
* .editorconfig
* .gitattributes
* .gitignore
* .styleci.yml
* phpunit.xml
* README.md

[badge_downloads]:      https://img.shields.io/packagist/dt/andrey-helldar/package-wizard.svg?style=flat-square

[badge_license]:        https://img.shields.io/packagist/l/andrey-helldar/package-wizard.svg?style=flat-square

[badge_stable]:         https://img.shields.io/github/v/release/andrey-helldar/package-wizard?label=stable&style=flat-square

[badge_unstable]:       https://img.shields.io/badge/unstable-dev--main-orange?style=flat-square

[link_license]:         LICENSE

[link_packagist]:       https://packagist.org/packages/andrey-helldar/package-wizard
