# Acro Media Gesso Drupal project skeleton

Composer create template for starting new decoupled drupal builds.

## How to use

`composer create-project acromedia/drupal-recommended /SOME_PATH/YOUR_PROJECT_NAME`

## Security

Update `drush/Commands/PolicyCommands.php` to protect your production lagoon environment's DB from accidentally being overwritten by `drush sql-sync`