terraform {
  required_version = ">= 0.13"

  required_providers {
    openstack = {
      source = "terraform-provider-openstack/openstack"
      version = "~> 3.4.0"
    }
  }
}
