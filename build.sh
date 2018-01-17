#!/usr/bin/env bash

docker build -t docker-registry.mycoachfootball.com:5000/com.globalsport/mycoach-analytics-frontend .
docker tag docker-registry.mycoachfootball.com:5000/com.globalsport/mycoach-analytics-frontend docker-registry.mycoachfootball.com:5000/com.globalsport/mycoach-analytics-frontend:$1
docker push docker-registry.mycoachfootball.com:5000/com.globalsport/mycoach-analytics-frontend:$1
