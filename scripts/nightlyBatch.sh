#!/bin/sh

mkdir -p logs

d=$(date +%m-%d-%y_%H:%M)

home=/home1/thewinn2/public_html/omer

# Run script for all users
php  $home/scripts/reminders.php >> "$home/scripts/logs/reminder_$d.log"
