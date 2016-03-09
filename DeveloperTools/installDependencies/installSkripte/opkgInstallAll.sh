#!/bin/sh

tempDir="/peertrackerDependencies"

touch $tempDir/installLog
for f in $tempDir/dependencies/*.ipk
do
	echo "installing $f"
	opkg install --force-depends $f >> $tempDir/installLog
	rm $f
	echo "deleted $f" >> $tempDir/installLog 
done

