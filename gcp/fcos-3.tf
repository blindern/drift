# See README.md for key creation steps.

resource "google_service_account" "fcos_3" {
  account_id   = "fcos-3"
  display_name = "fcos-3"
  description  = "Host service account for fcos-3 - Provisioned by Terraform"
}

resource "google_project_iam_member" "fcos_3_read_registry" {
  project = data.google_project.project.project_id
  role    = "roles/artifactregistry.reader"
  member  = "serviceAccount:${google_service_account.fcos_3.email}"
}