from subprocess import check_output
import re
import os
import time

DB_FILEPATH = r'/var/peer_tracker/db'
THRESHOLD = -70

def getDistance( signalStrength ):
	# do some magic here
	return int(signalStrength)

try:
	os.remove(DB_FILEPATH)
	os.makedirs(r'/var/peer_tracker')
except:
    pass

open(DB_FILEPATH, 'a').close()
WIFI_DEVICE_NAME = 'wlp1s0'
#WIFI_DEVICE_NAME = 'wlan0-1'

while True:
	out = check_output(["iw", "dev",  WIFI_DEVICE_NAME , "station", "dump"])
	array = out.split("Station")

	# Read current data
	file = open(DB_FILEPATH, 'r+')
	filecontent = []
	filecontent = file.readlines()
	file.close()

	newData = ''
	for station in array:
		REGEX_MAC_ADDRESS = re.compile('(?<=^ ).*(?=\(on.*\))')
		REGEX_SIGNAL = re.compile('(?<=signal:).*(?=dBm)')

		stationMAC = REGEX_MAC_ADDRESS.search(station)
		stationSignal = REGEX_SIGNAL.search(station)

		if(stationMAC is not None and stationSignal is not None):
			mac = stationMAC.group(0).strip()
			signal = stationSignal.group(0).strip()

			registeredClient=0
			for line in filecontent:
				lineData = line.split(',')
				oldmac = lineData[0]
				if(oldmac==mac):
					registeredClient=lineData[2]

			distance = getDistance(signal)
			if(distance < THRESHOLD):
				# let it beep
				print('beep')
			else:
				print('everything alright!')

			stationRep = mac + "," + str(distance) + "," + str(registeredClient)

			newData = newData + stationRep

	file = open(DB_FILEPATH, 'w+')
	print(newData)
	file.write(newData)
	file.close()

	time.sleep(3)
