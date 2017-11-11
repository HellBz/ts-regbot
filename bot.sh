#!/bin/bash
# Colors
ESC_SEQ="\x1b["
COL_RESET=$ESC_SEQ"39;49;00m"
COL_GREEN=$ESC_SEQ"32;01m"
COL_GOLD=$ESC_SEQ"30;33m"
COL_RED=$ESC_SEQ"31;02m"

clear
if [ $1 = 'stop' ] 
    then 
        pkill -f Reg_Bot
		echo -e "Reg_Bot: $COL_GREEN Bot has been STOPED! $COL_RESET"
    fi

if [ $1 = 'start' ] 
    then 
        screen -A -m -d -S Reg_Bot php regbot.php
		echo -e "Reg_Bot: $COL_GREEN Bot has been STARTED! $COL_RESET"
    fi
