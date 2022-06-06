provider "openstack" {
  # Username is gathered from OS_USERNAME
  # Password is gathered from OS_PASSWORD
  alias         = "nrec"
  tenant_name   = "uio-ifi-foreningenbs"
  region        = "osl"
  auth_url      = "https://api.nrec.no:5000/v3"
  domain_name   = "dataporten"
  endpoint_type = "public"
}

# Variables for use in compute resources
variable "fcos" {
  default     = "fedora-coreos-36.20220505.3.2-openstack.x86_64"
  description = "Name for Fedora CoreOS container image"
}

# --------------------------------------
# Network resources
# --------------------------------------

data "openstack_networking_network_v2" "public" {
  # retrieved using `openstack network list`
  name       = "dualStack"
  network_id = "c97fa886-592e-4ad1-a995-6d55651bed78"
}

# --------------------------------------
# Security groups and rules
# --------------------------------------

resource "openstack_networking_secgroup_v2" "misc" {
  name        = "role-misc"
  description = "Relaxed security group"
}

resource "openstack_networking_secgroup_rule_v2" "http" {
  direction         = "ingress"
  ethertype         = "IPv4"
  protocol          = "tcp"
  port_range_min    = 80
  port_range_max    = 80
  remote_ip_prefix  = "0.0.0.0/0"
  security_group_id = openstack_networking_secgroup_v2.misc.id
}

resource "openstack_networking_secgroup_rule_v2" "https" {
  direction         = "ingress"
  ethertype         = "IPv4"
  protocol          = "tcp"
  port_range_min    = 443
  port_range_max    = 443
  remote_ip_prefix  = "0.0.0.0/0"
  security_group_id = openstack_networking_secgroup_v2.misc.id
}

resource "openstack_networking_secgroup_rule_v2" "ssh" {
  direction         = "ingress"
  ethertype         = "IPv4"
  protocol          = "tcp"
  port_range_min    = 22
  port_range_max    = 22
  remote_ip_prefix  = "0.0.0.0/0"
  security_group_id = openstack_networking_secgroup_v2.misc.id
}

resource "openstack_networking_secgroup_rule_v2" "zerotier-udp" {
  direction         = "ingress"
  ethertype         = "IPv4"
  protocol          = "udp"
  port_range_min    = 9993
  port_range_max    = 9993
  remote_ip_prefix  = "0.0.0.0/0"
  security_group_id = openstack_networking_secgroup_v2.misc.id
}

resource "openstack_networking_secgroup_rule_v2" "zerotier-tcp" {
  direction         = "ingress"
  ethertype         = "IPv4"
  protocol          = "tcp"
  port_range_min    = 9993
  port_range_max    = 9993
  remote_ip_prefix  = "0.0.0.0/0"
  security_group_id = openstack_networking_secgroup_v2.misc.id
}

resource "openstack_networking_secgroup_rule_v2" "icmp" {
  direction         = "ingress"
  ethertype         = "IPv4"
  protocol          = "icmp"
  remote_ip_prefix  = "0.0.0.0/0"
  security_group_id = openstack_networking_secgroup_v2.misc.id
}

# --------------------------------------
# Compute resources
# --------------------------------------

resource "openstack_compute_keypair_v2" "athene" {
  name       = "athene"
  public_key = "ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDfIoi7t3kD6xRr3bsYaiUv/OnOEYsbk3Q5+LixnfxsdsPf2tYbBJ+YwrtAm7/YkIiDtdk5ZtCIvVD1ZB+r0sL/BCZrXBQvmktYhtVC9RNdZYFaaL7he9nIMGxPS5UNwweQwVUN1vfFo07QAj2vOmqRAo8WIQsKQWZD0OEYodDYb+ld53JVW1R44Xcqng0aV7dkoCGrKn/fDkBVRoidDXNYfB3+tVNIv40q+5NJUweXY6jcEY2uBMrho75p1CQ/y7Jq4sXwWcQbC7cvKdsttGYtsK99ypQtsqlB3HIzRNxVEy4Zcpb+6Iuiv3FDkv9MAqyEwxajcrFK6RQd1q2Sb7sH root@foreningenbs.no"
}

data "openstack_compute_flavor_v2" "medium" {
  # 1 vCPU, 4 GiB RAM, 20 GiB disk
  name = "m1.medium"
}

data "openstack_compute_flavor_v2" "large" {
  # 2 vCPU, 8 GiB RAM, 20 GiB disk
  name = "m1.large"
}

data "openstack_compute_flavor_v2" "xlarge" {
  # 4 vCPU, 16 GiB RAM, 20 GiB disk
  name = "m1.xlarge"
}

resource "openstack_blockstorage_volume_v3" "volume_5" {
  name = "volume_5"
  size = 25
  lifecycle {
    prevent_destroy = true
  }
}

resource "openstack_blockstorage_volume_v3" "volume_6" {
  name = "volume_6"
  size = 20
  lifecycle {
    prevent_destroy = true
  }
}

resource "openstack_blockstorage_volume_v3" "volume_7" {
  name = "volume_7"
  size = 50
  lifecycle {
    prevent_destroy = true
  }
}

resource "openstack_compute_instance_v2" "fcos_1" {
  name        = "fcos_1"
  image_name  = var.fcos
  flavor_name = data.openstack_compute_flavor_v2.large.name
  network {
    name = data.openstack_networking_network_v2.public.name
  }
  security_groups = [
    openstack_networking_secgroup_v2.misc.name,
  ]
  user_data = file("fcos-config.ign")

  lifecycle {
    ignore_changes = [
      # Don't replace the instance when we modify user data.
      # Comment this if needed.
      user_data,
      image_name,
    ]
  }
}

resource "openstack_compute_instance_v2" "fcos_2" {
  name        = "fcos_2"
  image_name  = var.fcos
  flavor_name = data.openstack_compute_flavor_v2.large.name
  network {
    name = data.openstack_networking_network_v2.public.name
  }
  security_groups = [
    openstack_networking_secgroup_v2.misc.name,
  ]
  user_data = file("fcos-config.ign")

  lifecycle {
    ignore_changes = [
      # Don't replace the instance when we modify user data.
      # Comment this if needed.
      user_data,
      image_name,
    ]
  }
}

resource "openstack_compute_instance_v2" "fcos_3" {
  name        = "fcos_3"
  image_name  = var.fcos
  flavor_name = data.openstack_compute_flavor_v2.xlarge.name
  network {
    name = data.openstack_networking_network_v2.public.name
  }
  security_groups = [
    openstack_networking_secgroup_v2.misc.name,
  ]
  user_data = file("fcos-config.ign")

  lifecycle {
    ignore_changes = [
      # Don't replace the instance when we modify user data.
      # Comment this if needed.
      user_data,
      image_name,
    ]
  }
}

resource "openstack_compute_volume_attach_v2" "va_5" {
  instance_id = openstack_compute_instance_v2.fcos_1.id
  volume_id   = openstack_blockstorage_volume_v3.volume_5.id
}

resource "openstack_compute_volume_attach_v2" "va_6" {
  instance_id = openstack_compute_instance_v2.fcos_2.id
  volume_id   = openstack_blockstorage_volume_v3.volume_6.id
}

resource "openstack_compute_volume_attach_v2" "va_7" {
  instance_id = openstack_compute_instance_v2.fcos_3.id
  volume_id   = openstack_blockstorage_volume_v3.volume_7.id
}

# --------------------------------------
# DNS
# --------------------------------------

# See https://uh-iaas.readthedocs.io/dns.html
# Delegation is configured in Domeneshop

resource "openstack_dns_zone_v2" "nrec_fbs" {
  name        = "nrec.foreningenbs.no."
  email       = "it-gruppa@foreningenbs.no"
  description = "FBS NREC instances"
}

resource "openstack_dns_recordset_v2" "dns_fcos_1" {
  zone_id     = "${openstack_dns_zone_v2.nrec_fbs.id}"
  name        = "fcos-1.nrec.foreningenbs.no."
  ttl         = 300
  type        = "A"
  records     = ["${openstack_compute_instance_v2.fcos_1.access_ip_v4}"]
}

resource "openstack_dns_recordset_v2" "dns_fcos_2" {
  zone_id     = "${openstack_dns_zone_v2.nrec_fbs.id}"
  name        = "fcos-2.nrec.foreningenbs.no."
  ttl         = 300
  type        = "A"
  records     = ["${openstack_compute_instance_v2.fcos_2.access_ip_v4}"]
}

resource "openstack_dns_recordset_v2" "dns_fcos_3" {
  zone_id     = "${openstack_dns_zone_v2.nrec_fbs.id}"
  name        = "fcos-3.nrec.foreningenbs.no."
  ttl         = 300
  type        = "A"
  records     = ["${openstack_compute_instance_v2.fcos_3.access_ip_v4}"]
}

# --------------------------------------
# Outputs
# --------------------------------------

output "fcos_1_ip" {
  value = openstack_compute_instance_v2.fcos_1.access_ip_v4
}

output "fcos_2_ip" {
  value = openstack_compute_instance_v2.fcos_2.access_ip_v4
}

output "fcos_3_ip" {
  value = openstack_compute_instance_v2.fcos_3.access_ip_v4
}
