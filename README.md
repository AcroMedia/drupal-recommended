# Acro Media Gesso Drupal project skeleton

Composer create template for starting new decoupled drupal builds.

## How to use

`composer create-project acromedia/drupal-recommended /SOME_PATH/YOUR_PROJECT_NAME`

### Env vars
- `TOKEN`: used to pass gitlab token programatically.
  - ie: `TOKEN=myToken composer create-project acromedia/drupal-recommended my-project`

## Development
### Run the script on local
- `php dev-create`: Creates a local project under `test/project`, will handle project deletion prior to running.



# Package Deployment

Package is deployed to [packagist](https://packagist.org/packages/acromedia/drupal-recommended) via github link with packagist.
This project's source code is stored in gitlab, and is mirrored to github.

### How to deploy a new version
- create a tag with the version number in the format `v0.0.0` in gitlab.


## Security

Update `drush/Commands/PolicyCommands.php` to protect your production lagoon environment's DB from accidentally being overwritten by `drush sql-sync`
