FROM atlassian/confluence:10.2.2@sha256:7e27b388a59029fa2a0b53601f7a6c602a11b4f1c9a32652de38a3b4007bdb25

USER root

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
      xmlstarlet \
    ; \
    rm -rf /var/lib/apt/lists/*; \
    curl -Ls "https://dev.mysql.com/get/Downloads/Connector-J/mysql-connector-j-8.4.0.tar.gz" \
      | tar -xz --directory "${CONFLUENCE_INSTALL_DIR}/confluence/WEB-INF/lib" \
            --strip-components=1 \
            --no-same-owner \
            "mysql-connector-j-8.4.0/mysql-connector-j-8.4.0.jar"

# set autowatch to false
#   see https://jira.atlassian.com/browse/CONF-22070
# also set server settings to match our use
# (Disabled as I've found no way of doing this in Confluence 10)
# RUN set -x && \
#     mkdir /tmp/conf && \
#     cp /opt/atlassian/confluence/confluence/WEB-INF/lib/com.atlassian.confluence.confluence-9.*.jar /tmp/conf/confluence.jar && \
#     cd /tmp/conf && \
#     jar xf confluence.jar && \
#     sed -i '/confluence.prefs.watch.my.own.content/{N;s/\(.*\n.*\)true/\1false/}' preferences-default.xml && \
#     cp -p preferences-default.xml /opt/atlassian/confluence/confluence/WEB-INF/classes/ && \
#     rm -rf /tmp/conf
