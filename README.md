- MonChauffeEau -

Require: Apache / Mysql / PHP + Jpgraph Lib/ Bash

1) Create the mysql Database & User:

$ mysql -u root -p

mysql> CREATE DATABASE MonChauffeEau;

mysql> GRANT ALL PRIVILEGES ON MonChauffeEau.* to monchauff@localhost IDENTIFIED BY 'monchauff';

mysql> exit


2) Inject the database structure

TO DO...


3) Copy the Webinterface to apache Root directory (usually /var/www/)

TO DO...


4) Write the Crontab rule

TO DO...


Screenshot:
![alt tag](https://raw.github.com/philmadomo/MonChauffeEau/master/ScMonChauff.png)

CmdLine for Water Consumption Graph:
sudo ln -s /tmp/dailygraph.jpg /var/www/MonChauffeEau/images/dailygraph.jpg

