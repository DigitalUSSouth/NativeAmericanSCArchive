#!/bin/sh
clear
echo "Minifying all JSON oral history transcriptions,\nto new files (non-destructive)."
for f in ../data/interviews/transcripts/json/readable/*.json;
do
	echo "Converting: $f";
	tr -d "\n\t" < "$f" > "${f%.json}-minified.json";
done
mv ../data/interviews/transcripts/json/readable/*-minified.json ../data/interviews/transcripts/json/minified