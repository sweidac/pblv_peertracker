#!/bin/bash

source ../vocore.sh




for f in `find ../../onVoCore -mindepth 1 -maxdepth 1 -type d`
do
	echo "copying $f to VoCore"
	vocoreScp $f /

done
