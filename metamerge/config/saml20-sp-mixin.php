<?php

$fieldsToStrip = array('entityDescriptor');

// NB: The examples assume that the authsource delivers attributes using friendly names
// and that the following global attribute filters are configured:
// 		50 => 'core:AttributeLimit', 
// 		90 => array( 'class' => 'consent:Consent', ...),
// 		95 => array('class' => 'core:AttributeMap', 'name2oid'),

$defaultTemplate = array (
  'AttributeNameFormat' => 'urn:oasis:names:tc:SAML:2.0:attrname-format:uri',
  'attributes' => array('none'),
  'authproc' => array(
	60 => array('class' => 'core:TargetedID'),
   ),
);

// Service Provider

$template['https://skriptenforum.net/shibboleth'] = array (
  'AttributeNameFormat' => 'urn:oasis:names:tc:SAML:2.0:attrname-format:uri',
  'attributes' => array('eduPersonScopedAffiliation'),
  'authproc' => array(
	60 => array('class' => 'core:TargetedID', 'nameId' => TRUE),
  ),
  'attributeencodings' => array('urn:oid:1.3.6.1.4.1.5923.1.1.1.10' => 'raw'),
);

// Service Categories

$template['http://refeds.org/category/research-and-scholarship'] = array (
  'AttributeNameFormat' => 'urn:oasis:names:tc:SAML:2.0:attrname-format:uri',
   // following attributes are default values
  'attributes' => array('mail', 'eduPersonPrincipalName', 'displayName'),
  'attributes.allowed' => array('mail', 'sn', 'givenName', 'eduPersonScopedAffiliation', 'eduPersonPrincipalName', 'displayName', 'eduPersonTargetedID'),
  'authproc' => array(
	15 => array('class' => 'core:AttributeMap', 'mail' => array('mail', 'eduPersonPrincipalName')),
	16 => array(
	  'class' => 'core:PHP',
	  'code' => '$attributes["displayName"] = array($attributes["givenName"][0] . " " . $attributes["sn"][0]);',
	),
	17 => array('class' => 'core:TargetedID'),
  ),
);

$template['http://www.geant.net/uri/dataprotection-code-of-conduct/v1'] = array (
  'AttributeNameFormat' => 'urn:oasis:names:tc:SAML:2.0:attrname-format:uri',
  'attributes' => array('none'),
  'attributes.allowed' => array('mail', 'sn', 'givenName', 'eduPersonScopedAffiliation', 'eduPersonPrincipalName', 'displayName', 'eduPersonTargetedID', 'schacHomeOrganization'),
  'authproc' => array(
	15 => array('class' => 'core:AttributeMap', 'mail' => array('mail', 'eduPersonPrincipalName')),
	16 => array(
	  'class' => 'core:PHP',
	  'code' => '$attributes["displayName"] = array($attributes["givenName"][0] . " " . $attributes["sn"][0]);',
	),
	17 => array('class' => 'core:TargetedID'),
	18 => array('class' => 'core:AttributeAdd','schacHomeOrganization' => 'tuwien.ac.at'),
  ),
);
