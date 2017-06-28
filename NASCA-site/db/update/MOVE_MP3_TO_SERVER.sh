#!/bin/bash
PORT=$1
ADDR=$2
WHO=$3
REL="../data/interviews/compressed/"
FILES=("Pee-Dee_Clayton-RC-Cummings_64kbs.mp3")
for f in "${FILES[@]}"; do
	FNAME="$REL$f"
	scp -P $PORT $FNAME $WHO@$ADDR:/var/www/html/nasca-git/NASCA-site/db/data/interviews/compressed/
done
