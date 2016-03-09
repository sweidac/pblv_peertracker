#!/bin/sh

peertrackerDir="/peertracker"

sshpass -p 'vocore' ssh -o StrictHostKeyChecking=no root@192.168.61.1 "mkdir -p $peertrackerDir"
echo "Verzeichnisse auf Vocore erzeugt"
sshpass -p 'vocore' scp -o StrictHostKeyChecking=no -r dependencies root@192.168.61.1:$peertrackerDir
sshpass -p 'vocore' scp -o StrictHostKeyChecking=no -r installSkripte root@192.168.61.1:$peertrackerDir
echo "Packages auf Vocore übertragen"
sshpass -p 'vocore' ssh -o StrictHostKeyChecking=no root@192.168.61.1 chmod 577 $peertrackerDir/installSkripte/opkgInstallAll.sh
sshpass -p 'vocore' ssh -o StrictHostKeyChecking=no root@192.168.61.1 $peertrackerDir/installSkripte/opkgInstallAll.sh
echo "Installationsskript gelaufen"
