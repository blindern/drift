terraform {
  required_version = ">= 0.13"

  required_providers {
    openstack = {
      source = "terraform-provider-openstack/openstack"
      version = "~> 3.3.0"
    }
  }
}
