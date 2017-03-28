#!/bin/bash
find . -type f -regex '.*\.\(ac3\|m4a\|wav\|mp3\)$'|while read fname; do
	f=$(sed 's/^\.\///' <<< $fname)
	fnew=$(sed 's/^/..\/compressed\//; s/\....$/_64kbs.mp3/' <<< $f)
	fdir=$(sed -r 's/(.*)\/.*/\1/' <<< $fnew)
	echo "Converting: $f"
	echo "To: $fnew"
	if [ ! -d "$fdir" ]; then
		echo "Creating $fdir"
		mkdir -p $fdir
	fi
	< /dev/null ffmpeg -loglevel panic -i $f -af "volume=0dB" -acodec libmp3lame -ab 64k $fnew
done