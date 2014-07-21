- MonChauffeEau -

Require: Apache / Mysql / PHP + Jpgraph Lib/ Bash

1) Create the mysql Database & User:

$ mysql -u root -p

mysql> CREATE DATABASE MonChauffeEau;

mysql> GRANT ALL PRIVILEGES ON MonChauffeEau.* to monchauff@localhost IDENTIFIED BY 'monchauff';

mysql> exit


2) Inject the database structure

mysql --user=monchauff --password=monchauff MonChauffeEau < MonChauffeEau.sql


3) Copy the Webinterface to apache Root directory (usually /var/www/)

mkdir /var/www/MonChauffeEau
sudo cp -r /webinterface/* /var/www/MonChauffeEau/


4) Write the Crontab rule

Edit the Crontab with "crontab -e"
And Add this line:

0 0 * * * /home/user/MonChauffeEau/MCEscript.sh

Meaning that the Path to the MonChauffeEau Folder is "/home/user/" and the script is launched everyday at 00:00 !

5) (Optional) Graph AutoGeneration
You need JpGraph Php Lib !


Screenshot:
![alt tag](https://raw.github.com/philmadomo/MonChauffeEau/master/ScMonChauff.png)

CmdLine for Water Consumption Graph:
sudo ln -s /tmp/dailygraph.jpg /var/www/MonChauffeEau/images/dailygraph.jpg

