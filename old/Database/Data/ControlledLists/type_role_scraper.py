#type_role_scraper.py
#Author: Matthew Jendrasiak (jendrasi@email.sc.edu)
#7/29/2016
#Web scraper to pull MARC relator standards from loc.gov. Formats output as a list of lists.

import requests
from bs4 import BeautifulSoup as BS

#format output of each individual role list
BASE = "[(%s),(%s),\n(%s)],\n"
def scrape_marc_roles():
	
	r = requests.get("http://www.loc.gov/marc/relators/relaterm.html")
	soup = BS(r.content, "html.parser")
	content = (soup.find_all("div"))[3]
	relators = (content.find_all("dl"))[-1]
	dts = relators.find_all("dt")
	dds = relators.find_all("dd") #SAME AMOUNT OF dts AND dds, dts[1] title is adjacent to dts[2] description
	
	index = 0 #initializes index and range for loop and uses a while since the range is dynamic
	_range = len(dts) #sets initial range to amount of dts
	while index < _range:
		while "unauthorized" in dts[index].find("span").get("class"): #(class = "unauthorized") attributes are not relevant
			dts.pop(index) #so we pop them
			dds.pop(index) #AND their adjacent descriptions from each list
			_range -= 1 #change the range of loop since list length has changed
		index += 1 #increment the index and repeat the while

	#format each description so that extra text from 'div' tag isn't there
	for dd in dds:
		while dd.find("div") != None:
			dd.find("div").extract()

	#write output
	file = open("type_role_controlled_list-data.txt", "w")
	file.write("[role VARCHAR(3), role_full VARCHAR(31), description TEXT(1023)]\n[\n")
	no_brackets_identity = str.maketrans("", "", "[]") #identity replaces brackets with nothing
	for j in range(0,len(dts)):
		dt = dts[j].find_all("span")
		file.write(BASE % (dt[1].text.translate(no_brackets_identity).strip(),
			dt[0].text.strip(),
			dds[j].text.strip()))
	file.write("]")
	file.close()


if __name__ == '__main__':
	
	scrape_marc_roles()

#
#