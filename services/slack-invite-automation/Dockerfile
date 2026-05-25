FROM node:24-alpine@sha256:2bdb65ed1dab192432bc31c95f94155ca5ad7fc1392fb7eb7526ab682fa5bf14

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
