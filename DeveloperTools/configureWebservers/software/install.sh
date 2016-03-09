# configure lighttpd (web-server)
cp /peertracker/web/lighttpd.conf /etc/lighttpd/lighttpd.conf
/etc/init.d/lighttpd restart
/etc/init.d/lighttpd disable

# reconfigure standard web-server
cp /peertracker/web/uhttpd /etc/config/uhttpd
/etc/init.d/uhttpd restart
/etc/init.d/uhttpd disable

# configure php5
cp /peertracker/web/php.ini /etc/php.ini

