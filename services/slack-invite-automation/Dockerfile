FROM node:24-alpine@sha256:a34ab6d7f6f4813f4d0c5469d74685604b962fbdbf5793a7bc7b4998ecb867c8

ENV COMMIT_SHA=95de5051925c1ecc4dae5808bdf3a22b3a428357

RUN set -eux; \
    cd /tmp; \
    wget -O- https://github.com/outsideris/slack-invite-automation/archive/${COMMIT_SHA}.zip \
      | unzip -; \
    mv /tmp/slack-* /app

WORKDIR /app
RUN npm ci

EXPOSE 3000

CMD ["node", "bin/www"]
