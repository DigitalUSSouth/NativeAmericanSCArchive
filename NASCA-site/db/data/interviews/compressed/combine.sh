#!/bin/bash
find uncombined/ -type d|while read dname; do
	if [ ! $dname = "uncombined/" ]; then
		echo
		echo "Combining: $dname"
		fname=$(sed "s/^uncombined\///" <<< $dname)
		if [ $(find $dname -type f -name *_??_??_64kbs.mp3 | wc -l) == 0 ];then
			fname=$(sed -r "s/^(.*)$/\1_64kbs.mp3/" <<< $fname)
			#combine * as $fname
			mp3wrap $fname $dname/* 2>/dev/null
			echo "Created: $fname"
		else
			for i in {1..4}; do
				if [ $(find $dname -type f -name *_$i*_??_64kbs.mp3 | wc -l) == 0 ];then
					echo "Tape $i doesn't exist"
				else
					fname2=$(sed -r "s/^(.*)$/\1_Tape${i}_64kbs.mp3/" <<< $fname)
					# combine as $fname
					mp3wrap $fname2 $dname/*_$i*_??_64kbs.mp3  2>/dev/null
					echo "Created: $fname2"
				fi
			done
		fi
	fi
done
rename 's/_MP3WRAP\.mp3$/.mp3/' *.mp3