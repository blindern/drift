<?php

// See https://github.com/simplesamlphp/simplesamlphp/blob/master/metadata-templates/saml20-sp-remote.php
// See https://simplesamlphp.org/docs/stable/simplesamlphp-reference-sp-remote

require "/var/simplesamlphp/config/secrets.php";

// Pålogging via beskyttede sider under Apache webserver.
$metadata["https://foreningenbs.no/mellon"] = [
  "SingleLogoutService" => "https://foreningenbs.no/mellon/logout",
  "AssertionConsumerService" => "https://foreningenbs.no/mellon/postResponse",
];

// Pålogging på gamle wikien (wiki-2015)
$metadata["https://foreningenbs.no/w/saml/module.php/saml/sp/metadata.php/default-sp"] = [
  "SingleLogoutService" => "https://foreningenbs.no/w/saml/module.php/saml/sp/saml2-logout.php/default-sp",
  "AssertionConsumerService" => "https://foreningenbs.no/w/saml/module.php/saml/sp/saml2-acs.php/default-sp",
];

// Pålogging på UKAs billettsystem - lokal utviklerversjon.
$metadata["http://localhost:8081/saml/module.php/saml/sp/metadata.php/default-sp"] = [
  "SingleLogoutService" => "http://localhost:8081/saml/module.php/saml/sp/saml2-logout.php/default-sp",
  "AssertionConsumerService" => "http://localhost:8081/saml/module.php/saml/sp/saml2-acs.php/default-sp",
];

// Pålogging på UKAs billettsystem.
$metadata["https://billett.blindernuka.no/saml/module.php/saml/sp/metadata.php/default-sp"] = [
  "SingleLogoutService" => "https://billett.blindernuka.no/saml/module.php/saml/sp/saml2-logout.php/default-sp",
  "AssertionConsumerService" => "https://billett.blindernuka.no/saml/module.php/saml/sp/saml2-acs.php/default-sp",
];

// Pålogging på dugnadssystemet.
$metadata["https://foreningenbs.no/dugnaden/saml/module.php/saml/sp/metadata.php/default-sp"] = [
  "SingleLogoutService" => "https://foreningenbs.no/dugnaden/saml/module.php/saml/sp/saml2-logout.php/default-sp",
  "AssertionConsumerService" => "https://foreningenbs.no/dugnaden/saml/module.php/saml/sp/saml2-acs.php/default-sp",
];
// For utvikling.
$metadata["http://localhost:8080/dugnaden/saml/module.php/saml/sp/metadata.php/default-sp"] = [
  "SingleLogoutService" => "http://localhost:8080/dugnaden/saml/module.php/saml/sp/saml2-logout.php/default-sp",
  "AssertionConsumerService" => "http://localhost:8080/dugnaden/saml/module.php/saml/sp/saml2-acs.php/default-sp",
];

// Pålogging på wiki (Confluence).
$metadata["https://foreningenbs.no/confluence/plugins/servlet/samlsso"] = [
  "AssertionConsumerService" => "https://foreningenbs.no/confluence/plugins/servlet/samlsso",
  "NameIDFormat" => "urn:oasis:names:tc:SAML:2.0:nameid-format:persistent",
  "simplesaml.nameidattribute" => "username",
  "saml20.sign.assertion" => true
];

// Pålogging på Google Apps for UKA.
$metadata["google.com/a/blindernuka.no"] = [
  "AssertionConsumerService" => "https://www.google.com/a/blindernuka.no/acs",
  "NameIDFormat" => "urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress",
  "simplesaml.nameidattribute" => "gapps-mail",
  "simplesaml.attributes" => false,
  "saml20.sign.assertion" => true,
  "authproc" => [
    10 => [
      "class" => "fbs:UKAGoogleApps",
      "accounts_url" => "https://foreningenbs.no/intern/api/googleapps/accounts",
      "accounts_url_auth_token" => $secret_accounts_url_auth_token,
      "userfile" => "/storage/cache/ukausers"
    ]
  ]
];

// Internside - produksjon
$metadata["https://foreningenbs.no/intern/api/saml2/metadata"] = [
  "SingleLogoutService" => "https://foreningenbs.no/intern/api/saml2/sls",
  "AssertionConsumerService" => "https://foreningenbs.no/intern/api/saml2/acs",
  "attributes" => ["username"],
];


// Internside - produksjon (sekundær URL)
$metadata["http://intern-backend.zt.foreningenbs.no/intern/api/saml2/metadata"] = [
  "SingleLogoutService" => "http://intern-backend.zt.foreningenbs.no/intern/api/saml2/sls",
  "AssertionConsumerService" => "http://intern-backend.zt.foreningenbs.no/intern/api/saml2/acs",
  "attributes" => ["username"],
];

// Internside - utvikling
$metadata["http://localhost:8081/intern/api/saml2/metadata"] = [
  "SingleLogoutService" => "http://localhost:8081/intern/api/saml2/sls",
  "AssertionConsumerService" => "http://localhost:8081/intern/api/saml2/acs",
  "attributes" => ["username"],
];
