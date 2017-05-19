#!/bin/bash
find uncombined/ -type d|while read dname; do
	if [ ! $dname = "uncombined/" ]; then
		echo
		echo "Combining: $dname"
		fname=$(sed "s/^uncombined\///" <<< $dname)
		if [ $(find $dname -type f -name *_??_??_64kbs.mp3 | wc -l) == 0 ];then
			fname=$(sed -r "s/^(.*)$/\1_64kbs.mp3/" <<< $fname)
			inputquery="concat:"
			for fin in $(ls $dname/ | sort -V); do
				inputquery="$inputquery$dname/$fin|"
			done
			inputquery=$(sed -r "s/^(.*).$/\1/" <<< $inputquery)
			#combine * as $fname
			#mp3wrap $fname $dname/* 2>/dev/null
			ffmpeg -i "$inputquery" -acodec copy $fname </dev/null
			#echo "input query is $inputquery"
			echo "Created: $fname"
		else
			for i in {1..4}; do
				if [ $(find $dname -type f -name *_$i*_??_64kbs.mp3 | wc -l) == 0 ];then
					echo "Tape $i doesn't exist"
				else
					fnametape=$(sed -r "s/^(.*)$/\1_Tape${i}_64kbs.mp3/" <<< $fname)
					# combine as $fname
					#mp3wrap $fname2 $dname/*_$i*_??_64kbs.mp3  2>/dev/null
					inputquery="concat:"
					for fin in $(ls $dname/*_$i?_??_64kbs.mp3 | sort -V); do
						inputquery="$inputquery$fin|"
					done
					inputquery=$(sed -r "s/^(.*).$/\1/" <<< $inputquery)
					#combine * as $fname
					#mp3wrap $fname $dname/* 2>/dev/null
					ffmpeg -i "$inputquery" -acodec copy $fnametape </dev/null
					#echo "input query is $inputquery"
					echo "Created: $fnametape"
				fi
			done
		fi
	fi
done
#rename 's/_MP3WRAP\.mp3$/.mp3/' *.mp3