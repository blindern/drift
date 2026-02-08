# Google Cloud

We have a project in Google Cloud called `foreningenbs`.

Some of the resources in it is managed by Terraform here.

Currently it must be manually deployed.

## Deploy

```bash
terraform init
terraform apply
```

## Host service accounts

Each host needs a GCP service account with `artifactregistry.reader`
to pull Docker images. The service account is created via Terraform,
but the key must be created manually (to avoid leaking it into state/git).

After adding a host SA in `host-service-accounts.tf` and applying:

```bash
HOST=<hostname>
cd $(mktemp -d)
gcloud iam service-accounts keys create key.json \
  --iam-account=$HOST@foreningenbs.iam.gserviceaccount.com \
  --project=foreningenbs
scp key.json root@$HOST:/var/mnt/data/google_cloud_service_account.json
rm key.json
```
