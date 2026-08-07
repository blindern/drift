FROM node:24-alpine@sha256:d32cdf619f63fe0471182d08996dd516c6275bb5fd31ae06e55a570bd9e1ad43

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
