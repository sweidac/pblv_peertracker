from subprocess import check_output
import re

out = check_output(["iw", "wlp1s0", "station", "dump"])
array = out.split("Station")

for station in array:
	regexMAC = re.compile('(?<=^ ).*(?=\(on.*\))')
	regexSignal= re.compile('(?<=signal:).*(?=dBm)')
	stationMAC = regexMAC.search(station)
	stationSignal = regexSignal.search(station)
	if(stationMAC is not None and stationSignal is not None):
		print(stationMAC.group(0).strip())
		print(stationSignal.group(0).strip())
