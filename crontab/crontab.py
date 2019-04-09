# coding=utf-8
import os
import re
import time
import math
import json
import pymongo
from raven import Client as Sentry_client

sentry_dsn = 'https://6261ef0f8a344bbea041e278e3a24a16:f747eae9ca4f44168132421238cda049@sentry.tinoy.xyz/5'
sentry_client = Sentry_client(sentry_dsn)

mongo_host = '127.0.0.1'
mongo_port = 12817
mongo_user = 'minecraft'
mongo_password = 'Hdvi!k#greYqrp)P'
mongo_collection = 'minecraft'

try:
    # 检查Docker服务健康状态
    check_shell = 'docker -v'
    check_result = re.match(r'(\S+)', os.popen(check_shell).read()).group(1)
    if check_result != "Docker":
        sentry_client.captureMessage('Docker error.')
    check_shell = 'docker container inspect -f "{{.State.Health.Status}}" mc'
    check_result = os.popen(check_shell).read().strip()
    if check_result != "healthy":
        sentry_client.captureMessage('Runtime error.')
        exit()

    time_now = time.strftime("%Y-%m-%d %H:%M:%S", time.localtime())

    # 连接MongoDB数据库
    mongo_conn = pymongo.MongoClient(host=mongo_host,
                                     port=mongo_port,
                                     username=mongo_user,
                                     password=mongo_password,
                                     authSource=mongo_collection)
    mongo_conn = mongo_conn[mongo_collection]

    # 获取Docker容器状态
    docker_stats = os.popen('docker stats --no-stream | grep mc').read()
    info = filter(None, docker_stats.split(' '))

    # 写入Docker信息数据
    mongo_db = mongo_conn['log']
    mongo_db.insert_one(
        {
            'timestamp': int(time.time()),
            'time': time_now,
            'cpu': info[2],
            'ram': {
                'num': info[3],
                'per': info[6]
            },
            'net': {
                'in': info[7],
                'out': info[9]
            },
            'disk': {
                'in': info[10],
                'out': info[12]
            }
        }
    )

    # 获取Docker容器信息
    docker_inspect = os.popen('docker inspect mc').read()
    info = json.loads(docker_inspect)[0]

    if info['State']['Running']:
        service_state = 'Running'
    elif info['State']['Paused']:
        service_state = 'Paused'
    elif info['State']['Restarting']:
        service_state = 'Restarting'
    elif info['State']['OOMKilled']:
        service_state = 'OOMKilled'
    else:
        service_state = 'unknown'

    start_time = info['State']['StartedAt'].split('.')[0]
    start_time = time.strptime(start_time, '%Y-%m-%dT%H:%M:%S')
    start_time = int(time.mktime(start_time))

    stop_time = info['State']['FinishedAt'].split('.')[0]
    stop_time = time.strptime(stop_time, '%Y-%m-%dT%H:%M:%S')
    stop_time = int(time.mktime(stop_time))

    # 写入Docker信息数据
    mongo_db = mongo_conn['info']
    mongo_db.update_one(
        filter={'key': 'update_time'},
        update={'$set': {'value': time_now}}
    )
    mongo_db.update_one(
        filter={'key': 'service_state'},
        update={'$set': {'value': service_state}}
    )
    mongo_db.update_one(
        filter={'key': 'service_notice'},
        update={'$set': {'value': info['State']['Status']}}
    )
    mongo_db.update_one(
        filter={'key': 'start_time'},
        update={'$set': {'value': start_time}}
    )
    mongo_db.update_one(
        filter={'key': 'stop_time'},
        update={'$set': {'value': stop_time}}
    )
    mongo_db.update_one(
        filter={'key': 'server_port'},
        update={'$set': {'value': info['NetworkSettings']['Ports']['25565/tcp'][0]['HostPort']}}
    )
    mongo_db.update_one(
        filter={'key': 'restart_count'},
        update={'$set': {'value': info['RestartCount']}}
    )
except BaseException:
    sentry_client.captureException()
