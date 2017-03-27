#!/bin/sh
clear
echo "Minifying all JSON oral history transcriptions,\nto new files (non-destructive)."
for f in ../data/interviews/*.json;
do
	echo "Converting: $f";
	tr -d "\n\t" < "$f" > "${f%.json}-minified.json";
done