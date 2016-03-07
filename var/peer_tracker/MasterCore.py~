from subprocess import check_output
import re
import os
import time

DB_FILEPATH = r'/var/peer_tracker/db'
# Maximale Entfernung vom Master in Metern
THRESHOLD = 20

# is valid in a signal  range from -75dBm to -95 dBm
def getDistance( signalStrength ):
	signalStrength = signalStrength * -1
	return int(signalStrength) * 1.5 - 109

try:
	os.remove(DB_FILEPATH)
	os.makedirs(r'/var/peer_tracker')
except:
    pass

open(DB_FILEPATH, 'a').close()
#WIFI_DEVICE_NAME = 'wlp1s0'
WIFI_DEVICE_NAME = 'wlan0-1'

while True:
	out = check_output(["iw", "dev",  WIFI_DEVICE_NAME , "station", "dump"])
	array = out.split("Station")


	with open(DB_FILEPATH, "r+") as file:
		file.seek(0)
		filecontent = []
		filecontent = file.readlines()

		# Save old Data to check for missing clients
		dataDict = {}
		for line in filecontent:
			dataArray = line.split(',')
			dataDict[dataArray[0]] = dataArray[2]

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
						# Copy registration state from DB
						registeredClient=lineData[2]
						# Client is still active
						dataDict[mac] = -1

				distance = getDistance(signal)
				if(distance > THRESHOLD):
					#subprocess.check_call( [ 'echo 1 > /sys/devices/gpio-leds.5/leds/vocore:orange:eth/brightness' ] )
					print('beep')
				else:
					#subprocess.check_call( [ 'echo 0 > /sys/devices/gpio-leds.5/leds/vocore:orange:eth/brightness' ] )
					print('everything alright!')

				stationRep = mac + "," + str(distance) + "," + str(registeredClient) + "\n"
				newData = newData + stationRep

		for key in dataDict:
			if(dataDict[key] > 0):
				missingStationRep = key + "," + str(-1) + "," + str(1) + "\n"
				newData = newData + missingStationRep
				#subprocess.check_call( [ 'echo 1 > /sys/devices/gpio-leds.5/leds/vocore:orange:eth/brightness' ] )

		newData = newData.rstrip()
		newData = os.linesep.join([s for s in newData.splitlines() if s])
		print("---")
		print(newData)
		print("---")
		file.seek(0)
		file.write(newData)

	time.sleep(1)
