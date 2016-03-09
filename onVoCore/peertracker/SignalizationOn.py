import os
stdPath = "/sys/class/gpio/"

#Make gpio reachable
def makeReachable(gpio):
	d = os.path.dirname(stdPath + "gpio" + str(gpio))
	if not os.path.exists(d):
		os.system("export " + stdPath + "gpio" + str(gpio))
	return

#Set gpio direction
def setDirection(gpio):
	os.system("echo out > " + stdPath + "gpio" + str(gpio) +"/direction")
	return

#Set gpio on
def turnOn(gpio):
	os.system("echo 1 > " + stdPath + "gpio" + str(gpio) + "/value")
	return

pin = 23
makeReachable(pin)
setDirection(pin)
turnOn(pin)