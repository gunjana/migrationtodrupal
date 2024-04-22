# Migration To Drupal module

Introduction:
  • This Module will take the JSON file provided in the module.
  • Creates a new custom entity for city data (cities_data).
  • Provides amapping form to map JSON data attributes to custom entity attributes.
  • Migrates data provided in the JSON to the drupal entity.

Installation and Setup of module:
  • Install module from Extend menu by searching Migration To Drupal module
  • Configure the mapping settings from /admin/config/mapping_form.
  • Go to /admin/content/cities-data To view imported Data.

After installation:
Migration Through Drush:
  • Run command -drush migrate:import --group migrate_to_drupal
