FROM node:24-alpine@sha256:cd6fb7efa6490f039f3471a189214d5f548c11df1ff9e5b181aa49e22c14383e

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
