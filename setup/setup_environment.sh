#!/bin/bash

# This script downloads the proper tools to setup the environment

# First get a JSON parser bash
clear

echo "Starting environment setup"

sudo wget http://stedolan.github.io/jq/download/linux64/jq

sudo chmod +x ./jq

sudo mv jq /usr/bin

if [ ! -f /usr/bin/jq ]; then
	echo "There was an issue installing the JSON parser..."
	echo "Quitting"
	return
fi

# Pull the user names out of the config file
users_length=$(cat ./config.json | jq '.users | length')

users_first_names=();
users_last_names=();
users_usernames=();

# Gather first names
counter=0

while [ $counter -lt $users_length ]; do
	curr_name=$(jq -r ".users[$counter].first_name" config.json)
	
	if [ $counter -eq 0 ]; then
		users_first_names=( "$curr_name" );
	else
		users_first_names=( "${users_first_names[@]}", "$curr_name");
	fi
	
	let counter=counter+1;
done

echo ${users_first_names[@]}

let counter=0;

while [ $counter -lt $users_length ]; do
	curr_name=$(jq -r ".users[$counter].last_name" config.json)
	
	if [ $counter -eq 0 ]; then
		users_last_names=( "$curr_name" );
	else
		users_last_names=( "${users_last_names[@]}", "$curr_name");
	fi
	
	let counter=counter+1;
done

echo ${users_last_names[@]}

let counter=0;

while [ $counter -lt $users_length ]; do
	curr_name=$(jq -r ".users[$counter].username" config.json)
	
	if [ $counter -eq 0 ]; then
		users_usernames=( "$curr_name" );
	else
		users_usernames=( "${users_usernames[@]}", "$curr_name");
	fi
	
	let counter=counter+1;
done

echo ${users_usernames[@]}


ip_address="http://$(jq -r ".ip_address" config.json)/"

echo $ip_address;

group_name=$(jq -r ".group_name" config.json)

echo $group_name

root_folder_name=$(jq -r ".root_folder_name" config.json)

echo $root_folder_name


#	Create the capstone group

sudo groupadd -g 10000 $group_name



