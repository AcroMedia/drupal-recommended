<?php

// Function to check if --local argument is present
function isLocalRun($argv)
{
  foreach ($argv as $arg) {
    if ($arg === '--local') {
      return true;
    }
  }
  return false;
}

/**
 * Replace file tokens with value.
 *
 * @param $filename
 * @param $replacement
 *   ['TOKEN' => 'Value']
 */
function replace_file_token($filename, $replacement)
{
  $content = file_get_contents($filename);
  foreach ($replacement as $token => $value) {
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
function delete_files($target)
{
  if (is_dir($target)) {
    $files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned
    foreach ($files as $file) {
      delete_files($file);
    }
    rmdir($target);
  } elseif (is_file($target)) {
    unlink($target);
  }
}

/**
 * Copy a directory and all its contents to a new location
 *
 * @param $src
 * @param $dst
 */
function copyDir($src, $dst)
{
  // Open the source directory
  $dir = opendir($src);

  // Create the destination directory if it doesn't exist
  if (!is_dir($dst)) {
    mkdir($dst);
  }

  // Iterate through the items in the source directory
  while (false !== ($file = readdir($dir))) {
    if ($file != '.' && $file != '..') {
      // If it's a directory, call copyFolder() recursively
      if (is_dir($src . '/' . $file)) {
        copyDir($src . '/' . $file, $dst . '/' . $file);
      } else {
        // Otherwise, copy the file
        copy($src . '/' . $file, $dst . '/' . $file);
      }
    }
  }

  // Close the directory
  closedir($dir);
}
