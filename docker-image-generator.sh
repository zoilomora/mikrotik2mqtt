#!/usr/bin/env bash

docker build \
  --target "production" \
  --tag "zoilomora/mikrotik2mqtt" \
  --tag "zoilomora/mikrotik2mqtt:$1" \
  .

docker push "zoilomora/mikrotik2mqtt"
docker push "zoilomora/mikrotik2mqtt:$1"
