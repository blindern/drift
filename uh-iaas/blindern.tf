provider "openstack" {
  # Username is gathered from OS_USERNAME
  # Password is gathered from OS_PASSWORD
  alias = "uh-iaas"
  tenant_name = "uio-ifi-foreningenbs"
  region = "osl"
  auth_url = "https://api.uh-iaas.no:5000/v3"
  domain_name = "dataporten"
  endpoint_type = "public"
}

# Variables for use in compute resources
variable "coreos" {
  default = "Container-Linux"
  description = "Name for CoreOS container image"
}


# --------------------------------------
# Network resources
# --------------------------------------

data "openstack_networking_network_v2" "public" {
  # retrieved using `openstack network list`
  name = "dualStack"
  network_id = "c97fa886-592e-4ad1-a995-6d55651bed78"
}


# --------------------------------------
# Security groups and rules
# --------------------------------------

resource "openstack_networking_secgroup_v2" "misc" {
  name = "role-misc"
  description = "Relaxed security group"
}

resource "openstack_networking_secgroup_rule_v2" "http" {
  direction         = "ingress"
  ethertype         = "IPv4"
  protocol          = "tcp"
  port_range_min    = 80
  port_range_max    = 80
  remote_ip_prefix  = "0.0.0.0/0"
  security_group_id = "${openstack_networking_secgroup_v2.misc.id}"
}

resource "openstack_networking_secgroup_rule_v2" "https" {
  direction         = "ingress"
  ethertype         = "IPv4"
  protocol          = "tcp"
  port_range_min    = 443
  port_range_max    = 443
  remote_ip_prefix  = "0.0.0.0/0"
  security_group_id = "${openstack_networking_secgroup_v2.misc.id}"
}

resource "openstack_networking_secgroup_rule_v2" "ssh" {
  direction         = "ingress"
  ethertype         = "IPv4"
  protocol          = "tcp"
  port_range_min    = 22
  port_range_max    = 22
  remote_ip_prefix  = "0.0.0.0/0"
  security_group_id = "${openstack_networking_secgroup_v2.misc.id}"
}

resource "openstack_networking_secgroup_rule_v2" "zerotier-udp" {
  direction         = "ingress"
  ethertype         = "IPv4"
  protocol          = "udp"
  port_range_min    = 9993
  port_range_max    = 9993
  remote_ip_prefix  = "0.0.0.0/0"
  security_group_id = "${openstack_networking_secgroup_v2.misc.id}"
}

resource "openstack_networking_secgroup_rule_v2" "zerotier-tcp" {
  direction         = "ingress"
  ethertype         = "IPv4"
  protocol          = "tcp"
  port_range_min    = 9993
  port_range_max    = 9993
  remote_ip_prefix  = "0.0.0.0/0"
  security_group_id = "${openstack_networking_secgroup_v2.misc.id}"
}

resource "openstack_networking_secgroup_rule_v2" "icmp" {
  direction         = "ingress"
  ethertype         = "IPv4"
  protocol          = "icmp"
  remote_ip_prefix  = "0.0.0.0/0"
  security_group_id = "${openstack_networking_secgroup_v2.misc.id}"
}


# --------------------------------------
# Compute resources
# --------------------------------------

resource "openstack_compute_keypair_v2" "athene" {
  name = "athene"
  public_key = "ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDfIoi7t3kD6xRr3bsYaiUv/OnOEYsbk3Q5+LixnfxsdsPf2tYbBJ+YwrtAm7/YkIiDtdk5ZtCIvVD1ZB+r0sL/BCZrXBQvmktYhtVC9RNdZYFaaL7he9nIMGxPS5UNwweQwVUN1vfFo07QAj2vOmqRAo8WIQsKQWZD0OEYodDYb+ld53JVW1R44Xcqng0aV7dkoCGrKn/fDkBVRoidDXNYfB3+tVNIv40q+5NJUweXY6jcEY2uBMrho75p1CQ/y7Jq4sXwWcQbC7cvKdsttGYtsK99ypQtsqlB3HIzRNxVEy4Zcpb+6Iuiv3FDkv9MAqyEwxajcrFK6RQd1q2Sb7sH root@foreningenbs.no"
}

data "openstack_compute_flavor_v2" "medium" {
  # 4 GB RAM + 20 GB disk
  name = "m1.medium"
}

data "openstack_compute_flavor_v2" "xlarge" {
  # 16 GB RAM + 20 GB disk
  name = "m1.xlarge"
}

resource "openstack_blockstorage_volume_v3" "volume_1" {
  name = "volume_1"
  size = 10
  lifecycle {
    prevent_destroy = true
  }
}

resource "openstack_blockstorage_volume_v3" "volume_2" {
  name = "volume_2"
  size = 50
  lifecycle {
    prevent_destroy = true
  }
}

resource "openstack_compute_instance_v2" "coreos_1" {
  name = "coreos_1"
  image_name = "${var.coreos}"
  flavor_name = "${data.openstack_compute_flavor_v2.medium.name}"
  network {
    name = "${data.openstack_networking_network_v2.public.name}"
  }
  security_groups = [
    "${openstack_networking_secgroup_v2.misc.name}"
  ]
  key_pair = "${openstack_compute_keypair_v2.athene.name}"
  user_data = "${file("coreos-config.ign")}"

  lifecycle {
    ignore_changes = [
      # Don't replace the instance when we modify user data.
      # Comment this if needed.
      "user_data"
    ]
  }
}

resource "openstack_compute_instance_v2" "coreos_2" {
  name = "coreos_2"
  image_name = "${var.coreos}"
  flavor_name = "${data.openstack_compute_flavor_v2.xlarge.name}"
  network {
    name = "${data.openstack_networking_network_v2.public.name}"
  }
  security_groups = [
    "${openstack_networking_secgroup_v2.misc.name}"
  ]
  key_pair = "${openstack_compute_keypair_v2.athene.name}"
  user_data = "${file("coreos-config.ign")}"

  lifecycle {
    ignore_changes = [
      # Don't replace the instance when we modify user data.
      # Comment this if needed.
      "user_data"
    ]
  }
}

resource "openstack_compute_volume_attach_v2" "va_1" {
  instance_id = "${openstack_compute_instance_v2.coreos_1.id}"
  volume_id = "${openstack_blockstorage_volume_v3.volume_1.id}"
}

resource "openstack_compute_volume_attach_v2" "va_2" {
  instance_id = "${openstack_compute_instance_v2.coreos_2.id}"
  volume_id = "${openstack_blockstorage_volume_v3.volume_2.id}"
}


# --------------------------------------
# Outputs
# --------------------------------------

output "coreos_1_ip" {
  value = "${openstack_compute_instance_v2.coreos_1.access_ip_v4}"
}

output "coreos_2_ip" {
  value = "${openstack_compute_instance_v2.coreos_2.access_ip_v4}"
}
