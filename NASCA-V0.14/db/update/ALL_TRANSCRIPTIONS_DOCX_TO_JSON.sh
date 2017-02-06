#!/bin/sh
clear
echo "Converting all oral history transcriptions [.docx to .json]"
for f in ../data/oralhistory/*.docx;
do
	echo "Converting: $f"
	python3 ../scripts/transcriptionToJSON.py "$f"
done