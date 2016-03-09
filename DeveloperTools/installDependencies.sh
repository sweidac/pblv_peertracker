#!/bin/bash

source ./vocore.sh

tempDir="/peertrackerDependencies"

vocoreSsh "mkdir -p $tempDir"

for f in `find ./dependencies/*.ipk`
do

	filename=${f##.*/}
	echo "Übertrage $f"
	vocoreScp $f $tempDir
	echo "Installiere $filename und lösche es danach"
	vocoreSsh "opkg install --force-depends $tempDir/$filename; rm $tempDir/$filename"

done




