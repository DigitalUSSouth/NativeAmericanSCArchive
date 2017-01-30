#IMPORT PACKAGES
import sys
from pathlib import Path
import docx


def verify_argument():
	filename = sys.argv[1]
	PATH = Path("./" + filename)

	if not filename:						# if file argument isn't passed
		sys.exit("SCRIPT REQUIRES .docx FILENAME AS ARGUMENT")

	if not PATH.is_file():					# if the file passed doesn't exist
		sys.exit("THE ARGUMENT(" + filename + ") PASSED IS NOT AN EXISTING FILE")

	if filename[-5:] != ".docx":		# if file is not a Word document
		sys.exit("THE ARGUMENT(" + filename + ") PASSED IS NOT A .docx WORD DOCUMENT")
	
	# pass document text into 'data' using 'docx' module
	document = docx.Document(filename)
	data = []
	for paragraph in document.paragraphs:
		data.append(paragraph.text)
		#print(repr(paragraph.text))	#debug
	
	# pass data onto transcription_to_JSON
	transcription_to_JSON(data)


def transcription_to_JSON(_data):
	jsTitle = "Default JSON Title"
	jsDescription = "Default JSON Description"
	jsAudioFile = "Default JSON Audio File.mp3"
	header = [jsTitle, jsDescription, jsAudioFile]

	isInHeader = False

	index = 0
	while not isInHeader:
		if _data[index] is not "":
			isInHeader = True
		index += 1
	index -= 1

	# now we're in the header, if there was any white space or
	# new lines before it

	header[0] = _data[index]
	index += 1
	header[1] = _data[index]
	index += 1
	header[2] = _data[index]

	if((header[0] is "") | (header[2] is "")):
		sys.exit("THERE WAS A PROBLEM PARSING THE HEADER:\n" +
					"One or many of the header requirements look empty.")
	elif(header[2][-4:] != ".mp3"):
		sys.exit("THE AUDIO FILE LISTED (" + header[2] +
					") IN THE TRANSCRIPTION IS NOT .mp3")

	index += 1
	while isInHeader:
		if _data[index] is not "":
			isInHeader = False
		index += 1
	index -= 1
	# 'current' index is the first line of the actual transcription
	
	# reallocate data to only include the transcription so that the first
	# line is at index '0'
	_data = _data[index:]
	# initialize list to hold parsed data from each line, in the form of a
	# list of triples (not tuples). Each triple is a line in the transcription.
	triples = []
	for lineNum, lineContent in enumerate(_data):
		# for a single transcript line OPEN
		
		# replace all triple spaces with a tab
		lineContent = lineContent.replace("   ","\t")
		# split the line into a list of elements by the tabs
		triple = lineContent.split("\t")
		# strip heading and leading whitespace from elements
		for i, element in enumerate(triple):
			triple[i] = element.strip()
		# filter out any elements that are empty
		triple = list(filter(bool, triple))
		# verify that there are exactly 3 elements left
		if len(triple) is not 3:
			transcription_error_call(lineNum, lineContent,
				"There aren't three separate elements. (" + str(len(triple)) + ")")
		
		# now we make sure each entry of triple is consistently formatted
		
		# FOR SPEAKER
		# remove colon if there is one
		triple[0] = triple[0].strip(":")
		triple[0] = triple[0].strip()
		# FOR TIMECODE
		tempTC = triple[1]
		# remove brackets
		tempTC = tempTC.strip("[")
		tempTC = tempTC.strip("]")
		tempTC = tempTC.strip()
		# replace delimeters
		hour = tempTC[:2]
		minute = tempTC[3:5]
		second = tempTC[6:8]
		millis = tempTC[9:]
		tempTC = hour + ":" + minute + ":" + second + "." + millis
		triple[1] = tempTC
		# FOR DIALOGUE/TEXT
		# add a space to the end of the text line
		triple[2] += " "

		# append the fully parsed and formatted triple to triples
		triples.append(triple)

		# for a single transcript line CLOSE

	##print(repr(triples))	#debug
	write_JSON(header, triples)
	

def write_JSON(_header, _triples):
	filename = sys.argv[1]
	filename = filename[:-5] + ".json"
	slashIndex = filename.rfind("/")
	filename = filename[(slashIndex+1):]

	##print(filename)		#debug

	with open(filename, 'w') as file:
		file.write("{\n")
		file.write(json_formatted_line(1, "title", _header[0]))
		file.write(json_formatted_line(1, "description", _header[1]))
		file.write(json_formatted_line(1, "audio_file", _header[2]))
		file.write("\t\"text\":\n\t[\n")
		
		for i in range(0,len(_triples)):
			file.write(json_formatted_text_object(i, _triples[i]))

		file.write("\t]\n}")


def json_formatted_text_object(_id, _triple):
	returnStr = ""
	returnStr += "\t\t{\n"
	returnStr += json_formatted_line(3, "id", _id)
	returnStr += json_formatted_line(3, "speaker", _triple[0])
	returnStr += json_formatted_line(3, "text_bit", _triple[2])
	returnStr += json_formatted_line(3, "timecode", _triple[1])
	returnStr += "\t\t},\n"
	return returnStr


def json_formatted_line(_tabs, _type, _value):
	returnStr = ""
	for i in range(0,_tabs):
		returnStr += "\t"
	returnStr += "\"" + _type + "\": \"" + str(_value) + "\",\n"
	return returnStr

def transcription_error_call(_lineNum):
	sys.exit("Error at line " + str(_lineNum) + " of transcription\n")


def transcription_error_call(_lineNum, _lineContent, extra):
	sys.exit("Error at line " + str(_lineNum) + " of transcription: " + _lineContent + "\n" + extra + "\n")

#RUN SCRIPT
verify_argument()
