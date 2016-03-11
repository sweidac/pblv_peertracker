echo "lets start pinging. yeah!"
while true; do
  arping -w 1 -U -I wlan0 0.0.0.0
	sleep 1
done
