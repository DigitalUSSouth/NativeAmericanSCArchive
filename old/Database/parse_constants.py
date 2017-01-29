import ast

file = []
index_of_file = 0

def parse_constants_start():
	global file
	global index_of_file

	#INITIALIZE ALL LISTS
	TYPE_DIGITAL = []
	TYPE_CONTENT = []
	ROLE = []
	FILE_FORMAT = []
	GENRE = []
	LANGUAGE = []

	header_names = []#init
	#this is the names and order that the lists of constants come in

	with open("constants.py", "r") as f:

		#read file into list
		file = f.readlines()
		
		#get index where header names start
		header_names_start = 0 #init
		for enum, line in enumerate(file):
			if("###" in line):
				header_names_start = enum + 1
		print("\nheader_names_start is " + str(header_names_start))#debug
		
		index_of_file= header_names_start
		while(file[index_of_file].strip() != ""):
			header_names.append(file[index_of_file])
			header_names[-1] = header_names[-1][1:-1]
			index_of_file += 1
		print("\nOrder of constants/headers is:")#debug
		print(header_names)#debug

		#now we know what order the constants are being read in at, defined under header_names

		for header in header_names:
			if "TYPE_DIGITAL" in header:
				parse_TYPE_DIGITAL()
			elif "TYPE_CONTENT" in header:
				parse_TYPE_CONTENT()
			elif "ROLE" in header:
				parse_ROLE()
			elif "FILE_FORMAT" in header:
				parse_FILE_FORMAT()
			elif "GENRE" in header:
				parse_GENRE()
			elif "LANGUAGE" in header:
				parse_LANGUAGE()

		#now write the modified list called 'file'
		write_new_constants_py()


#we'll modify the Type Digital part of list called 'file' and wait to write it until EVERYTHING IS PARSED
def parse_TYPE_DIGITAL():
	global file
	global index_of_file

	index_of_file = navigateTo("TYPE_DIGITAL", file, index_of_file)
	file = addTabs(file, index_of_file)
	file = addCommas(file, index_of_file)
	file = changeToTuples(file, index_of_file)

	print("TYPE DIGITAL parsed")


#we'll modify the Type Content part of list called 'file' and wait to write it until EVERYTHING IS PARSED
def parse_TYPE_CONTENT():
	global file
	global index_of_file

	index_of_file = navigateTo("TYPE_CONTENT", file, index_of_file)
	file = addTabs(file, index_of_file)
	file = addCommas(file, index_of_file)
	file = changeToTuples(file, index_of_file)
	
	print("TYPE CONTENT parsed")


#we'll modify the Role part of list called 'file' and wait to write it until EVERYTHING IS PARSED
def parse_ROLE():
	global file
	global index_of_file

	index_of_file = navigateTo("ROLE", file, index_of_file)
	file = addTabs(file, index_of_file)
	file = addCommas(file, index_of_file)
	file = changeToTuples(file, index_of_file)
	
	print("ROLE parsed")


#we'll modify the File Format part of list called 'file' and wait to write it until EVERYTHING IS PARSED
def parse_FILE_FORMAT():
	global file
	global index_of_file

	index_of_file = navigateTo("FILE_FORMAT", file, index_of_file)
	file = addTabs(file, index_of_file)
	file = addCommas(file, index_of_file)
	file = changeToTuples(file, index_of_file)
	
	print("FILE FORMAT parsed")


#we'll modify the Genre part of list called 'file' and wait to write it until EVERYTHING IS PARSED
def parse_GENRE():
	global file
	global index_of_file

	index_of_file = navigateTo("GENRE", file, index_of_file)
	file = addTabs(file, index_of_file)
	file = addCommas(file, index_of_file)
	file = changeToTuples(file, index_of_file)
	
	print("GENRE parsed")


#we'll modify the Language part of list called 'file' and wait to write it until EVERYTHING IS PARSED
def parse_LANGUAGE():
	global file
	global index_of_file

	index_of_file = navigateTo("LANGUAGE", file, index_of_file)
	file = addTabs(file, index_of_file)
	file = addCommas(file, index_of_file)
	file = changeToTuples(file, index_of_file)

	print("LANGUAGE parsed")


def write_new_constants_py():
	global file
	global index_of_file
	
	with open("constants_new.py", "w") as new_file:
		for line in file:
			new_file.write(line)
	print("new file printed")


def navigateTo(header, file, index_of_file):
	#Navigate to beginning of header
	while header not in file[index_of_file]:
		index_of_file += 1
	index_of_file += 1
	return index_of_file


def addTabs(file, index_of_file):
	#cycle through lines
	while(file[index_of_file] != "]\n"):
		if("\t" not in file[index_of_file]):
			file[index_of_file] = "\t" + file[index_of_file]
		index_of_file += 1
	return file


def addCommas(file, index_of_file):
	while(file[index_of_file] != "]\n"):
		if("']," not in file[index_of_file]):
			file[index_of_file] = file[index_of_file][:-1] + ",\n"
		index_of_file += 1
	return file


def changeToTuples(file, index_of_file):
	while(file[index_of_file] != "]\n"):
		file[index_of_file] = file[index_of_file].replace("['", "('", 1)
		file[index_of_file] = file[index_of_file].replace("']", "')", 1)
		index_of_file += 1
	return file


#RUN SCRIPT
parse_constants_start()