# Migration To Drupal module

## Introduction

- This Module defines 2 migration methods i.e. (mongodb, json)
- The Json required for migration is provided inside the module itself under json_file folder
- This module defines a new migration group i.e. migrate_to_drupal
- Creates a new custom entity for city data (cities_data)
- Migrates data provided in the JSON to the drupal entity
- Migrates data from the mongodb database to the drupal entity
- Provides a mapping form to map JSON data attributes to custom entity
attributes
- For Migration from the mongodb please make sure you have added source database in your settings.php

## Installation and Setup of module

- Install module from Extend menu by
searching Migration To Drupal module
- Configure the mapping settings
from /admin/config/mapping_form
- Go to /admin/content/cities-data To
view imported Data

## Migration Through Drush

Run the below commands in your terminal for migration using Json file.

```sh
drush migrate:import --group migrate_to_drupal migrate_json
```

Run the below commands in your terminal for migration from mongodb.

```sh
drush migrate:import --group migrate_to_drupal migrate_mongo
```
