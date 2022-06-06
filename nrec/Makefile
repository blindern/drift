.PHONY: all
all:
	@echo "Invalid target"
	@exit 1

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
