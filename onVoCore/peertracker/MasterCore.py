from subprocess import check_output
import re
import os
import time
import thread
import VocoreSound

DB_FILEPATH = r'/peertracker/'

# Maximum inactive time of a client before it is marked as missing
MAX_INACTIVE_TIME = 5000

# Maximal distance of a child to master
# min = 2m and max = 30m
# default 10m
DISTANCE = 10

# is valid in a signal  range from -75dBm to -95 dBm
def isInRange( signalStrength ):
	if(signalStrength <= -75 and signalStrength >= -95):
		signalStrength = signalStrength * -1
		distance = int(signalStrength) * 1.5 - 109
		print("Entfernung:" + distance)
		if distance > DISTANCE:
			return 1
		else:
			return 0
	elif signalStrength > -75:
		return 1
	elif signalStrength < -95:
		return 0

def notify():
	print('beep')
	VocoreSound.signalOn()
	time.sleep(1)
	VocoreSound.signalOff()

try:
	os.remove(DB_FILEPATH + "db")
	os.makedirs(DB_FILEPATH)
except:
    pass

open(DB_FILEPATH + "db", 'a').close()

while True:
	out = check_output(["iw", "dev", "wlan0" , "station",  "dump"])

	# Get Information of each station
	array = out.split("Station")

	# load currently configured DISTANCE-value from file
	with open(DB_FILEPATH + "distance", "r") as file:
		DISTANCE = int(file.readline())

	# open database and run the checks
	with open(DB_FILEPATH + "db", "r+") as file:
		file.seek(0)
		filecontent = []
		filecontent = file.readlines()

		# Save old Data to check for missing and new clients
		dataDict = {}
		for line in filecontent:
			dataArray = line.split('|')
			# Store MAC-ID as key and registration state as value
			dataDict[dataArray[0]] = dataArray[2]

		# create the new data for the database
		newData = ''
		for station in array:
			REGEX_MAC_ADDRESS = re.compile('(?<=^ ).*(?=\(on.*\))')
			REGEX_SIGNAL = re.compile('(?<=signal:).*(?=dBm)')
			REGEX_INACTIVE_TIME = re.compile('(?<=inactive time:).*(?=ms)')

			stationMAC = REGEX_MAC_ADDRESS.search(station)
			stationSignal = REGEX_SIGNAL.search(station)
			stationInactive = REGEX_INACTIVE_TIME.search(station)

			if(stationMAC is not None and stationSignal is not None):
				# Filter MAC and Signal Strength from output of iw command
				mac = stationMAC.group(0).strip()
				signal = stationSignal.group(0).strip()

				if stationInactive is not None:
					inactiveTime = int(stationInactive.group(0).strip())
				else:
					inactiveTime = 0

				# debug output
				print("Station: " + mac + " | Signal: " + signal + " | Inactive Time: " + str(inactiveTime))

				# check if station was already in database
				isNewClient = 1
				for line in filecontent:
					lineData = line.split('|')
					oldmac = lineData[0]
					if(oldmac==mac):
						# Copy registration state from DB
						isNewClient=lineData[2]
						# Client is still active
						del dataDict[mac]

				# check if client is in range, but only if inactiveTime is under 5 seconds
				if(inactiveTime < MAX_INACTIVE_TIME):
					inRange = isInRange(signal)
				else:
					inRange = 0

				if(inRange == 0 and isNewClient == 0):
					thread.start_new_thread(notify, ())

				# build string representation of a station in database
				stationRep = mac + "|" + str(inRange) + "|" + str(isNewClient) + "\n"
				newData = newData + stationRep

		# Iterate over non active clients
		for key in dataDict:
			missingStationRep = key + "|" + str(0) + "|" + dataDict[key] + "\n"
			newData = newData + missingStationRep
			if dataDict[key] == 1:
				thread.start_new_thread(notify, ())

		# remove blank lines
		newData = newData.rstrip()
		newData = os.linesep.join([s for s in newData.splitlines() if s])
		print("---")
		print(newData)
		print("---")
		# goto first line and start writing
		file.seek(0)
		file.write(newData)

	time.sleep(1)
