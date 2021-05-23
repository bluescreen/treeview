# Kendo treeview
Elimination Tree generator for Kendo Tournament Trees

Can generate and simulate tree based match hierarchies.

## Install

Clone repository and install dependencies
```
composer install
```
Migrate database
```
touch database.sqlite && sqlite3 database.sqlite < data.sql
```

Run unit tests
```
vendor/bin/phpunit tests
```

## Usage

To generate and simulate a elimination round 
```
Competitor::participateAll();
$seed = new IndividualSeed();
$seed->setup('elimination');
IndividualMatch::simulateAll($seed->getDepth());
```
