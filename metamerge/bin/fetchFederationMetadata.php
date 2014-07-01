#!/usr/bin/env php
<?php

define('SCRIPTPATH', (dirname(__FILE__)));
define('BASEPATH', dirname(dirname(dirname(dirname(__FILE__)))));

require(SCRIPTPATH.'/../config/config.php');

$command = BASEPATH.'/modules/metarefresh/bin/metarefresh.php --validate-fingerprint=' . $fingerprint . ' ' . $metadatasource . ' >'.BASEPATH.'/log/metadataimport.log';
exec($command, $output, $exitCode);
if ($exitCode != 0) {
	echo($output[0]);
	exit($exitCode);
}

exec(SCRIPTPATH.'/mergeMetadata.php', $output, $exitCode);
if ($exitCode != 0) {
	echo($output[0]);
	exit($exitCode);
}

exec(SCRIPTPATH.'/checkMergedMetadata.php', $output, $exitCode);
if ($exitCode != 0) {
	echo($output[0]);
	exit($exitCode);
}

rename(BASEPATH.'/metadata-generated/saml20-sp-merged.php', BASEPATH.'/metadata/'.$destination);
exit(0);

?>
