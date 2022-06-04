FROM atlassian/confluence:7.18.1

USER root

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
      xmlstarlet \
    ; \
    rm -rf /var/lib/apt/lists/*; \
    curl -Ls "https://dev.mysql.com/get/Downloads/Connector-J/mysql-connector-java-8.0.29.tar.gz" \
      | tar -xz --directory "${CONFLUENCE_INSTALL_DIR}/confluence/WEB-INF/lib" \
            --strip-components=1 \
            --no-same-owner \
            "mysql-connector-java-8.0.29/mysql-connector-java-8.0.29.jar"

# set autowatch to false
#   see https://jira.atlassian.com/browse/CONF-22070
# also set server settings to match our use
RUN set -x && \
    mkdir /tmp/conf && \
    cp /opt/atlassian/confluence/confluence/WEB-INF/lib/confluence-[567]*.jar /tmp/conf/confluence.jar && \
    cd /tmp/conf && \
    jar xf confluence.jar && \
    sed -i '/confluence.prefs.watch.my.own.content/{N;s/\(.*\n.*\)true/\1false/}' preferences-default.xml && \
    cp -p preferences-default.xml /opt/atlassian/confluence/confluence/WEB-INF/classes/ && \
    rm -rf /tmp/conf
