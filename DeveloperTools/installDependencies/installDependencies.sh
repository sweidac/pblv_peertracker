#!/bin/bash

source ../vocore.sh

tempDir="/peertrackerDependencies"

vocoreSsh "mkdir -p $tempDir"
echo "Verzeichnisse auf Vocore erzeugt"
echo "Packages werden auf Vocore übertragen"
#vocoreScp  dependencies $tempDir
vocoreScp installSkripte $tempDir
echo "Packages auf Vocore übertragen"
echo "Installationsskript wird gestartet"
vocoreSsh "chmod 577 $tempDir/installSkripte/opkgInstallAll.sh"
vocoreSsh "$tempDir/installSkripte/opkgInstallAll.sh"
echo "Installationsskript gelaufen"




