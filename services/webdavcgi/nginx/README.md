# nginx

This is a helper container for local development so that
we can use HTTPS. We run this separate instance in front
to do this similar to has it is done in production.

We need HTTPS to set SameSite=None when developing locally,
in order for requests that come back for the SAML-service
to include cookies.
