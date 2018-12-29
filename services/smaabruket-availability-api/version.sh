if [ ! -f version.txt ]; then
  echo "Missing version.txt"
else
  export SMAABRUKET_AVAILABILITY_API_TAG=$(cat version.txt)
fi
