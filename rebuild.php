<?php

function out($msg, $lnNum=1) { fwrite(STDOUT, $msg.str_repeat("\n",$lnNum)); }
function ln($n=1) { out('', $n); }
function h1($msg) { ln(2); out("-------");  out($msg); }

$configFile = dirname(__FILE__).'/config.php';
if (!file_exists($configFile)) { out('Cannot find config.php file!'); die(); }
require_once $configFile;


h1("Update sources from git...");
chdir($dir);
system("git pull");
system("git submodule update --init --recursive");


h1("Rebuild Propel ORM classes...");
chdir($dir."/core/orm");
system("../../lib/propel/generator/bin/propel-gen");


h1("Set write permissions for folders...");
$writeDirs = array(
	$dir.'/lib/tcpdf/cache',
	$dir.'/lib/tcpdf/fonts',
	$dir.'/lib/tcpdf/images',
	$dir.'/docs/invoices',
	$dir.'/docs/reports'
);
foreach($writeDirs as $d)
{
	out('  * '.$d);
	chmod($d, 0777);
}


ln(); out("Done!", 2);
