<?php
$project_path = getcwd();
$templates_path = $project_path . '/templates/';
$project_path_trimmed = explode('/', $project_path);
$default_project_name = $project_path_trimmed[count($project_path_trimmed) - 1];
$default_webroot = 'web';

echo "Setting up your drupal project!\n";

echo "What is the name of your project [$default_project_name] :";
$default_project_name = preg_replace("/\s+|_+/", '-', rtrim(fgets(STDIN))) ?: $default_project_name;
echo "project name: " . $default_project_name . "\n";

echo "What is webroot of your project [$default_webroot] :";
$default_webroot = preg_replace("/\s+|_+/", '-', rtrim(fgets(STDIN))) ?: $default_webroot;
echo "project webroot: " . $default_webroot . "\n";

echo "Copying template files...\n";
copy($templates_path . 'template.README.md', $project_path . '/README.md');
copy($templates_path . 'template.gitlab-ci.yml', $project_path . '/.gitlab-ci.yml');
copy($templates_path . 'template.gitignore', $project_path . '/.gitignore');
copy($templates_path . 'template.composer.json', $project_path . '/composer.json');
copy($templates_path . 'template.composer.lock', $project_path . '/composer.lock');
copy($templates_path . 'template.grumphp.yml.dist', $project_path . '/grumphp.yml.dist');
copy($templates_path . 'template.lando.yml', $project_path . '/.lando.yml');
copy($templates_path . 'template.docker-compose.yml', $project_path . '/docker-compose.yml');
copy($templates_path . 'lagoonize/template.env', $project_path . '/.env');
copy($templates_path . 'lagoonize/template.lagoon.yml', $project_path . '/.lagoon.yml');
copy($templates_path . 'template.phpunit.xml.dist', $project_path . '/phpunit.xml.dist');
copy($templates_path . 'template.phpcs.xml.dist', $project_path . '/phpcs.xml.dist');

// Copy drupal configurations
// create webroot and sites/default directories.
mkdir($project_path . '/' . $default_webroot . '/sites/default', 0755, true); 
copy($templates_path . 'template.services.yml', $project_path . '/' . $default_webroot . '/sites/default/services.yml');

$token_replacments = [
    '[PROJECTNAME]' => strtolower($default_project_name),
    '[WEBROOT]'     => $default_webroot
];

replace_file_token($project_path . '/.lando.yml', $token_replacments);
replace_file_token($project_path . '/.lagoon.yml', $token_replacments);
replace_file_token($project_path . '/.lagoon/cli.dockerfile', $token_replacments);
replace_file_token($project_path . '/.lagoon/nginx.dockerfile', $token_replacments);
replace_file_token($project_path . '/.lagoon/php.dockerfile', $token_replacments);
replace_file_token($project_path . '/.env', $token_replacments);
replace_file_token($project_path . '/composer.json', $token_replacments);
replace_file_token($project_path . '/phpunit.xml.dist', $token_replacments);

delete_files('composer.lock');
delete_files($templates_path);

echo "Finishing the project setup!\n";

/**
 * Replace file tokens with value.
 *
 * @param $filename
 * @param $replacement
 *   ['TOKEN' => 'Value']
 */
function replace_file_token($filename, $replacement) {
    $content = file_get_contents($filename);
    foreach($replacement as $token => $value) {
        $content_chunks = explode($token, $content);
        $content = implode($value, $content_chunks);
    }
    file_put_contents($filename, $content);
}

/**
 * PHP delete function that deals with directories recursively
 *
 * @param $target
 */
function delete_files($target) {
    if(is_dir($target)){
        $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned
        foreach( $files as $file ){
            delete_files( $file );
        }
        rmdir( $target );
    } elseif(is_file($target)) {
        unlink( $target );
    }
}
