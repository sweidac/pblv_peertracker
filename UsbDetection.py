import os

import subprocess

lsusbRec = subprocess.check_output("lsusb -t -v", shell=True)



def checkMassStorage():

	var = False

	if "Class=Mass Storage" in lsusbRec:

		var = True

	return var



def ensure_dir(checkFile):

	d = os.path.dirname(checkFile)

	if not os.path.exists(d):

		os.makedirs(d)



def writeToFile(conf):

	fileName = "/etc/peer_tracker/localconfig"

	ensure_dir(fileName)

	os.system("touch "+ fileName)

	f = open(fileName,"w")

	f.write(conf)

	f.close()



if checkMassStorage():

	writeToFile("master\n")

else:

	writeToFile("child\n")



