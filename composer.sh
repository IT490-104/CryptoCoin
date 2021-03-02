 #!/bin/bash

echo "Script initiated"

#--------------Turn on MySQL-----------------------
service mysql status | grep 'active (running)' > /dev/null 2>&1

if [ $? != 0 ]
then
        echo "MySQL was off... starting up service ... please wait..."
        sudo -S service mysql restart > /dev/null
        echo "MySQL is running..."
else
    echo "MySQL was already running..."
fi

#--------------Turn on RabbitMQ-----------------------
service rabbitmq-server status | grep 'active (running)' > /dev/null 2>&1

if [ $? != 0 ]
then
        echo "RabbitMQ was off... starting up service ... please wait..."
        sudo -S service rabbitmq-server restart > /dev/null
        echo "RabbitMQ is running..."
else
    echo "RabbitMQ was already running..."
fi
#--------------Turn on Apache-----------------------
service apache2 status | grep 'active (running)' > /dev/null 2>&1

if [ $? != 0 ]
then
        echo "Apache was off... starting up service ... please wait..."
        sudo -S service apache2 restart > /dev/null
        echo "Apache is running..."
else
    echo "Apache was already running..."
fi
#--------------Turn on Back End Scripts-----------------------
python3 --version  | grep 'not found' > /dev/null 2>&1

if [ $? != 0 ]
then
    apt install -y python3-pip
else
python3 --version
