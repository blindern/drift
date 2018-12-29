if [ -f .keystone-private.sh ]; then
  source .keystone-private.sh
fi

# Put these in a separate file named .keystone-private.sh
# which will not be commited to Git.
#export OS_USERNAME=
#export OS_PASSWORD=

export OS_PROJECT_NAME=uio-ifi-foreningenbs
export OS_AUTH_URL=https://api.uh-iaas.no:5000/v3
export OS_IDENTITY_API_VERSION=3
export OS_USER_DOMAIN_NAME=dataporten
export OS_PROJECT_DOMAIN_NAME=dataporten
export OS_REGION_NAME=osl
export OS_NO_CACHE=1
