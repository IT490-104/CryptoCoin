#!/bin/bash

#keep script running indefinetly
while true; do

#BTC
sudo curl https://rest.coinapi.io/v1/exchangerate/BTC/USD --request GET --header "X-CoinAPI-Key: 11099F7a-836D-46D8-B938-ACB283DE9CBC" > data.json
sudo php new.php
#LTC
sudo curl https://rest.coinapi.io/v1/exchangerate/LTC/USD --request GET --header "X-CoinAPI-Key: 11099F7a-836D-46D8-B938-ACB283DE9CBC" > data.json
sudo php new.php
#DOGE
sudo curl https://rest.coinapi.io/v1/exchangerate/DOGE/USD --request GET --header "X-CoinAPI-Key: 11099F7a-836D-46D8-B938-ACB283DE9CBC" > data.json
sudo php new.php
#XRP
sudo curl https://rest.coinapi.io/v1/exchangerate/XRP/USD --request GET --header "X-CoinAPI-Key: 11099F7a-836D-46D8-B938-ACB283DE9CBC" > data.json
sudo php new.php
#ETH
sudo curl https://rest.coinapi.io/v1/exchangerate/ETH/USD --request GET --header "X-CoinAPI-Key: 11099F7a-836D-46D8-B938-ACB283DE9CBC" > data.json
sudo php new.php

sleep 3000
#run every 50 mins so that we dont hit daily 100 API request limit
done
