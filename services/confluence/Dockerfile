FROM cptactionhank/atlassian-confluence:7.9.3

USER root

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
    rm -rf /tmp/conf && \
    xmlstarlet ed --inplace \
      --append "//Connector" -t attr -n scheme -v https \
      --append "//Connector" -t attr -n proxyName -v foreningenbs.no \
      --append "//Connector" -t attr -n proxyPort -v 443 \
      --update "//Context[@path='']/@path" -v /confluence \
      "${CONF_INSTALL}/conf/server.xml"

USER daemon
