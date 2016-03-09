#!/bin/sh

if [[ -z $2 ]]; then
	printf "Too few arguments. Usage:\n ./inetAcces.sh [AP SSID] [password]\n"
	
fi

touch inet.conf
CONFIGURED_INET=`cat inet.conf`

if [ -z $CONFIGURED_INET ]
then
	#echo "Configuring internet Access" > inet.conf

	# step 1
	printf "config interface wwan\n\toption proto dhcp\n" >> /etc/config/network

	# step 2
	printf "config wifi-iface\n\toption device   radio0\n\toption network  wwan\n\toption mode     sta\n\toption ssid     $1\n\toption encryption psk2\n\toption key      $2\n" >> /etc/config/wireless

	# step 3
	printf "config wifi-iface\n\toption name     wan\n\tlist network    'wan'\n\tlist network    'wwan'\n\tlist network    'wan6'\n\toption input    ACCEPT\n\toption output   ACCEPT\n\toption forward  ACCEPT\n" >> /etc/config/firewall

	echo "Configured" >> inet.conf # mark as configured

	echo "Written config files, now restart wifi..."

	wifi reload

	echo "Wifi restarted"
else
	echo "Internet Access already configured"
	echo
fi

