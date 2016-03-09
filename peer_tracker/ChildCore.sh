echo "lets start pinging. yeah!"
while true; do
  ping -c 1 -W 1 192.168.61.1 > /dev/null
	sleep 1
done
