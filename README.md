# Package Wizard

<img src="https://preview.dragon-code.pro/the%20dragon%20code/package%20wizard.svg?brand=composer&mode=auto" alt="Package Wizard"/>

[![Stable Version][badge_stable]][link_packagist]
[![Total Downloads][badge_downloads]][link_packagist]
[![License][badge_license]][link_license]


## Installation

To get the latest version of `Package Wizard`, simply require the project using [Composer](https://getcomposer.org):

```bash
composer global require package-wizard/installer:*
```

## Update

You can update global dependencies by running the command:

```bash
composer global update
```

## Using

<p align="center">
    <img src="/.github/images/preview.gif?raw=true" alt="Preview"/>
</p>

Once in the folder, call the `composer package` command and follow the prompts.

The wizard will ask some questions and generate initial files for your project.

## Troubleshooting

For detailed information while the application is running, run it with the `-vvv` parameter:

```bash
composer package -vvv
```

## License

This package is licensed under the [MIT License](LICENSE).


[badge_downloads]:      https://img.shields.io/packagist/dt/package-wizard/installer.svg?style=flat-square

[badge_license]:        https://img.shields.io/packagist/l/package-wizard/installer.svg?style=flat-square

[badge_stable]:         https://img.shields.io/github/v/release/package-wizard/installer?label=stable&style=flat-square

[link_license]:         LICENSE

[link_packagist]:       https://packagist.org/packages/package-wizard/installer
