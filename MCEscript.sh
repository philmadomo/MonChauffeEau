#!/bin/bash
#Module purpose
#==============
#
# Script to control startup and stop of a WaterHeater
#
#Implements
#==========
# Options: debugmode=1
#
#@author: DomoPhil <philmadomo <AT> free.fr >
#@license:
#@organization: http://madomotique.wordpress.com
#------------------------------------------------
#
# STATIC VAR:
debugmode=0
scriptdir=/home/philippe/MonChauffeEau
logfile=/var/log/MonChauffeEau.log
percenthotwater=40 #Debug : Hotwater usage in the global water usage
suffixtosleep=m
xplmessageon=1 #Send XPL Message ? You need Send.py file for Domogik
pathtosendxpl=/home/philippe/domogik-0.1.0/src/domogik/xpl/bin
graphgen=1
#-----------------------------------------------

function returnsleeptime(){
	HCEnd=$1
	MCEss=$2
	Htime=$3
	echo $((HCEnd*60-Htime-MCEss*60))
}

monthnow=$(date +%m)
daynow=$(date +%d)
yearnow=$(date +%Y)
monthyesterday=$(date +%m -d yesterday)
dayyesterday=$(date +%d -d yesterday)
yearyesterday=$(date +%Y -d yesterday)
nowheatingtime=0
nowwatthour=0
errorcode=0

if [ "${1}" ]; then
        debugmode=1
	echo "[Debug Mode ON]"
	echo "Today: $daynow-$monthnow-$yearnow"
	echo "Yesterday: $dayyesterday-$monthyesterday-$yearyesterday"
fi

eval $(php5 $scriptdir/GetMCEinfo.php) #Get Zone Info
if [ "$debugmode" = "1" ]; then
	echo "WaterHeaterMode:$WaterHeaterMode (HCEndTime=$HCEndTime MCEss=$MCEStartScriptTime)"
fi

#--- Check if VAR is Set before
Mode0MonthRestart=$(date +%m --date="$Mode0ModeReStartDate")
Mode0DayRestart=$(date +%d --date="$Mode0ModeReStartDate")
Mode0YearRestart=$(date +%Y --date="$Mode0ModeReStartDate")
SummerModeStartMonth=$(date +%m --date="$SummerModeStartDate")
SummerModeStartDay=$(date +%d --date="$SummerModeStartDate")
#SummerModeStartYear=$(date +%Y --date="$SummerModeStartDate")
SummerModeEndDay=$(date +%d --date="$SummerModeEndDate")
SummerModeEndMonth=$(date +%m --date="$SummerModeEndDate")
#SummerModeEndYear=$(date +%Y --date="$SummerModeEndDate")

if [ "$debugmode" = "1" ]; then
        echo "Mode0RestartDay-Month: $Mode0DayRestart-$Mode0MonthRestart"
	echo "SummerMode Day-Month: $SummerModeStartDay-$SummerModeStartMonth -> $SummerModeEndDay-$SummerModeEndMonth"
	echo "CmdCold:$CmdGetColdWater , CmdHot:$CmdGetHotWater"
fi

#before Update3 : CmdGetColdWater and GetWaterYesterday.php 1 30
coldwaterusage=$(php5 $scriptdir/$CmdGetColdWater)
if [ "$(printf "$coldwaterusage" | cut -b "1")" = "E" ]; then #test Error
	if [ "$debugmode" = "1" ]; then
		echo "ColdWaterUsage Error"
	fi
	coldwatererror=1
        coldwaterusage=0 # Set Cold Water Usage Value to 0 in case of Error
	errorcode=1
else
	coldwaterusage=${coldwaterusage/.*}
fi
if [ "$debugmode" = "1" ]; then
        echo "ColdWaterUsage:$coldwaterusage"
fi

hotwaterusage=$(php5 $scriptdir/$CmdGetHotWater)
if [ "$(printf "$hotwaterusage" | cut -b "1")" = "E" ]; then #test Error
        if [ "$debugmode" = "1" ]; then
                echo "HotWaterUsage Error"
        fi
        hotwatererror=1
        hotwaterusage=0 # Set Hot Water Usage Value to 0 in case of Error
        errorcode=1
else
        hotwaterusage=${hotwaterusage/.*}
fi
if [ "$debugmode" = "1" ]; then
        echo "HotWaterUsage:$hotwaterusage"
fi

case "$WaterHeaterMode" in 
"0") #Mode Disabled
        if [ "$debugmode" = "1" ]; then
                echo "Mode 0 WaterHeater Disabled until $Mode0DayRestart-$Mode0MonthRestart-$Mode0YearRestart and then Mode $Mode0ModeTransition"
        fi
#	if [[ "$daynow" = "$Mode0DayRestart" ]] && [[ "$monthnow" = "$Mode0MonthRestart" ]] && [[ "$yearnow" = "$Mode0YearRestart" ]]; then
#
	if [[ "$daynow" -ge "$Mode0DayRestart" ]] && [[ "$monthnow" -ge "$Mode0MonthRestart" ]] && [[ "$yearnow" -ge "$Mode0YearRestart" ]]; then       	
		if [ "$debugmode" = "1" ]; then
			echo "Mode 0: Day of Mode Change Reach switch to $Mode0ModeTransition"
		fi
		# Switch to $Mode0ModeTransition
		if [ "$(php5 -f $scriptdir/MCEsetMode.php 0 $Mode0ModeTransition)" = "1" ]; then
	                if [ "$debugmode" = "1" ]; then
	                        echo "Error With SetMode Script"
			fi
			errorcode=1
	        fi

	fi
	LastDayConso=$hotwaterusage
        LastDaysConsoWithoutHeating=$((LastDaysConsoWithoutHeating+hotwaterusage))
	NumberDaysWithoutHeating=$((NumberDaysWithoutHeating+1))
	;;

"2") #Mode Forced
	if [ "$debugmode" = "1" ]; then
                echo "Mode 2 WaterHeater Forced then Mode $Mode2ModeTransition"
        fi
	#Mode2HeatingTime	
	#Mode2ModeTransition
	sleepbeforeheating=$(returnsleeptime $HCEndTime $MCEStartScriptTime $Mode2HeatingTime)
	if [ "$debugmode" = "1" ]; then
                echo "Sleep $sleepbeforeheating Minutes, WaterHeater ON ($CmdRelayOn) and wait $Mode2HeatingTime Minutes"
        fi
	if [ "$debugmode" = "0" ]; then
		sleep $sleepbeforeheating$suffixtosleep
        fi
	#Before Update 4
	#if [ $($scriptdir/MCEsetrelay.sh $CmdRelayOn) = "OK" ];then
	if [ $($CmdRelayOn) = "OK" ];then
                if [ "$debugmode" = "1" ]; then
                        echo "WaterHeater Set to [High]"
                fi
        else
                if [ "$debugmode" = "1" ]; then
                        echo "WaterHeater NOT Set to [High]"
                fi
		errorcode=2
        fi
        nowheatingtime=$Mode2HeatingTime
	sleep $nowheatingtime$suffixtosleep
        if [ "$debugmode" = "1" ]; then
                echo "WaterHeater OFF ($CmdRelayOff)"
        fi
	#Before Update 4
	#if [ $($scriptdir/MCEsetrelay.sh $CmdRelayOff) = "OK" ];then
        if [ $($CmdRelayOff) = "OK" ];then
                if [ "$debugmode" = "1" ]; then
                        echo "WaterHeater Set to [low]"
                fi
        else
                if [ "$debugmode" = "1" ]; then
                        echo "WaterHeater NOT Set to [low]"
                fi
		errorcode=2
        fi
	# Switch to $Mode2ModeTransition
	if [ "$(php5 -f $scriptdir/MCEsetMode.php 0 $Mode2ModeTransition)" = "1" ]; then
		if [ "$debugmode" = "1" ]; then
			echo "Error With SetMode Script"
		fi
	fi
        LastDayConso=$hotwaterusage
        LastDaysConsoWithoutHeating=0
        NumberDaysWithoutHeating=0 
	;;
	
"3") #Mode ECO
	MaxHotWater=$((Mode3WaterHeaterCapacity*Mode3WaterUsagePerCentWithoutHeating/100))
	WaterHeating=0
	if [ "$debugmode" = "1" ]; then
		echo "Mode 3 WaterHeater in ECO Mode "
		echo "MaxDayWithoutHeating : $Mode3MaxDayWithoutHeating"
		echo "MaxHotWaterUsageWithoutHeating : $MaxHotWater"
	fi
	LastDayConso=$hotwaterusage
	LastDaysConsoWithoutHeating=$((LastDaysConsoWithoutHeating+hotwaterusage))
	NumberDaysWithoutHeating=$((NumberDaysWithoutHeating+1))
	#Test LastDaysConsoWithoutHeating > Mode3MaxDayWithoutHeating
	# AND Test LastDaysConsoWithoutHeating < Mode3WaterHeaterCapacity * Mode3WaterUsagePerCentWithoutHeating / 100
	if [ "$NumberDaysWithoutHeating" -gt "$Mode3MaxDayWithoutHeating" ]; then
		if [ "$debugmode" = "1" ]; then
			echo "MaxDayWithoutHeating Reach"
		fi
		nowheatingtime=$Mode1HeatingTime
		WaterHeating=1
	fi
	if [ "$LastDaysConsoWithoutHeating" -gt "$MaxHotWater" ]; then
		if [ "$debugmode" = "1" ]; then
			echo "MaxWaterUsageWithoutHeating Reach"
		fi
		nowheatingtime=$Mode1HeatingTime
		WaterHeating=1
	fi
	if [[ "$MinHeating" -ne "0" ]] && [[ "$WaterHeating" -ne "1" ]]; then
		if [ "$debugmode" = "1" ]; then
                        echo "MinHeating is Enable to $MinHeating minutes"
                fi
		nowheatingtime=$MinHeating
		WaterHeating=1 
	fi
	
	if [ "$WaterHeating" -eq "1" ]; then
		sleepbeforeheating=$(returnsleeptime $HCEndTime $MCEStartScriptTime $nowheatingtime)
		if [ "$debugmode" = "1" ]; then
        	        echo "Sleep $sleepbeforeheating Minutes, WaterHeater ON ($CmdRelayOn) and wait $nowheatingtime Minutes"
		fi
		if [ "$debugmode" = "0" ]; then
			sleep $sleepbeforeheating$suffixtosleep
		fi
		#Before Update 4
		#if [ $($scriptdir/MCEsetrelay.sh $CmdRelayOn) = "OK" ];then
		if [ $($CmdRelayOn) = "OK" ];then
			if [ "$debugmode" = "1" ]; then
				echo "WaterHeater Set to [High]"
			fi
		else
			if [ "$debugmode" = "1" ]; then
				echo "WaterHeater NOT Set to [High]"
			fi
			errorcode=2
			nowheatingtime=0
		fi
		sleep $nowheatingtime$suffixtosleep
		if [ "$debugmode" = "1" ]; then
			echo "WaterHeater OFF ($CmdRelayOff)"
		fi
		#Before Update 4
		#if [ $($scriptdir/MCEsetrelay.sh $CmdRelayOff) = "OK" ];then
		if [ $($CmdRelayOff) = "OK" ];then
			if [ "$debugmode" = "1" ]; then
				echo "WaterHeater Set to [low]"
			fi
		else
			if [ "$debugmode" = "1" ]; then
				echo "WaterHeater NOT Set to [low]"
			fi
			errorcode=2
		fi
		LastDayConso=$hotwaterusage
		LastDaysConsoWithoutHeating=0
		NumberDaysWithoutHeating=0
	else
		if [ "$debugmode" = "1" ]; then
			echo "No Water Heating"
		fi
		#LastDayConso=$hotwaterusage
		#LastDaysConsoWithoutHeating=$((LastDaysConsoWithoutHeating+hotwaterusage))
		#NumberDaysWithoutHeating=$((NumberDaysWithoutHeating+1))
	fi
	;;
*)  #Mode Normal
	if [ "$debugmode" = "1" ]; then
		echo "Mode 1 WaterHeater in Normal Mode"
	fi
	if [ "$Mode1SummerEnable" = "1" ]; then
		if [ "$debugmode" = "1" ]; then
			echo "Mode 1 SummerMode Enable"
		fi
		#Test if Date is inbetween the Summer Mode Date
		if [ "$monthnow" -gt "$SummerModeStartMonth" -a "$monthnow" -lt "$SummerModeEndMonth" -o "$monthnow" -eq "$SummerModeStartMonth" -a "$daynow" -ge "$SummerModeStartDay" -o "$monthnow" -eq "$SummerModeEndMonth" -a "$daynow" -le "$SummerModeEndDay" ]; then
        	        if [ "$debugmode" = "1" ]; then
        	                echo "Date in SummerMode: $SummerModeStartDay/$SummerModeStartMonth < $daynow/$monthnow < $SummerModeEndDay/$SummerModeEndMonth"
        	        fi
			nowheatingtime=$SummerModeHeatingTime
		else
			if [ "$debugmode" = "1" ]; then
        	                echo "Date NOT in SummerMode: $SummerModeStartDay/$SummerModeStartMonth < X < $SummerModeEndDay/$SummerModeEndMonth"
        	        fi
			nowheatingtime=$Mode1HeatingTime
		fi
	else
		if [ "$debugmode" = "1" ]; then
			echo "Mode 1 SummerMode Disable"
		fi
		nowheatingtime=$Mode1HeatingTime
	fi
	#if [ "$debugmode" = "1" ]; then
	#	echo "WaterHeater ON ($CmdRelayOn) and wait $nowheatingtime Minutes"
	#fi
	
	sleepbeforeheating=$(returnsleeptime $HCEndTime $MCEStartScriptTime $nowheatingtime)
        if [ "$debugmode" = "1" ]; then
                echo "Sleep $sleepbeforeheating Minutes, WaterHeater ON ($CmdRelayOn) and wait $nowheatingtime Minutes"
        fi
	if [ "$debugmode" = "0" ]; then
        	sleep $sleepbeforeheating$suffixtosleep
	fi
	#Before Update 4
	#if [ $($scriptdir/MCEsetrelay.sh $CmdRelayOn) = "OK" ];then
	if [ $($CmdRelayOn) = "OK" ];then
		if [ "$debugmode" = "1" ]; then
			echo "WaterHeater Set to [High]"
		fi
	else
		if [ "$debugmode" = "1" ]; then
			echo "WaterHeater NOT Set to [High]"
		fi
		nowheatingtime=0
		errorcode=2
        fi
	sleep $nowheatingtime$suffixtosleep
	if [ "$debugmode" = "1" ]; then
		echo "WaterHeater OFF ($CmdRelayOff)"
	fi
	#Before Update 4
	#if [ $($scriptdir/MCEsetrelay.sh $CmdRelayOff) = "OK" ];then
	if [ $($CmdRelayOff) = "OK" ];then
		if [ "$debugmode" = "1" ]; then
			echo "WaterHeater Set to [low]"
		fi
	else
		if [ "$debugmode" = "1" ]; then
			echo "WaterHeater NOT Set to [low]"
		fi
		errorcode=2
	fi
	LastDayConso=$hotwaterusage
	LastDaysConsoWithoutHeating=0
	NumberDaysWithoutHeating=0
	;;
esac

if [ "$debugmode" = "1" ]; then
	echo "SetMCEInfo $LastDayConso $LastDaysConsoWithoutHeating $NumberDaysWithoutHeating $nowwatthour"
fi
if [ "$nowheatingtime" = "0" ];then
	nowwatthour=0
else
	nowwatthour=$((WaterHeaterPower*nowheatingtime/60))
	nowwatthour=${nowwatthour/.*}
fi
if [ "$(php5 -f $scriptdir/SetMCEinfo.php 0 $LastDayConso $LastDaysConsoWithoutHeating $NumberDaysWithoutHeating $nowwatthour)" = "1" ]; then
        if [ "$debugmode" = "1" ]; then
                echo "Error SetMCEInfo"
        fi
        errorcode=1
fi

if [ "$(php5 -f $scriptdir/SetMCEcoldhotwater.php 0 $coldwaterusage $LastDayConso)" = "1" ]; then
        if [ "$debugmode" = "1" ]; then
                echo "Error SetMCEcoldhotwater"
        fi
        errorcode=45
fi
if [ "$graphgen" = "1" ]; then
	if [ "$(php5 -f $scriptdir/MCEcreateGraph.php)" = "1" ]; then
	        	if [ "$debugmode" = "1" ]; then
	        	        echo "Error SetMCEcreateGraph"
	        	fi
	        	errorcode=46
	fi
fi


echo "$(date +'%D %T'): Mode: $WaterHeaterMode ColdWaterUsage: $coldwaterusage HotWaterUsage: $LastDayConso TotalWaterUsageWithoutHeating: $LastDaysConsoWithoutHeating NumberDaysWithoutHeating: $NumberDaysWithoutHeating HeatingTime: $nowheatingtime PowerUsage: $nowwatthour (ErrorCode: $errorcode)" >> $logfile
#Send XPL Messages

if [[ "$debugmode" = "0" ]] && [[ "$xplmessageon" = "1" ]]; then
	sudo python $pathtosendxpl/send.py xpl-trig sensor.basic "device=coldwaterusage,current=$coldwaterusage,type=counter"
	sudo python $pathtosendxpl/send.py xpl-trig sensor.basic "device=hotwaterusage,current=$LastDayConso,type=counter"
	sudo python $pathtosendxpl/send.py xpl-trig sensor.basic "device=heatingtime,current=$nowheatingtime,type=count"
fi
