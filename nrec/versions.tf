terraform {
  required_version = ">= 0.13"

  required_providers {
    openstack = {
      source = "terraform-provider-openstack/openstack"
      version = "~> 2.1.0"
    }
  }
}
