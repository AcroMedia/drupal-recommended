<?php
include 'utils.php';

// Check if it's a local run
$is_dev_mode = isLocalRun($argv);

if ($is_dev_mode) {
  echo "Running in local mode\n";
}

$base_path = getcwd();
$project_path = $is_dev_mode ? $base_path . '/test/project' : $base_path;

// Define the path to the auth.json file
$auth_file_path = $project_path . '/auth.json';

echo "Auth file path: " . $auth_file_path . "\n";
try {
  // Check if the auth.json file exists
  if (file_exists($auth_file_path)) {
    // Run the composer install command
    $command = 'composer install --ignore-platform-reqs 2>&1';
    $output = shell_exec($command);

    // Check if the command was successful
    if ($output === null) {
      throw new Exception('Command execution failed.');
    }

    echo $output;
  } else {
    // Print an error message
    echo "You need to create a auth.json file before running composer install, see README.md for more information.\n";
  }
} catch (Exception $e) {
  // Print the error message
  echo "Could not auto-install. Something went wrong during install command. Ensure an auth.json file is present. Consult readme.md for more information and try running composer install again.\n";
}
