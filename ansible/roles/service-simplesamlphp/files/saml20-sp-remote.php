<?php

// See https://github.com/simplesamlphp/simplesamlphp/blob/master/metadata-templates/saml20-sp-remote.php
// See https://simplesamlphp.org/docs/stable/simplesamlphp-reference-sp-remote

// Pålogging via beskyttede sider under Apache webserver.
$metadata["https://foreningenbs.no/mellon"] = [
  "SingleLogoutService" => [
    [
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect",
      "Location" => "https://foreningenbs.no/mellon/logout",
    ],
  ],
  "AssertionConsumerService" => [
    [
      "index" => 0,
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST",
      "Location" => "https://foreningenbs.no/mellon/postResponse",
    ],
  ],
];

// Pålogging på gamle wikien (wiki-2015)
$metadata["https://foreningenbs.no/w/saml/module.php/saml/sp/metadata.php/default-sp"] = [
  "SingleLogoutService" => [
    [
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect",
      "Location" => "https://foreningenbs.no/w/saml/module.php/saml/sp/saml2-logout.php/default-sp",
    ],
  ],
  "AssertionConsumerService" => [
    [
      "index" => 0,
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST",
      "Location" => "https://foreningenbs.no/w/saml/module.php/saml/sp/saml2-acs.php/default-sp",
    ],
    [
      "index" => 1,
      "Binding" => "urn:oasis:names:tc:SAML:1.0:profiles:browser-post",
      "Location" => "https://foreningenbs.no/w/saml/module.php/saml/sp/saml1-acs.php/default-sp",
    ],
    [
      "index" => 2,
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Artifact",
      "Location" => "https://foreningenbs.no/w/saml/module.php/saml/sp/saml2-acs.php/default-sp",
    ],
    [
      "index" => 3,
      "Binding" => "urn:oasis:names:tc:SAML:1.0:profiles:artifact-01",
      "Location" => "https://foreningenbs.no/w/saml/module.php/saml/sp/saml1-acs.php/default-sp/artifact",
    ],
  ],
];

// Pålogging på UKAs billettsystem - lokal utviklerversjon.
$metadata["http://localhost:8081/saml/module.php/saml/sp/metadata.php/default-sp"] = [
  "SingleLogoutService" => [
    [
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect",
      "Location" => "http://localhost:8081/saml/module.php/saml/sp/saml2-logout.php/default-sp",
    ],
  ],
  "AssertionConsumerService" => [
    [
      "index" => 0,
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST",
      "Location" => "http://localhost:8081/saml/module.php/saml/sp/saml2-acs.php/default-sp",
    ],
    [
      "index" => 1,
      "Binding" => "urn:oasis:names:tc:SAML:1.0:profiles:browser-post",
      "Location" => "http://localhost:8081/saml/module.php/saml/sp/saml1-acs.php/default-sp",
    ],
    [
      "index" => 2,
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Artifact",
      "Location" => "http://localhost:8081/saml/module.php/saml/sp/saml2-acs.php/default-sp",
    ],
    [
      "index" => 3,
      "Binding" => "urn:oasis:names:tc:SAML:1.0:profiles:artifact-01",
      "Location" => "http://localhost:8081/saml/module.php/saml/sp/saml1-acs.php/default-sp/artifact",
    ],
  ],
  "contacts" => [
    [
      "emailAddress" => "it-gruppa@foreningenbs.no",
      "contactType" => "technical",
      "givenName" => "IT-gruppa",
    ],
  ],
];

// Pålogging på UKAs billettsystem.
$metadata["https://billett.blindernuka.no/saml/module.php/saml/sp/metadata.php/default-sp"] = [
  "SingleLogoutService" => [
    [
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect",
      "Location" => "https://billett.blindernuka.no/saml/module.php/saml/sp/saml2-logout.php/default-sp",
    ],
    [
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:SOAP",
      "Location" => "https://billett.blindernuka.no/saml/module.php/saml/sp/saml2-logout.php/default-sp",
    ],
  ],
  "AssertionConsumerService" => [
    [
      "index" => 0,
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST",
      "Location" => "https://billett.blindernuka.no/saml/module.php/saml/sp/saml2-acs.php/default-sp",
    ],
    [
      "index" => 1,
      "Binding" => "urn:oasis:names:tc:SAML:1.0:profiles:browser-post",
      "Location" => "https://billett.blindernuka.no/saml/module.php/saml/sp/saml1-acs.php/default-sp",
    ],
    [
      "index" => 2,
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Artifact",
      "Location" => "https://billett.blindernuka.no/saml/module.php/saml/sp/saml2-acs.php/default-sp",
    ],
    [
      "index" => 3,
      "Binding" => "urn:oasis:names:tc:SAML:1.0:profiles:artifact-01",
      "Location" => "https://billett.blindernuka.no/saml/module.php/saml/sp/saml1-acs.php/default-sp/artifact",
    ],
  ],
  "contacts" => [
    [
      "emailAddress" => "it-gruppa@foreningenbs.no",
      "contactType" => "technical",
      "givenName" => "IT-gruppa",
    ],
  ],
  "attributes" => NULL,
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
  "contacts" => [
    [
      "emailAddress" => "it-gruppa@foreningenbs.no",
      "contactType" => "technical",
      "givenName" => "IT-gruppa",
    ],
  ],
  "attributes" => NULL,
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
      "userfile" => "/storage/cache/ukausers"
    ]
  ]
];
