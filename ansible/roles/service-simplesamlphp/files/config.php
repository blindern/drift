<?php

// See https://github.com/simplesamlphp/simplesamlphp/blob/master/config-templates/config.php

require "/var/simplesamlphp/config/secrets.php";

$config = [
  "baseurlpath" => "https://foreningenbs.no/simplesaml/",
  "certdir" => "cert/",
  "loggingdir" => "/storage/log/",
  "datadir" => "/storage/data/",
  "tempdir" => "/tmp/simplesamlphp",
  "debug" => true,
  "showerrors" => true,
  "errorreporting" => true,
  "debug.validatexml" => false,
  "auth.adminpassword" => $secret_auth_adminpass,
  "admin.protectindexpage" => false,
  "admin.protectmetadata" => false,
  "secretsalt" => $secret_secretsalt,
  "technicalcontact_name" => "IT-gruppa",
  "technicalcontact_email" => "it-gruppa@foreningenbs.no",
  "timezone" => "Europe/Oslo",
  "logging.level" => \SimpleSAML\Logger::DEBUG,
  "logging.handler" => "file",
  "logging.facility" => defined("LOG_LOCAL5") ? constant("LOG_LOCAL5") : LOG_USER,
  "logging.processname" => "simplesamlphp",
  // Relative to loggingdir.
  "logging.logfile" => "simplesamlphp.log",
  "statistics.out" => [],
  "enable.saml20-idp" => true,
  "enable.shib13-idp" => false,
  "enable.adfs-idp" => false,
  "enable.wsfed-sp" => false,
  "enable.authmemcookie" => false,
  "session.duration" => 8 * (60 * 60), // 8 hours.
  "session.datastore.timeout" => (4 * 60 * 60), // 4 hours
  "session.state.timeout" => (60 * 60), // 1 hour
  "session.cookie.name" => "_saml_idp_sid",
  "session.cookie.lifetime" => 14 * 86400,
  "session.cookie.path" => "/",
  "session.cookie.domain" => null,
  "session.cookie.secure" => true,
  "session.disable_fallback" => false,
  "enable.http_post" => false,
  "session.phpsession.cookiename" => null,
  "session.phpsession.savepath" => null,
  "session.phpsession.httponly" => false,
  "session.authtoken.cookiename" => "simplesaml_idp_token",
  "session.rememberme.enable" => true,
  "session.rememberme.checked" => true,
  "session.rememberme.lifetime" => (14 * 86400),
  "language.available" => [
    "en", "no", "nn", "se", "da", "de", "sv", "fi", "es", "fr", "it", "nl", "lb", "cs",
    "sl", "lt", "hr", "hu", "pl", "pt", "pt-br", "tr", "ja", "zh", "zh-tw", "ru", "et",
    "he", "id", "sr", "lv", "ro", "eu"
  ],
  "language.rtl" => ["ar", "dv", "fa", "ur", "he"],
  "language.default" => "no",
  "language.parameter.name" => "language",
  "language.parameter.setcookie" => true,
  "language.cookie.name" => "language",
  "language.cookie.domain" => null,
  "language.cookie.path" => "/",
  "language.cookie.lifetime" => (60 * 60 * 24 * 900),
  "attributes.extradictionary" => null,
  "theme.use" => "fbs:bstheme",
  "default-wsfed-idp" => "urn:federation:pingfederate:localhost",
  "idpdisco.enableremember" => true,
  "idpdisco.rememberchecked" => true,
  "idpdisco.validate" => true,
  "idpdisco.extDiscoveryStorage" => null,
  "idpdisco.layout" => "dropdown",
  "shib13.signresponse" => true,
  "authproc.idp" => [
    10 => [
      "class" => "saml:TransientNameID",
      "attribute" => "username",
    ],
    30 => "core:LanguageAdaptor",
    50 => "core:AttributeLimit",
    99 => "core:LanguageAdaptor",
  ],
  "authproc.sp" => [
    90 => "core:LanguageAdaptor",
  ],
  "metadata.sources" => [
    ["type" => "flatfile"],
  ],
  "store.type" => "sql",
  "store.sql.dsn" => "sqlite:/storage/sqlitedatabase.sq3",
  "store.sql.username" => null,
  "store.sql.password" => null,
  "store.sql.prefix" => "simpleSAMLphp",
  "metadata.sign.enable" => false,
  "metadata.sign.privatekey" => null,
  "metadata.sign.privatekey_pass" => null,
  "metadata.sign.certificate" => null,
  "proxy" => null,
  "trusted.url.domains" => null,
];

// For local development to override this config.
if (file_exists("/config-override.php")) {
  require "/config-override.php";
}
