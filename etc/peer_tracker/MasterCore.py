from subprocess import check_output
import re
import os
import time

#DB_FILEPATH = r'/etc/peer_tracker/'
DB_FILEPATH = r'/home/fabian/Dokumente/pblv_peertracker/etc/peer_tracker/'

# Maximale Entfernung vom Master in Metern
THRESHOLD = 20

# is valid in a signal  range from -75dBm to -95 dBm
def isInRange( signalStrength ):
	print("Signal:" + signalStrength)
	if(signalStrength <= -75 and signalStrength >= -95):
		signalStrength = signalStrength * -1
		distance = int(signalStrength) * 1.5 - 109
		print("Entfernung:" + distance)
		if distance > THRESHOLD:
			return 1
		else:
			return 0
	elif signalStrength > -75:
		return 1
	elif signalStrength < -95:
		return 0

def notify():
	#subprocess.check_call( [ 'echo 1 > /sys/devices/gpio-leds.5/leds/vocore:orange:eth/brightness' ] )
	print('beep')

try:
	os.remove(DB_FILEPATH + "db")
	os.makedirs(DB_FILEPATH)
except:
    pass

open(DB_FILEPATH + "db", 'a').close()
#WIFI_DEVICE_NAME = 'wlp1s0'
#WIFI_DEVICE_NAME = 'wlan0-1'

while True:
	out = check_output(["iw", "dev",  WIFI_DEVICE_NAME , "station", "dump"])
	array = out.split("Station")

	with open(DB_FILEPATH + "db", "r+") as file:
		file.seek(0)
		filecontent = []
		filecontent = file.readlines()

		# Save old Data to check for missing clients
		dataDict = {}
		for line in filecontent:
			dataArray = line.split('|')
			# Store MAC-ID as key and registration state as value
			dataDict[dataArray[0]] = dataArray[2]

		newData = ''
		for station in array:
			REGEX_MAC_ADDRESS = re.compile('(?<=^ ).*(?=\(on.*\))')
			REGEX_SIGNAL = re.compile('(?<=signal:).*(?=dBm)')

			stationMAC = REGEX_MAC_ADDRESS.search(station)
			stationSignal = REGEX_SIGNAL.search(station)

			if(stationMAC is not None and stationSignal is not None):
				# Filter MAC and Signal Strength from output of iw command
				mac = stationMAC.group(0).strip()
				signal = stationSignal.group(0).strip()

				registeredClient=0
				for line in filecontent:
					lineData = line.split('|')
					oldmac = lineData[0]
					if(oldmac==mac):
						# Copy registration state from DB
						registeredClient=lineData[2]
						# Client is still active
						del dataDict[mac]

				inRange = isInRange(signal)
				if(inRange == 0):
					notify()

				stationRep = mac + "|" + str(inRange) + "|" + str(registeredClient) + "\n"
				newData = newData + stationRep

		# Iterate over non active clients
		for key in dataDict:
			missingStationRep = key + "|" + str(0) + "|" + dataDict[key] + "\n"
			newData = newData + missingStationRep
			if dataDict[key] == 1:
				notify()

		newData = newData.rstrip()
		newData = os.linesep.join([s for s in newData.splitlines() if s])
		print("---")
		print(newData)
		print("---")
		file.seek(0)
		file.write(newData)

	time.sleep(1)
