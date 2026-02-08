# See README.md for key creation steps.

resource "google_service_account" "fcos_2" {
  account_id   = "fcos-2"
  display_name = "fcos-2"
  description  = "Host service account for fcos-2 - Provisioned by Terraform"
}

resource "google_project_iam_member" "fcos_2_read_registry" {
  project = data.google_project.project.project_id
  role    = "roles/artifactregistry.reader"
  member  = "serviceAccount:${google_service_account.fcos_2.email}"
}