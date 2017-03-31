#!/bin/sh
clear
echo "Converting all oral history transcriptions [.docx to .json]"
for f in ../data/interviews/transcripts/docx/*.docx;
do
	echo "Converting: $f";
	python3 ../scripts/transcriptionToJSON.py "$f";
done
mv ../data/interviews/transcripts/docx/*.json ../data/interviews/transcripts/json/readable