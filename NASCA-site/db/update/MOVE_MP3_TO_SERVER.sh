#!/bin/bash
PORT=$1
ADDR=$2
WHO=$3
REL="../data/interviews/compressed/"
FILES=("Hazel-Thornton Helen-Jeffcoat John-Barry-Chavis Ms-Williams") #todo:move two peedee recordings as well
PRE="Beaver-Creek_"
SUF="_64kbs_cropped.mp3"
for f in "${FILES[@]}"; do
	FNAME="$REL$PRE$f$SUF"
	echo "filename is $FNAME"
	echo "port is $PORT"
	echo "user is $WHO"
	echo "ip is $ADDR"
	scp -P $PORT $FNAME $WHO@$ADDR:/var/www/html/nasca-git/NASCA-site/db/data/interviews/compressed/
done
