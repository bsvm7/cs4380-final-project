#!/bin/bash

#	Delete the JSON parser
if [ -f /usr/bin/jq ]; then
	echo "Found the jq file and deleting"
	sudo rm -f /usr/bin/jq
fi

