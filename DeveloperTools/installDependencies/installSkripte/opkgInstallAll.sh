#!/bin/sh

peertrackerDir="/peertracker"

touch $peertrackerDir/installLog
for f in $peertrackerDir/dependencies/*.ipk
do
	echo "installing $f"
	opkg install --force-depends $f >> $peertrackerDir/installLog 
done

echo "deleting ipk files" >> $peertrackerDir/installLog
rm -r $peertrackerDir/dependencies
