FROM cptactionhank/atlassian-confluence:latest

MAINTAINER Henrik Steen <henrist@henrist.net>

USER root

# set autowatch to false
# see https://jira.atlassian.com/browse/CONF-22070
RUN set -x \
  && mkdir /tmp/conf \
  && cp /opt/atlassian/confluence/confluence/WEB-INF/lib/confluence-[56]*.jar /tmp/conf/confluence.jar \
  && cd /tmp/conf \
  && jar xf confluence.jar \
  && sed -i '/confluence.prefs.watch.my.own.content/{N;s/\(.*\n.*\)true/\1false/}' preferences-default.xml \
  && cp -p preferences-default.xml /opt/atlassian/confluence/confluence/WEB-INF/classes/

USER daemon

