if [ ! -f version.txt ]; then
  echo "Missing version.txt"
else
  export USERS_API_TAG=$(cat version.txt)
fi
