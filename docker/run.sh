#!/bin/bash

#if docker ps -a | grep -q redis; then
#  docker kill redis; docker rm -v redis
#fi

#docker run  --name redis -d redis
docker run --rm --link redis -e container=docker --cap-add SYS_ADMIN --privileged  -it -v /run -v /run/lock -v /sys/fs/cgroup:/sys/fs/cgroup:ro \
  -p 443:443 \
  -p 80:80 \
  -p 4200:4200 \
  trinitronx/minera

