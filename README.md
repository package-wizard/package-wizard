# Package Wizard

<p align="center">
    <img src="/.github/images/logotype.png?raw=true" alt="Package Wizard Logotype"/>
</p>

[![Stable Version][badge_stable]][link_packagist]
[![Unstable Version][badge_unstable]][link_packagist]
[![Total Downloads][badge_downloads]][link_packagist]
[![License][badge_license]][link_license]


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

## License

This package is licensed under the [MIT License](LICENSE).


## For Enterprise

Available as part of the Tidelift Subscription.

The maintainers of `andrey-helldar/package-wizard` and thousands of other packages are working with Tidelift to deliver commercial support and maintenance for the open source packages you use to build your applications. Save time, reduce risk, and improve code health, while paying the maintainers of the exact packages you use. [Learn more](https://tidelift.com/subscription/pkg/packagist-andrey-helldar-package-wizard?utm_source=packagist-andrey-helldar-package-wizard&utm_medium=referral&utm_campaign=enterprise&utm_term=repo).


[badge_downloads]:      https://img.shields.io/packagist/dt/andrey-helldar/package-wizard.svg?style=flat-square

[badge_license]:        https://img.shields.io/packagist/l/andrey-helldar/package-wizard.svg?style=flat-square

[badge_stable]:         https://img.shields.io/github/v/release/andrey-helldar/package-wizard?label=stable&style=flat-square

[badge_unstable]:       https://img.shields.io/badge/unstable-dev--main-orange?style=flat-square

[link_license]:         LICENSE

[link_packagist]:       https://packagist.org/packages/andrey-helldar/package-wizard
