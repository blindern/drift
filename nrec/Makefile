.PHONY: all
all:
	@echo "Invalid target"
	@exit 1

ct:
	wget https://github.com/coreos/container-linux-config-transpiler/releases/download/v0.9.0/ct-v0.9.0-x86_64-unknown-linux-gnu -O ct
	chmod +x ct

.PHONY: convert-config
convert-config: ct
	./ct \
		-in-file coreos-config.yml \
		-out-file coreos-config.ign \
		-platform openstack-metadata \
		-files-dir "$(shell pwd)" \
		-pretty

.PHONY: convert-fcos-config
convert-fcos-config:
	docker run \
		--pull=always \
		--interactive \
		--rm \
		-v "$(shell pwd):/data" \
		-w /data \
		quay.io/coreos/butane:release \
		--pretty \
		--files-dir /data \
		--strict fcos-config.bu > fcos-config.ign
