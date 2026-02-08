# See README.md for key creation steps.

resource "google_service_account" "fbshs1" {
  account_id   = "fbshs1"
  display_name = "fbshs1"
  description  = "Host service account for fbshs1 - Provisioned by Terraform"
}

resource "google_project_iam_member" "fbshs1_read_registry" {
  project = data.google_project.project.project_id
  role    = "roles/artifactregistry.reader"
  member  = "serviceAccount:${google_service_account.fbshs1.email}"
}
