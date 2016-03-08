#!/bin/sh

peertrackerDir="/peertracker"

touch $peertrackerDir/installLog
for f in $peertrackerDir/dependencies/*.ipk
do
	echo "installing $f"
	opkg install --force-depends $f >> $peertrackerDir/installLog 
done
