# See README.md for key creation steps.

resource "google_service_account" "fcos_1" {
  account_id   = "fcos-1"
  display_name = "fcos-1"
  description  = "Host service account for fcos-1 - Provisioned by Terraform"
}

resource "google_project_iam_member" "fcos_1_read_registry" {
  project = data.google_project.project.project_id
  role    = "roles/artifactregistry.reader"
  member  = "serviceAccount:${google_service_account.fcos_1.email}"
}