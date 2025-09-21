# This file is manually deployed!

provider "google" {
  project = "foreningenbs"
  region  = "europe-north2"
}

data "google_project" "project" {
}

resource "google_artifact_registry_repository" "fbs_docker" {
  location               = "europe-north2"
  repository_id          = "fbs-docker"
  description            = "Docker registry for FBS - Provisioned by Terraform"
  format                 = "DOCKER"
  cleanup_policy_dry_run = true
  docker_config {
    immutable_tags = true
  }
}

resource "google_iam_workload_identity_pool" "github" {
  workload_identity_pool_id = "github"
}

resource "google_iam_workload_identity_pool_provider" "github" {
  provider                           = google
  project                            = data.google_project.project.project_id
  workload_identity_pool_id          = google_iam_workload_identity_pool.github.workload_identity_pool_id
  workload_identity_pool_provider_id = "github"

  attribute_mapping = {
    "google.subject"             = "assertion.sub"
    "attribute.actor"            = "assertion.actor"
    "attribute.repository"       = "assertion.repository"
    "attribute.repository_owner" = "assertion.repository_owner"
  }

  oidc {
    issuer_uri = "https://token.actions.githubusercontent.com"
  }

  attribute_condition = "assertion.repository_owner=='blindern'"
}

resource "google_service_account" "github_actions" {
  account_id   = "github-actions"
  project      = data.google_project.project.project_id
  display_name = "GitHub Actions"
  description  = "github.com/blindern - Provisioned by Terraform"
}

resource "google_service_account_iam_binding" "repository_iam" {
  service_account_id = google_service_account.github_actions.name
  role               = "roles/iam.workloadIdentityUser"

  members = [
    "principalSet://iam.googleapis.com/${google_iam_workload_identity_pool.github.name}/attribute.repository_owner/blindern"
  ]
}

resource "google_project_iam_member" "github_actions_read_write_registry" {
  project = data.google_project.project.project_id
  role    = "roles/artifactregistry.writer"
  member  = "serviceAccount:${google_service_account.github_actions.email}"
}

output "github_workflow_identity_provider_name" {
  value = google_iam_workload_identity_pool_provider.github.name
}

output "github_actions_service_account_email" {
  value = google_service_account.github_actions.email
}
