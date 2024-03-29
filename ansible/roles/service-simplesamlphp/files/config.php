<?php

// See https://github.com/simplesamlphp/simplesamlphp/blob/master/config-templates/config.php

require "/var/simplesamlphp/config/secrets.php";

$httpUtils = new \SimpleSAML\Utils\HTTP();

$config = [
  "baseurlpath" => "https://foreningenbs.no/simplesaml/",
  "certdir" => "cert/",
  "loggingdir" => "/storage/log/",
  "datadir" => "/storage/data/",
  "tempdir" => "/tmp/simplesamlphp",
  "debug" => [
    "saml" => false,
    "backtraces" => true,
    "validatexml" => false,
  ],
  "showerrors" => false,
  "errorreporting" => false,
  "auth.adminpassword" => $secret_auth_adminpass,
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
  "enable.adfs-idp" => false,
  "session.duration" => 8 * (60 * 60), // 8 hours.
  "session.datastore.timeout" => (4 * 60 * 60), // 4 hours
  "session.state.timeout" => (60 * 60), // 1 hour
  "session.cookie.name" => "_saml_idp_sid",
  "session.cookie.lifetime" => 14 * 86400,
  "session.cookie.path" => "/",
  "session.cookie.domain" => null,
  "session.cookie.secure" => true,
  "session.cookie.samesite" => $httpUtils->canSetSameSiteNone() ? "None" : null,
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
    "en", "no", "nn", "se", "da", "de", "sv", "fi", "es", "ca", "fr", "it", "nl", "lb",
    "cs", "sk", "sl", "lt", "hr", "hu", "pl", "pt", "pt-br", "tr", "ja", "zh", "zh-tw",
    "ru", "et", "he", "id", "sr", "lv", "ro", "eu", "el", "af", "zu", "xh", "st",
  ],
  "language.rtl" => ["ar", "dv", "fa", "ur", "he"],
  "language.default" => "no",
  "language.parameter.name" => "language",
  "language.parameter.setcookie" => true,
  "language.cookie.name" => "language",
  "language.cookie.domain" => null,
  "language.cookie.path" => "/",
  "language.cookie.lifetime" => (60 * 60 * 24 * 900),
  "language.cookie.samesite" => $httpUtils->canSetSameSiteNone() ? "None" : null,
  "theme.use" => "fbs:bstheme",
  "idpdisco.enableremember" => true,
  "idpdisco.rememberchecked" => true,
  "idpdisco.validate" => true,
  "idpdisco.extDiscoveryStorage" => null,
  "idpdisco.layout" => "dropdown",
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
  "metadatadir" => "metadata",
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
  "trusted.url.domains" => ["foreningenbs.no"],
  "module.enable" => [
    "admin" => true,
    "authoauth2" => true,
    "fbs" => true,
    "multiauth" => true,
  ],
  'headers.security' => [
    'Content-Security-Policy' => "default-src 'none'; frame-ancestors 'self'; object-src 'none'; script-src 'self'; style-src 'self' 'unsafe-inline' https://maxcdn.bootstrapcdn.com; font-src 'self' https://maxcdn.bootstrapcdn.com; connect-src 'self'; img-src 'self' data:; base-uri 'none'",
  ],
];

// For local development to override this config.
if (file_exists("/config-override.php")) {
  require "/config-override.php";
}
