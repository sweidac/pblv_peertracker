#!/bin/sh

function vocoreSsh
{
sshpass -p 'vocore' ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null  root@192.168.61.1 $1
}

function vocoreScp
{
sshpass -p 'vocore' scp -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null -r $1 root@192.168.61.1:$2
}
