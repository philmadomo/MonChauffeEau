#!/bin/bash
#Module purpose
#==============
# Set the relay from SOMEWHERE (url, cmdline, serial...)
# and return OK or Ex for Error
# 
# Error Case:
#   - Link broken (EU)
#   - Return Value is NOK (ER) 
#   - No Value (ED)
#Implements
#==========
# Input: URL (ex: http://192.168.1.202:40405/command/relayboard/rb1-led0/high)
# Output: OK or Ex
#
#@author: DomoPhil <philmadomo <AT> free.fr >
#@license:
#@organization: http://madomotique.wordpress.com
#------------------------------------------------

function returnvalueorerror()
{
	#printf 'param=[%s]\n' "$1"
	relayret=$(/usr/bin/wget -O - $1 2>/dev/null | sed -e 's/[{}]/''/g' | awk -v k="text" '{n=split($0,a,","); for (i=1; i<=n; i++) print a[i]}' | grep '"status" :' | sed 's/:/ /1' | awk -F" " '{ print $2 }')
	printf '%s' "$relayret"
}

#Check the parameter ${1}
if [ "${1}" ]; then
	result=$(returnvalueorerror "${1}")
	relayret=$(printf '%s' "$result" | awk -F\" '{print $(NF-1)}')
	#printf 'result=%s\n' "$result"
	if [ -n "$relayret" ]; then
		if [ "$relayret" == "OK" ]; then
			printf '%s' "$relayret"
		else
			#Return Return-Error
			printf 'ER'
			#printf 'E[%s]' "$relayret"
		fi
	else
		#Return Data Error
		printf 'ED'
	fi
else
	#Return Error Url
        printf 'EU'
fi

