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

// inventar.foreningenbs.no
$metadata['https://inventar.foreningenbs.no/saml/metadata'] = array(
    'SingleSignOnService'  => 'https://foreningenbs.no/simplesaml/saml2/idp/SSOService.php',
    'SingleLogoutService'  => 'https://foreningenbs.no/simplesaml/saml2/idp/SingleLogoutService.php',
    'certData'             => 'MIIEOzCCAyOgAwIBAgIJAJB1ClZFzgNIMA0GCSqGSIb3DQEBCwUAMIGzMQswCQYDVQQGEwJOTzENMAsGA1UECAwET3NsbzENMAsGA1UEBwwET3NsbzEqMCgGA1UECgwhRm9yZW5pbmdlbiBCbGluZGVybiBTdHVkZW50ZXJoamVtMRIwEAYDVQQLDAlJVC1ncnVwcGExHDAaBgNVBAMME2lkcC5mb3JlbmluZ2VuYnMubm8xKDAmBgkqhkiG9w0BCQEWGWl0LWdydXBwYUBmb3JlbmluZ2VuYnMubm8wHhcNMTQxMTEyMDAyODE2WhcNMjQxMTExMDAyODE2WjCBszELMAkGA1UEBhMCTk8xDTALBgNVBAgMBE9zbG8xDTALBgNVBAcMBE9zbG8xKjAoBgNVBAoMIUZvcmVuaW5nZW4gQmxpbmRlcm4gU3R1ZGVudGVyaGplbTESMBAGA1UECwwJSVQtZ3J1cHBhMRwwGgYDVQQDDBNpZHAuZm9yZW5pbmdlbmJzLm5vMSgwJgYJKoZIhvcNAQkBFhlpdC1ncnVwcGFAZm9yZW5pbmdlbmJzLm5vMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAr1ufEAkElJH9EpVX/xGZsUV7R17PpGWCHvnF6+nHTAKQxSM57h9UmjxdMx1jShbU0AWm6IVt4KPRyBXGEFfqVuXYvuU5pjGDpK1I9fAn/Fpkw0fe+RQQYq2QT0iKPDUkmjcq99WirJKMzUwfO7KuUV4lvctBnMx7s/1K6olq8HzY6km70kji46vmU45YiMgyo1TL3keVb+zVKgbjEX6P7Hm0Q7eXXY+3NHIqaKQ8N6d5xOT7mVuRqhvKAlwqUO296KhYBSgntElmH3/f/QayEaFoDbpMuWBbSmnLCNcQch+qYM/wFKFxt6i5AZRVmzTZAB8WkiKepvGiFCWujXRhNQIDAQABo1AwTjAdBgNVHQ4EFgQULdQSA/j4QuWMrB0SGhkyDW6Dai4wHwYDVR0jBBgwFoAULdQSA/j4QuWMrB0SGhkyDW6Dai4wDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAQEAfBjmva4UE7/0u4+g4JiTTSsX5ZaveccHxTV7JqneFBbj7OmPQsaOpgFpaiwjyM1XIbOGKK3/A0sTAmKGwQC8o+VwQTAHiZhtv3CqWLY0MVZ03OYuuhX/q5AQij3FXUTriUfMaoqqsKX8hh8BTK4wcntCi4qYHihtvXsfrCnrJwl+Y811LziUKDFJymv3ZXYsTsiFqB7KI6+3YCe8mKy9KwYSHz5qDktwGERAShEvRDHVZ1kSARChrdSgf0LcuIO9nFd3O3x2VzMHC2vZj91KsX8tWHErodHxtZcHMpzOJSIvBY5cZx/qtCifl3yVYGxhUg4kl67afV5M2DRuvA7XXQ==',
);

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
