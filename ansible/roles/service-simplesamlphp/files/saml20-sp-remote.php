<?php

// See https://github.com/simplesamlphp/simplesamlphp/blob/master/metadata-templates/saml20-sp-remote.php
// See https://simplesamlphp.org/docs/stable/simplesamlphp-reference-sp-remote

require "/var/simplesamlphp/config/secrets.php";

// Pålogging via beskyttede sider under Apache webserver.
$metadata["https://foreningenbs.no/mellon"] = [
  "SingleLogoutService" => [
    [
      "Location" => "https://foreningenbs.no/mellon/logout",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect",
    ],
  ],
  "AssertionConsumerService" => [
    [
      "index" => 1,
      "Location" => "https://foreningenbs.no/mellon/postResponse",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST",
    ],
  ],
];

// webdavcgi - produksjon
$metadata["https://foreningenbs.no/filer-mellon"] = [
  "SingleLogoutService" => [
    [
      "Location" => "https://foreningenbs.no/filer-mellon/logout",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect",
    ],
  ],
  "AssertionConsumerService" => [
    [
      "index" => 1,
      "Location" => "https://foreningenbs.no/filer-mellon/postResponse",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST",
    ],
  ],
];

// webdavcgi - lokal utvikling
$metadata["https://localhost:8820/filer-mellon"] = [
  "SingleLogoutService" => [
    [
      "Location" => "https://localhost:8820/filer-mellon/logout",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect",
    ],
  ],
  "AssertionConsumerService" => [
    [
      "index" => 1,
      "Location" => "https://localhost:8820/filer-mellon/postResponse",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST",
    ],
  ],
];

// Pålogging på gamle wikien (wiki-2015)
$metadata["https://foreningenbs.no/w/saml/module.php/saml/sp/metadata.php/default-sp"] = [
  "SingleLogoutService" => [
    [
      "Location" => "https://foreningenbs.no/w/saml/module.php/saml/sp/saml2-logout.php/default-sp",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect",
    ],
  ],
  "AssertionConsumerService" => [
    [
      "index" => 1,
      "Location" => "https://foreningenbs.no/w/saml/module.php/saml/sp/saml2-acs.php/default-sp",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST",
    ],
  ],
];

// Pålogging på UKAs billettsystem - lokal utviklerversjon. Gammel versjon.
$metadata["http://localhost:8081/saml/module.php/saml/sp/metadata.php/default-sp"] = [
  "SingleLogoutService" => [
    [
      "Location" => "http://localhost:8081/saml/module.php/saml/sp/saml2-logout.php/default-sp",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect",
    ],
  ],
  "AssertionConsumerService" => [
    [
      "index" => 1,
      "Location" => "http://localhost:8081/saml/module.php/saml/sp/saml2-acs.php/default-sp",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST",
    ],
  ],
];

// Pålogging på UKAs billettsystem. Gammel versjon.
$metadata["https://billett.blindernuka.no/saml/module.php/saml/sp/metadata.php/default-sp"] = [
  "SingleLogoutService" => [
    [
      "Location" => "https://billett.blindernuka.no/saml/module.php/saml/sp/saml2-logout.php/default-sp",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect",
    ],
  ],
  "AssertionConsumerService" => [
    [
      "index" => 1,
      "Location" => "https://billett.blindernuka.no/saml/module.php/saml/sp/saml2-acs.php/default-sp",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST",
    ],
  ],
];

// Pålogging på UKAs billettsystem - lokal utviklerversjon. 2024-versjon.
$metadata["http://localhost:8081/api/saml2/metadata"] = [
  "SingleLogoutService" => [
    [
      "Location" => "http://localhost:8081/api/saml2/sls",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect",
    ],
  ],
  "AssertionConsumerService" => [
    [
      "index" => 1,
      "Location" => "http://localhost:8081/api/saml2/acs",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST",
    ],
  ],
];

// Pålogging på UKAs billettsystem. 2024-versjon.
$metadata["https://billett.blindernuka.no/api/saml2/metadata"] = [
  "SingleLogoutService" => [
    [
      "Location" => "https://billett.blindernuka.no/api/saml2/sls",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect",
    ],
  ],
  "AssertionConsumerService" => [
    [
      "index" => 1,
      "Location" => "https://billett.blindernuka.no/api/saml2/acs",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST",
    ],
  ],
];

// Pålogging på dugnadssystemet.
$metadata["https://foreningenbs.no/dugnaden/saml/module.php/saml/sp/metadata.php/default-sp"] = [
  "SingleLogoutService" => [
    [
      "Location" => "https://foreningenbs.no/dugnaden/saml/module.php/saml/sp/saml2-logout.php/default-sp",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect",
    ],
  ],
  "AssertionConsumerService" => [
    [
      "index" => 1,
      "Location" => "https://foreningenbs.no/dugnaden/saml/module.php/saml/sp/saml2-acs.php/default-sp",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST",
    ],
  ],
];
// For utvikling.
$metadata["http://localhost:8080/dugnaden/saml/module.php/saml/sp/metadata.php/default-sp"] = [
  "SingleLogoutService" => [
    [
      "Location" => "http://localhost:8080/dugnaden/saml/module.php/saml/sp/saml2-logout.php/default-sp",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect",
    ],
  ],
  "AssertionConsumerService" => [
    [
      "index" => 1,
      "Location" => "http://localhost:8080/dugnaden/saml/module.php/saml/sp/saml2-acs.php/default-sp",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST",
    ],
  ],
];

// Pålogging på wiki (Confluence).
$metadata["https://foreningenbs.no/confluence/plugins/servlet/samlsso"] = [
  "AssertionConsumerService" => [
    [
      "index" => 1,
      "Location" => "https://foreningenbs.no/confluence/plugins/servlet/samlsso",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST",
    ],
  ],
  "NameIDFormat" => "urn:oasis:names:tc:SAML:2.0:nameid-format:persistent",
  "saml20.sign.assertion" => true,
  "authproc" => [
    20 => [
      "class" => "saml:AttributeNameID",
      "identifyingAttribute" => "username",
      "Format" => "urn:oasis:names:tc:SAML:2.0:nameid-format:persistent",
    ],
  ]
];

// Pålogging på Google Apps for UKA.
$metadata["google.com/a/blindernuka.no"] = [
  "AssertionConsumerService" => [
    [
      "index" => 1,
      "Location" => "https://www.google.com/a/blindernuka.no/acs",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST",
    ],
  ],
  "NameIDFormat" => "urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress",
  "simplesaml.attributes" => false,
  "saml20.sign.assertion" => true,
  "authproc" => [
    10 => [
      "class" => "fbs:UKAGoogleApps",
      "accounts_url" => "https://foreningenbs.no/intern/api/googleapps/accounts",
      "accounts_url_auth_token" => $secret_accounts_url_auth_token,
      "userfile" => "/storage/cache/ukausers"
    ],
    20 => [
      "class" => "saml:AttributeNameID",
      "identifyingAttribute" => "gapps-mail",
      "Format" => "urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress",
    ],
  ]
];

// Internside - produksjon
$metadata["https://foreningenbs.no/intern/api/saml2/metadata"] = [
  "SingleLogoutService" => [
    [
      "Location" => "https://foreningenbs.no/intern/api/saml2/sls",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect",
    ],
  ],
  "AssertionConsumerService" => [
    [
      "index" => 1,
      "Location" => "https://foreningenbs.no/intern/api/saml2/acs",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST",
    ],
  ],
  "attributes" => ["username"],
];


// Internside - produksjon (sekundær URL)
$metadata["http://intern-backend.zt.foreningenbs.no/intern/api/saml2/metadata"] = [
  "SingleLogoutService" => [
    [
      "Location" => "http://intern-backend.zt.foreningenbs.no/intern/api/saml2/sls",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect",
    ],
  ],
  "AssertionConsumerService" => [
    [
      "index" => 1,
      "Location" => "http://intern-backend.zt.foreningenbs.no/intern/api/saml2/acs",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST",
    ],
  ],
  "attributes" => ["username"],
];

// Internside - utvikling
$metadata["http://localhost:8081/intern/api/saml2/metadata"] = [
  "SingleLogoutService" => [
    [
      "Location" => "http://localhost:8081/intern/api/saml2/sls",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect",
    ],
  ],
  "AssertionConsumerService" => [
    [
      "index" => 1,
      "Location" => "http://localhost:8081/intern/api/saml2/acs",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST",
    ],
  ],
  "attributes" => ["username"],
];
