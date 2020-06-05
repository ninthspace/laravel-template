#!/bin/bash

#
# Before installing
#
# - check content of https://github.com/laravel/laravel/blob/master/composer.json
#   and bring composer.json up to date

#
# Begin
#
composer install

###########################################################
#
# Development packages
#
###########################################################

# Blueprint
composer require --dev laravel-shift/blueprint

# Query Monitor
composer require --dev supliu/laravel-query-monitor
php artisan vendor:publish --provider="Supliu\LaravelQueryMonitor\ServiceProvider"

# Laravel Code Style
composer require matt-allan/laravel-code-style --dev
php artisan vendor:publish --provider="MattAllan\LaravelCodeStyle\ServiceProvider"
echo '.php_cs.cache' >> .gitignore

# Mirabile
composer require ninthspace/mirabile --dev
vendor/bin/mirabile-install

# PHP Insights
composer require nunomaduro/phpinsights --dev
php artisan vendor:publish --provider="NunoMaduro\PhpInsights\Application\Adapters\Laravel\InsightsServiceProvider"

# PHP Pest
composer require phpunit/phpunit:"^9.0" --dev --update-with-dependencies
composer require nunomaduro/collision:"^5.0" --dev --update-with-dependencies
composer require pestphp/pest --dev
php artisan pest:install

# PHP Pest Faker
composer require pestphp/pest-plugin-faker --dev

# PHP Pest Livewire
composer require pestphp/pest-plugin-livewire --dev

# PHPUnit Watcher
composer require spatie/phpunit-watcher --dev

# Laravel Dusk
#
# @todo change DuskTestCase::driver to point to http://selenium-hub:4444/wd/hub
# @todo create an .env.dusk.local which has APP_URL=http://CONTAINER_NAME-nginx:80/ so it
#  points to correct container
#
composer require --dev laravel/dusk
php artisan dusk:install

# Roave Security Advisories
composer require roave/security-advisories:dev-master --dev

###########################################################
#
# Application packages
#
###########################################################

#
# Doctrine/Dbal
composer require doctrine/dbal

# Livewire
#
# @todo check @livewireStyles and @livewireScripts are in view template(s)
#
composer require livewire/livewire

# Predis
#
# @todo consider switching to https://github.com/phpredis/phpredis
#
composer require predis/predis

# Laravel Actions
composer require lorisleiva/laravel-actions

# Only if multi-tenant
# Dweller UI and Dweller
#
# @todo update middlewareGroups as per documentation
# @todo add traits as per documentation
# @todo remove or comment out Illuminate\Auth\Passwords\PasswordResetServiceProvider::class
#
composer require ninthspace/dweller-ui
php artisan dweller:install
php artisan migrate
php artisan ui dwellerui --auth
npm install && npm run dev
php artisan migrate

# Bouncer
#
# @todo add HasRolesAndAbilities Trait to user model
#
composer require silber/bouncer v1.0.0-rc.8
php artisan vendor:publish --tag="bouncer.migrations"
php artisan migrate

# Laravel Flash
composer require spatie/laravel-flash

# Laravel Media Library
#
# @todo add migrate down method
#
composer require "spatie/laravel-medialibrary:^8.0.0"
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="migrations"
php artisan migrate

# Active for Laravel
composer require watson/active

# Floorshow
composer require ninthspace/floorshow
