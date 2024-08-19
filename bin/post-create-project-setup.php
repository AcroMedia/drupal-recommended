<?php
include 'utils.php';

// Check if it's a local run
$is_dev_mode = isLocalRun($argv);

if ($is_dev_mode) {
  echo "Running in local mode\n";
}

$base_path = getcwd();
$project_path = $is_dev_mode ? $base_path . '/test/project' : $base_path;
$templates_path = $base_path . '/templates/';
$project_path_trimmed = explode('/', $project_path);

// Set default values
$default_project_name = $project_path_trimmed[count($project_path_trimmed) - 1];
$default_webroot = 'web';

echo "Setting up your drupal project!\n";

echo "What is the name of your project [$default_project_name] :";
$default_project_name = preg_replace("/\s+|_+/", '-', rtrim(fgets(STDIN))) ?: $default_project_name;
echo "project name: " . $default_project_name . "\n";

echo "What is webroot of your project [$default_webroot] :";
$default_webroot = preg_replace("/\s+|_+/", '-', rtrim(fgets(STDIN))) ?: $default_webroot;
echo "project webroot: " . $default_webroot . "\n";

echo "Enter your Gitlab personal access token:";
$personal_access_token = preg_replace("/\s+|_+/", '-', rtrim(fgets(STDIN))) ?: "";

if (!$personal_access_token) {
  echo "\033[1;33m WARNING! You haven't specified a personal_access_token \n Installation will fail until you update 
  your auth.json file. \033[0m \n";
}

echo "Copying template files...\n";
if ($is_dev_mode) {
  // Local mode, create a test project directory
  // under test directory to ensure we don't overwrite the current directory
  mkdir($project_path, 0777, true);
  copyDir($base_path . '/.lagoon', $project_path . '/.lagoon');
  copyDir($base_path . '/assets', $project_path . '/assets');
}
copy($templates_path . 'template.README.md', $project_path . '/README.md');
copy($templates_path . 'template.gitlab-ci.yml', $project_path . '/.gitlab-ci.yml');
copy($templates_path . 'template.gitignore', $project_path . '/.gitignore');
copy($templates_path . 'template.composer.json', $project_path . '/composer.json');
copy($templates_path . 'template.auth.json', $project_path . '/auth.json');
copy($templates_path . 'template.grumphp.yml.dist', $project_path . '/grumphp.yml.dist');
copy($templates_path . 'template.lando.yml', $project_path . '/.lando.yml');
copy($templates_path . 'lagoonize/template.env', $project_path . '/.env');
copy($templates_path . 'lagoonize/template.lagoon.yml', $project_path . '/.lagoon.yml');
copy($templates_path . 'template.phpunit.xml.dist', $project_path . '/phpunit.xml.dist');
copy($templates_path . 'template.phpcs.xml.dist', $project_path . '/phpcs.xml.dist');


// Copy drupal configurations
// create webroot and sites/default directories.
echo "Copying default Drupal configuration files...\n";
mkdir($project_path . '/' . $default_webroot . '/sites/default', 0755, true);
copy($templates_path . 'template.services.yml', $project_path . '/' . $default_webroot . '/sites/default/services.yml');

// Replace tokens with updated values
$token_replacments = [
  '[PROJECTNAME]'  => strtolower($default_project_name),
  '[WEBROOT]'      => $default_webroot,
  '[GITLAB_TOKEN]' => $personal_access_token
];

echo "Replacing tokens in files...\n";
replace_file_token($project_path . '/.lando.yml', $token_replacments);
replace_file_token($project_path . '/.lagoon.yml', $token_replacments);
replace_file_token($project_path . '/.lagoon/cli.dockerfile', $token_replacments);
replace_file_token($project_path . '/.lagoon/nginx.dockerfile', $token_replacments);
replace_file_token($project_path . '/.lagoon/php.dockerfile', $token_replacments);
replace_file_token($project_path . '/.env', $token_replacments);
replace_file_token($project_path . '/composer.json', $token_replacments);
replace_file_token($project_path . '/phpunit.xml.dist', $token_replacments);
replace_file_token($project_path . '/auth.json', $token_replacments);

echo "Finishing the project setup!\n";

if ($is_dev_mode) {
  // Local mode, exit after copying files
  // to ensure we don't run cleanup
  echo "Running in dev mode, skipping file cleanup.\n";
  exit;
}

delete_files('composer.lock');
delete_files($templates_path);
