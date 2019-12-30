<?php

// SAML 2.0 IdP configuration for simpleSAMLphp.
// See https://github.com/simplesamlphp/simplesamlphp/blob/master/metadata-templates/saml20-idp-hosted.php
// See https://simplesamlphp.org/docs/stable/simplesamlphp-reference-idp-hosted

require "/var/simplesamlphp/config/secrets.php";

$metadata["__DYNAMIC:1__"] = [
  "host" => "__DEFAULT__",
  // Relative to cert directory.
  "privatekey" => "idp.foreningenbs.no.pem",
  // Relative to cert directory.
  "certificate" => "idp.foreningenbs.no.crt",
  "auth" => "fbs-multi",
  "authproc" => [
    5 => [
      "class" => "fbs:GoogleAccount",
      "hmac_key" => $secret_fbs_api_hmac_key,
      "api_url" => "https://foreningenbs.no/users-api",
    ],
  ],
];
