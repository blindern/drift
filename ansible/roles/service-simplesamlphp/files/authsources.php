<?php

// See https://github.com/simplesamlphp/simplesamlphp/blob/master/config-templates/authsources.php

require "/var/simplesamlphp/config/secrets.php";

$config = [
  "admin" => [
    "core:AdminPassword",
  ],
  "fbs-multi" => [
    "multiauth:MultiAuth",
    "sources" => [
      "fbs-api" => [
        "text" => [
          "en" => "Username and password for your foreningsbruker",
          "no" => "Brukernavn og passord for foreningsbrukeren din",
        ],
      ],
      "google-connect" => [
        "text" => [
          "en" => "Log in with Google",
          "no" => "Logg inn med Google",
        ],
      ],
    ],
  ],
  "google-connect" => [
    "openidconnect:Connect",
    "client_id" => $secret_google_openidconnect_client_id,
    "client_secret" => $secret_google_openidconnect_client_secret,
    "token_endpoint" => "https://www.googleapis.com/oauth2/v4/token",
    "user_info_endpoint" => "https://www.googleapis.com/oauth2/v3/userinfo",
    "auth_endpoint" => "https://accounts.google.com/o/oauth2/v2/auth",
    "sslcapath" => "/etc/ssl/certs",
  ],
  "fbs-api" => [
    "fbs:FbsApi",
    "hmac_key" => $secret_fbs_api_hmac_key,
    "api_url" => "http://users-api.zt.foreningenbs.no:8000",
  ],
  /*
  "fbs-ldap" => [
    "ldap:LDAP",
    "hostname" => "ldap.zt.foreningenbs.no",
    "enable_tls" => TRUE,
    "debug" => true,
    "timeout" => 0,
    "referrals" => TRUE,
    "attributes" => ["dn", "uidNumber", "uid", "givenName", "mobile", "sn", "mail"],
    "dnpattern" => "uid=%username%,ou=Users,dc=foreningenbs,dc=no",
    "search.enable" => FALSE,
    "search.base" => "ou=Users,dc=foreningenbs,dc=no",
    "search.attributes" => ["uid", "mail"],
    "search.username" => $secret_ldap_username,
    "search.password" => $secret_ldap_password,
    "priv.read" => FALSE,
    "priv.username" => NULL,
    "priv.password" => NULL,
  ],
  */
];
