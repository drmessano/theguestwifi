[![🚀 Deploy Main ](https://github.com/drmessano/theguestwifi/actions/workflows/main.yml/badge.svg)](https://github.com/drmessano/theguestwifi/actions/workflows/main.yml) [![🚀 Deploy Backup](https://github.com/drmessano/theguestwifi/actions/workflows/backup.yml/badge.svg)](https://github.com/drmessano/theguestwifi/actions/workflows/backup.yml)

# theguestwifi
Source for https://theguestwifi.com

Prerequisites:

* A web server with PHP.
* The qrencode package.
* A dictionary file.  I installed the wamerican-small package in Ubuntu.

On your server:

1. Install the prerequisites

```
apt install php qrencode wamerican-small
```

2. Put the theguestwifi script someplace and make it executable

```
curl https://raw.githubusercontent.com/drmessano/theguestwifi/master/theguestwifi > /usr/local/bin/theguestwifi
chmod +x /usr/local/bin/theguestwifi
```

3. Put the index.php on your web server in /var/www/theguestwifi

```
mkdir /var/www/theguestwifi
curl https://raw.githubusercontent.com/drmessano/theguestwifi/master/index.php > /var/www/theguestwifi/index.php
```

4. Run the theguestwifi script to create the password file and QR images
5. Create a systemd timer for the theguestwifi so it rotates regularly:

```
printf '[Unit]
Description=TheGuestWifi Updater
[Service]
Type=simple
ExecStart=/usr/local/bin/theguestwifi
[Install]
WantedBy=multi-user.target
' | sudo tee /etc/systemd/system/theguestwifi.service

printf '[Unit]
Description=TheGuestWifi Updater
[Timer]
OnCalendar=04:59:00 America/New_York
Unit=theguestwifi.service
[Install]
WantedBy=multi-user.target
' | sudo tee /etc/systemd/system/theguestwifi.timer

sudo systemctl daemon-reload
sudo systemctl enable theguestwifi.timer
```

#### This release adds a new Replica role, allowing Replica servers to update from a Primary server

1. Uncomment the ````primary=```` line in ````/usr/local/bin/theguestwifi````, replacing the API call with the address of your Primary server:

````
# Optional API address of Primary server if this is a Replica
# primary=https://www.theguestwifi.com?api
````

2. Edit ````/etc/systemd/system/theguestwifi.service```` and change the ````ExecStart```` line to the following:

````
ExecStart=/usr/local/bin/theguestwifi -r
````

3. Edit ````/etc/systemd/system/theguestwifi.timer```` and change the ````OnCalendar```` line to the following:

````
OnCalendar=04:59:30 America/New_York
````

This gives the Primary 30 seconds to update before the Replica updates

4. Optional: Create a file named ````serverid```` in ````/var/www/theguestwifi````.  Creating this file will generate a hidden paragraph, viewable with 'View Page Source' in the browser, that displays the identity of the server instance you are viewing.

````
printf 'primary' | tee /var/www/theguestwifi/serverid
````

Which will display in 'View Page Source':

````
<p hidden />##### ServerID: primary #####
````

RouterOS:

1. Set up a guest wireless network with the SSID "TheGuestWifi"
2. Set up a security profile named "theguestwifi" and apply it to the "TheGuestWifi" network
3. Copy the RouterOS script to your router and run it
4. Set the Scheduler to run the RouterOS script a few seconds after the theguestwifi script runs on your server  
4a. If you are using this script with https://www.theguestwifi.com, set the scheduler to run at 4:59:00 Eastern Time
