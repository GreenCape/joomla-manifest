# Joomla Manifest

![SensioLabsInsight](https://insight.sensiolabs.com/projects/073a6430-a4e1-4191-886e-78dfa07e8dd7/mini.png)
[![Code Climate](https://codeclimate.com/github/GreenCape/joomla-manifest/badges/gpa.svg)](https://codeclimate.com/github/GreenCape/joomla-manifest)
[![Test Coverage](https://codeclimate.com/github/GreenCape/joomla-manifest/badges/coverage.svg)](https://codeclimate.com/github/GreenCape/joomla-manifest/coverage)
[![Latest Stable Version](https://poser.pugx.org/greencape/joomla-manifest/v/stable.png)](https://packagist.org/packages/greencape/joomla-manifest)
[![Build Status](https://api.travis-ci.org/GreenCape/joomla-manifest.svg?branch=master)](https://travis-ci.org/greencape/joomla-manifest)

`joomla-manifest` is a class library for handling Joomla! extension manifest files.

## Requirements

PHP 5.4+

## Installation

### Composer

Simply add a dependency on `greencape/joomla-manifest` to your project's `composer.json` file if you use
[Composer](http://getcomposer.org/) to manage the dependencies of your project. Here is a minimal example of a
`composer.json` file that just defines a dependency on Joomla Manifest:

    {
        "require": {
            "greencape/joomla-manifest": "*@dev"
        }
    }

For a system-wide installation via Composer, you can run:

    composer global require 'greencape/joomla-manifest=*'

Make sure you have `~/.composer/vendor/bin/` in your path.

## Usage Examples

See the samples in the demo directory.

## Resources

### Documentation and Examples

  - [General description of **Manifest** files](http://docs.joomla.org/Manifest_files)
  - [**File** manifest example](http://docs.joomla.org/J2.5:Making_non-core_language_packs)
  - [**Package** manifest example](http://docs.joomla.org/Package)
