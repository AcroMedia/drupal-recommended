# Acro Media Gesso Drupal project skeleton

Composer create template for starting new decoupled drupal builds.

## How to use

`composer create-project acromedia/drupal-recommended /SOME_PATH/YOUR_PROJECT_NAME`

## Development
### Run the script on local
- clone the repo, make alterations as needed
- copy the dir into a new location. `cp -r gesso-drupal testing-gesso-drupal`
- enter to new project root `cd testing-gesso-drupal`
- Run the setup script `composer run-script post-create-project-cmd`
  - This will create the project within testing-gesso-drupal hence why we create a copy
    to not mess up the repo code.

## Security

Update `drush/Commands/PolicyCommands.php` to protect your production lagoon environment's DB from accidentally being overwritten by `drush sql-sync`
