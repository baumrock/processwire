<?php

namespace ProcessWire;

chdir(__DIR__);
chdir("../../");
$rootPath = getcwd(); // no trailing slash!
$public = "$rootPath/gitpod/public";

require_once "wire/core/ProcessWire.php";
$config = ProcessWire::buildConfig($rootPath);

$iterator = new \RecursiveIteratorIterator(
  new \RecursiveDirectoryIterator(
    $rootPath,
    \RecursiveDirectoryIterator::SKIP_DOTS
  ),
  \RecursiveIteratorIterator::SELF_FIRST
);
foreach ($iterator as $item) {
  if(strpos($item, "/var/www/html/.ddev") === 0) continue;
  if(strpos($item, "/var/www/html/.git") === 0) continue;
  if(strpos($item, "/var/www/html/gitpod") === 0) continue;
  if(strpos($item, "/var/www/html/wire") === 0) continue;
  if ($item->isDir()) {
    if(!is_dir($item)) mkdir("$public/" . $iterator->getSubPathname());
  } else copy($item, "$public/" . $iterator->getSubPathname());
  echo "copied $item\n";
}
echo "done!\n";

// create symlink
exec("ln -snf $rootPath/wire $public/wire");