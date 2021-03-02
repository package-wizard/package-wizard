# Package Wizard

<p align="center">
    <img src="/.github/images/logotype.png?raw=true" alt="Package Wizard Logotype"/>
</p>

[![Stable Version][badge_stable]][link_packagist]
[![Unstable Version][badge_unstable]][link_packagist]
[![Total Downloads][badge_downloads]][link_packagist]
[![License][badge_license]][link_license]

## Table of contents

* [Requirements](#requirements)
* [Installation](#installation)
* [Update](#update)
* [Using](#using)
* [Troubleshooting](#troubleshooting)

## Requirements

| Service | Versions |
|:---|:---|
| Composer | ^2.0 |
| PHP | ^7.2.5, ^8.0 |
| php_ext-json | any |

## Installation

To get the latest version of `Package Wizard`, simply require the project using [Composer](https://getcomposer.org):

```bash
$ composer global require andrey-helldar/package-wizard
```

## Update

You can update global dependencies by running the command:

```bash
$ composer global update
```

## Using

<p align="center">
    <img src="/.github/images/preview.gif?raw=true" alt="Preview"/>
</p>

Once in the folder, call the `composer package:init` command and follow the prompts.

The wizard will ask some questions and generate initial files for your project.

The following files and folders will be created:

```
.github/workflows
src/
tests/

.codecov.yml
.editorconfig
.gitattributes
.gitignore
.styleci.yml

composer.json

phpunit.xml
README.md
```

## Troubleshooting

For detailed information while the application is running, run it with the `-vvv` parameter:

```bash
$ composer package:init -vvv
```

[badge_downloads]:      https://img.shields.io/packagist/dt/andrey-helldar/package-wizard.svg?style=flat-square

[badge_license]:        https://img.shields.io/packagist/l/andrey-helldar/package-wizard.svg?style=flat-square

[badge_stable]:         https://img.shields.io/github/v/release/andrey-helldar/package-wizard?label=stable&style=flat-square

[badge_unstable]:       https://img.shields.io/badge/unstable-dev--main-orange?style=flat-square

[link_license]:         LICENSE

[link_packagist]:       https://packagist.org/packages/andrey-helldar/package-wizard
