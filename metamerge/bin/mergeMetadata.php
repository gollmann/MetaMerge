#!/usr/bin/env php
<?php

define('SIMPLESAMLPATH', dirname(dirname(dirname(dirname(__FILE__)))));
define('MODULEPATH', (dirname(dirname(__FILE__))));
ini_set('error_level', E_ALL & E_STRICT);
ini_set('display_errors', true);

/*
 Merge metadata-generated/saml20-sp-remote.php with ../config/saml20-sp-mixin.php
 and drop superfluous fields.
 Templates may be specified by entityID or by category (http://macedir.org/entity-category).
 If specified by entity-category fields in the metadata dominate otherwise the template
 fields overwrite fields in the imported metadata.
 Merged metadata are written to metadata-generated/saml20-sp-merged.php.
 */

require_once(SIMPLESAMLPATH.'/lib/_autoload.php');
require(SIMPLESAMLPATH.'/metadata-generated/saml20-sp-remote.php');
require(MODULEPATH.'/config/saml20-sp-mixin.php');

foreach($template as $url => $mdata) {
	if(!isset($metadata[$url])) {
		passthru("logger -t IDP-METADATA-DIFF 'INFO: template url not found in metadata array: $url'");
	}
}

if(!($fh = @fopen(SIMPLESAMLPATH.'/metadata-generated/saml20-sp-merged.php', 'w'))) {
	echo("cannot open/create output file\n");
	exit(1);
}
@fwrite($fh, "<?php\n");

foreach($metadata as $url => $mdata) {
	foreach($fieldsToStrip as $field) unset($mdata[$field]);

// 	map "attributes" and "attributes.required" from OIDs to friendly names
 	$mapper = new sspmod_core_Auth_Process_AttributeMap(array('oid2name'), NULL);
 	foreach(['attributes', 'attributes.required'] as $field) {
 		if(isset($mdata[$field])) {
 			$tmp = array('Attributes' => array_fill_keys($mdata[$field], 0));
 			$mapper->process($tmp);
 			$mdata[$field] = array_keys($tmp['Attributes']);
 		}
 	}

	$templateWins = true;
	if(isset($template[$url])) $currentTemplate = $template[$url];
	else $currentTemplate = NULL;

	if($currentTemplate == NULL && isset($mdata['EntityAttributes']['http://macedir.org/entity-category'])) {
		foreach($mdata['EntityAttributes']['http://macedir.org/entity-category'] as $category) {
			if(isset($template[$category])) {
				$currentTemplate = $template[$category];
				$templateWins = false;
			}
		}
	}
	if($currentTemplate == NULL) $currentTemplate = $defaultTemplate;

	foreach($currentTemplate as $field => $value) {
		if($templateWins || !isset($mdata[$field])) $mdata[$field] = $value;
	}

	if(isset($mdata['attributes.allowed'])) {
		$mdata['attributes'] = array_intersect($mdata['attributes'], $mdata['attributes.allowed']);
		unset($mdata['attributes.allowed']);
	}
	if(isset($mdata['attributes.required']) && isset($mdata['attributes.allowed.ifRequired'])) {
		$allowed = array_intersect($mdata['attributes.required'], $mdata['attributes.allowed.ifRequired']);
		$mdata['attributes'] = $mdata['attributes'] + $allowed;
	}
	unset($mdata['attributes.allowed.ifRequired']);
	unset($mdata['attributes.required']);

	// output
	if(!@fwrite($fh, "\n\$metadata['$url'] = ".var_export($mdata, true).";\n")) {
		echo("cannot write to output file\n");
		exit(1);
	}
}

@fwrite($fh, "\n?>");
if(!@fclose($fh)) {
	echo("cannot close output file\n");
	exit(1);
}

exit(0);

?>
