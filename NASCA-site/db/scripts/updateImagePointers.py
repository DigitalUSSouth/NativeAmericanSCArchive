# Updates imagePointers.json in db folder
# imagePointers is just a json list of cdm pointers to every image in contentdm

#IMPORT PACKAGES
import urllib.request, json, codecs
import xml.etree.ElementTree as ET

def getdbConfiguration():
	global server,port,api_query_base,collection

	cdmconfig = ET.parse('../../api/configuration.xml').getroot()
	#for el in cdmconfig:
	#	print(el.tag)
	#exit()
	cdmconfig = cdmconfig.find('databases').find('cdm')
	server = cdmconfig.find('server').text
	port = cdmconfig.find('port').text
	api_query_base = cdmconfig.find('api_query_base').text
	collection = cdmconfig.find('collection').text


def main():
	#do a general search for every file in contentdm
	#http://digital.tcl.sc.edu:81/dmwebservices/index.php?q=dmQuery/nasca/CISOSEARCHALL/field/nosort/1024/0/0/0/0/0/0/0/json
	#return from that address as json
	#simplify json to {id,pointer,type,name}
	#output as db/data/images/imagePointers.json
	getdbConfiguration()
	url = 'http://' + server + ':' + port + api_query_base + 'dmQuery' + collection +\
		'/CISOSEARCHALL/field/nosort/1024/0/0/0/0/0/0/0/json'
	reader = codecs.getreader("utf-8")
	urlrequest = urllib.request.urlopen(url)
	data = json.load(reader(urlrequest))
	#print(str(data['pager']['start']) + ", " + str(data['pager']['total']))
	#print(str(data['records'][0]['pointer']) + " should be 557")

	totalRecords = data['pager']['total']
	recordsList = []
	for i in range(0,totalRecords):
		recordsList.append([data['records'][i]['pointer'],\
							data['records'][i]['filetype'],\
							data['records'][i]['find']])
	writeJSON(recordsList, totalRecords)


def writeJSON(records, total):
	filename = "../data/images/imagePointers.json"
	with open(filename, 'w') as file:
		file.write("{\"total\":" + str(total) + ",\n")
		file.write("\t\"pointers\":[\n")
		
		for i, rec in enumerate(records):
			file.write(create_pointer_object(i, rec))
			if not i==total-1:
				file.write(",\n")
			else:
				file.write("\n")

		file.write("]}")


def create_pointer_object(_id, _triple):
	returnStr = "\t{\n\t\t\"id\":" + str(_id) +\
						",\"pointer\":" + str(_triple[0]) +\
						",\"type\":\"" + _triple[1] +\
						"\",\"name\":\"" + _triple[2] + "\"\n\t}"

	return returnStr


#RUN SCRIPT
server,port,api_query_base,collection = "","","",""
main()