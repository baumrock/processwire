<?php

namespace ProcessWire;

chdir(__DIR__);
chdir("../../");
$rootPath = getcwd(); // no trailing slash!
$public = "$rootPath/gitpod/public";

require_once "wire/core/ProcessWire.php";
$config = ProcessWire::buildConfig($rootPath);

if(!is_dir($public)) {
  mkdir($public);
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
}

// create symlink
chdir($public);
exec("ln -snf $rootPath/wire wire");

// clone RockShell
if(!is_dir("RockShell")) exec("git clone https://github.com/baumrock/RockShell.git");

echo "\n\n";
echo "#########################################\n";
echo "#### ProcessWire is ready to install ####\n";
echo "#### proudly powered by baumrock.com ####\n";
echo "#########################################\n";
echo "now install PW using RockShell and set the hostname to the url of the preview window (without http):\n";
echo "cd $public/RockShell && php rockshell pw-install\n";