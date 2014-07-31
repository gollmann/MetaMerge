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
   // default attributes: mail, eduPersonPrincipalName, displayName
  'attributes' => array('urn:oid:0.9.2342.19200300.100.1.3', 'urn:oid:1.3.6.1.4.1.5923.1.1.1.6', 'urn:oid:2.16.840.1.113730.3.1.241'),
  'authproc' => array(
	10 => array('class' => 'core:AttributeLimit', 'mail', 'sn', 'givenName', 'eduPersonScopedAffiliation'),
	15 => array('class' => 'core:AttributeMap', 'mail' => array('mail', 'eduPersonPrincipalName')),
	16 => array(
	  'class' => 'core:PHP',
	  'code' => '$attributes["displayName"] = array($attributes["givenName"][0] . " " . $attributes["sn"][0]);',
	),
	17 => array('class' => 'core:TargetedID'),
	20 => array('class' => 'core:AttributeMap', 'name2oid'), // map for attribute filter
	85 => array('class' => 'core:AttributeMap', 'oid2name'), // map for consent display
  ),
);

$template['http://www.geant.net/uri/dataprotection-code-of-conduct/v1'] = array (
  'AttributeNameFormat' => 'urn:oasis:names:tc:SAML:2.0:attrname-format:uri',
  'attributes' => array('none'),
  'authproc' => array(
	10 => array('class' => 'core:AttributeLimit', 'mail', 'sn', 'givenName', 'eduPersonScopedAffiliation'),
	15 => array('class' => 'core:AttributeMap', 'mail' => array('mail', 'eduPersonPrincipalName')),
	16 => array(
	  'class' => 'core:PHP',
	  'code' => '$attributes["displayName"] = array($attributes["givenName"][0] . " " . $attributes["sn"][0]);',
	),
	17 => array('class' => 'core:TargetedID'),
	18 => array('class' => 'core:AttributeAdd','schacHomeOrganization' => 'tuwien.ac.at'),
	20 => array('class' => 'core:AttributeMap', 'name2oid'), // map for attribute filter
	85 => array('class' => 'core:AttributeMap', 'oid2name'), // map for consent display
  ),
);

?>
