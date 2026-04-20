FROM node:24-alpine@sha256:d1b3b4da11eefd5941e7f0b9cf17783fc99d9c6fc34884a665f40a06dbdfc94f

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
