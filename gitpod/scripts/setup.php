<?php

namespace ProcessWire;

chdir(__DIR__);
chdir("../../");
$rootPath = getcwd(); // no trailing slash!
$public = "$rootPath/gitpod/public";
if(!is_dir($public)) mkdir($public);

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

  $dir = "$public/" . $iterator->getSubPathname();
  echo str_pad($item, 60, " ", STR_PAD_RIGHT)." --> $dir \n";
  if($item->isDir() and !is_dir($dir)) mkdir($dir);
  if($item->isFile()) copy($item, $dir);
}
echo " -- done! --\n";

// create symlink
exec("ln -snf $rootPath/wire $public/wire");