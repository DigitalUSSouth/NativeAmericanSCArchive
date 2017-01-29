# Updates imagePointers.json in db folder
# imagePointers is just a json list of pointers to every image in contentdm

#IMPORT PACKAGES
import urllib.request, json, codecs


def main():
	#do a general search for every file in contentdm
	#http://digital.tcl.sc.edu:81/dmwebservices/index.php?q=dmQuery/nasca/CISOSEARCHALL/field/nosort/1024/0/0/0/0/0/0/0/json
	#return from that address as json
	#simplify json to {id,pointer,type,name}
	#output as db/data/images/imagePointers.json
	url = "http://digital.tcl.sc.edu:81/dmwebservices/index.php?q=dmQuery/nasca/CISOSEARCHALL/field/nosort/1024/0/0/0/0/0/0/0/json"
	reader = codecs.getreader("utf-8")
	response = urllib.request.urlopen(url)
	data = json.load(reader(response))
	for line in data:
		print(data[line])


#RUN SCRIPT
main()