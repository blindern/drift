<?php

// See https://github.com/simplesamlphp/simplesamlphp/blob/master/metadata-templates/saml20-sp-remote.php
// See https://simplesamlphp.org/docs/stable/simplesamlphp-reference-sp-remote

require "/var/simplesamlphp/config/secrets.php";

// Pålogging via beskyttede sider under Apache webserver.
$metadata["https://foreningenbs.no/mellon"] = [
  "certData" => "MIICsDCCAZgCCQD9VVEo/CrVhDANBgkqhkiG9w0BAQsFADAaMRgwFgYDVQQDEw9mb3JlbmluZ2VuYnMubm8wHhcNMTQxMTEyMDUxNDI1WhcNMjQxMTExMDUxNDI1WjAaMRgwFgYDVQQDEw9mb3JlbmluZ2VuYnMubm8wggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDWbtY21DuKhPcxVbAtmxhTmrDvzmNdLiv2lme5AlJkWOC0PcJnBBF1ETs3PobT42EVapWzoZ0kH3S3c1HNbXvKr4xbKq5584SGOlZ7BKol7BluCy8Pi3lNZ4mPPc+rF+KfL/H4zgo+xmzdLPJnnXqvEiC+QVg7VUSOAZMUJHJUZ3ooF7DpH+fPx+UjYRjLz0A5yNrKD2kZjWL7NUB72fdqiGlGTZgrO1zwAbc2Ar+9+KoUxVdP5Ecs46Kb7FuX100WReEQnmejB8zun2b2eBC1j+0LuvlbS4gYFN6iiRHGvTNkcO2MjRBYke4QrHvLeZQo3yFwpolHhHnx888D/Ph5AgMBAAEwDQYJKoZIhvcNAQELBQADggEBABH13nU1SlT/199Ca4CippFvDi5MEFE+my0Pd+ScDiKBITOG6w8NBB7X1qhW1IzsffZpIXUJPsfHuUo+MY0NjVKJxdPSGwf/h7obVxji1GLSty3FkcP/vNJB31U0GUeRaArediQ1E4pIwv9Ws4jdE2EQePDWpWGPGpuJTWMXZYpJA5vAKuUn9TRHi9zENO8hNyNKGFnJ0QlSB6vQaMeU2ZrHuqBrA9UuJ9xhI9H1iqL3ZxLDQb6HZiHyzvasS340F56VtYhE5xkKYSDILxICkRtni3BU6iuRH4wgSwliUVmqesC/fNFobwKszKuRxjQn5hbP+9FAfRvh8euSjrBnLnU=",
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
  "certData" => "MIICuzCCAaMCFBfyif0ialQFs2kjTAgvQMEGv4SzMA0GCSqGSIb3DQEBCwUAMBoxGDAWBgNVBAMMD2ZvcmVuaW5nZW5icy5ubzAeFw0yMjA3MTUwOTQ1NTZaFw0zMjA3MTQwOTQ1NTZaMBoxGDAWBgNVBAMMD2ZvcmVuaW5nZW5icy5ubzCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAPA712cqNA4z8iqCsmZ67dotmlR83nAU1xTd4RhFU6WArDBiA53stDaGMYRTDehYwIOtfJMHGa1GnzNyxlr/xPmQu5fb1gW7FRV8j01zQ4MJ87kPmDCPEJ1DLUju1bBdWzTy90MekbdRCxsHttugk7fKssdHGfrKHHzwV9Eh5RLurHEVA7H4VaJAyk24ve2awGhqkfwHbhgZGikjCDF0xkTRCw4G4YgKsaJJgZQONG4rnYJEmgSwLsB7OAsxSGy/f9lrdOS8/HAtJVmKx4qL6nI5Fqq2jSEMu+MjkLbtZpuM+keAVgaOH0tK1bN1NNhgGwVH9LAWl8M1fPbVKQsMw5kCAwEAATANBgkqhkiG9w0BAQsFAAOCAQEAKAQKIj8oMdj8Ib1eMQ+KLLB3v6RaFvs2kXIQpMz6NLdY/Wi6dLAhfYxI/+4L9qHiB73vsNPWZ+Vn2ILBAIPenjYm21Lh6s2L9TdSG2Gq2jbUsSegcLZTDckXRg+avu3CWqfJITDZNJw8nF7vBzQOYWoMdBrPLvqTm9ipN4tjEpZSoX9SwSSRBlhxITiC4HOCQE/926JTxhhm8lkY3GLzPFalB/xIj/zMsvJASuM9xErOqo45bRklI6jSbyKjayH67/UZN5svs9B1441e86C7cBWAdCPXtFDJHJJiivl8qaznIPVBfYMmXIrFtUQUCXkPbtFviFFGrUIvAsKa9t1owQ==",
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
  "certData" => "MIICuTCCAaECFD8thYf1Ei/x0t13D5Bg9fgp7zQ6MA0GCSqGSIb3DQEBCwUAMBkxFzAVBgNVBAMMDmxvY2FsaG9zdDo4ODIwMB4XDTIyMDcxNDIzNDcyOFoXDTMyMDcxMzIzNDcyOFowGTEXMBUGA1UEAwwObG9jYWxob3N0Ojg4MjAwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDAw6gPzO7YmaZqgZfhRfWmynEIHRKRkm08YmvhvsT25rbIB5rGL2Mwv4U6o3WaFlgPa8Sq/v7nVFks34g9ZKw++nitOs6vkTZUxIIsnQC7Qg+sy3YCuSzunkU4jHZLucbH1QzEhI2SQvxxbWoK4Cy/fwR+DR/hfYmKCHxiNCWloqSsGYXdlzauhdXOFgthH/SIPiGwAxPuP38SX2fBhbH7UOTm3bhtFovZREJ3D3YVE/g16IoSQ5ksM2S0DB7sb8uONrzpn3eM5SiphTwvMtz/jtaT7CKXdboYPn9HRG7QWYHiJ1gU9NRBuQkJ3QVbam7HO0w01B6qrXnr3guk7wDnAgMBAAEwDQYJKoZIhvcNAQELBQADggEBADUlJZFCCVSNfQuikLANXL4WtgKIRNghfxMVb41jxqLXJL9/RhkKwGIn0kO4MEdzbxz+E1X2Y6NlaoH+qiorwgDBqOHrfP4Ujg7NJxLrpf/Pd3y9CZ6qQoF5Y6IOOU/GsUmbCT1m66JUzklrGFJtLAfK8KfwAGgNTa86mYdIuyVSLH6T8kfv0YceyUWnxNpRheqtStSBu3w6Dn12EVyqz24E4MCTRwTNB4mhgQHghlHl7ySCiHSZl4VRzxWeTDQR0/Ldl1ka7dbHqHLWXK6+1fy/cLcvRoYUq/N8Nxi7YrLOaNVfr9o4a//wMqEzni1Svt13uTKXJP3gIka2napU47E=",
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

// Pålogging på Snipe-IT (inventar).
$metadata["https://inventar.foreningenbs.no"] = [
  "SingleLogoutService" => [
    [
      "Location" => "https://inventar.foreningenbs.no/saml/sls",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect",
    ],
  ],
  "AssertionConsumerService" => [
    [
      "index" => 1,
      "Location" => "https://inventar.foreningenbs.no/saml/acs",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST",
    ],
  ],
  "NameIDFormat" => "urn:oasis:names:tc:SAML:2.0:nameid-format:persistent",
  "authproc" => [
    20 => [
      "class" => "saml:AttributeNameID",
      "identifyingAttribute" => "username",
      "Format" => "urn:oasis:names:tc:SAML:2.0:nameid-format:persistent",
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

// Internside - utvikling
$metadata["http://localhost:5173/intern/api/saml2/metadata"] = [
  "SingleLogoutService" => [
    [
      "Location" => "http://localhost:5173/intern/api/saml2/sls",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect",
    ],
  ],
  "AssertionConsumerService" => [
    [
      "index" => 1,
      "Location" => "http://localhost:5173/intern/api/saml2/acs",
      "Binding" => "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST",
    ],
  ],
  "attributes" => ["username"],
];
