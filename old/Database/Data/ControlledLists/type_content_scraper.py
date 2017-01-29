#type_content_scraper.py
#Authors: Matthew Jendrasiak (jendrasi@email.sc.edu)
#7/29/2016
#Simple web scraper to pull type standards from Dublin Core. Formats output as a list of lists.

import requests
from bs4 import BeautifulSoup as BS

#format output of each individual language list
BASE = "[(%s),(%s),(%s)],\n"
def scrape_dcmi_content():
	req = requests.get("http://dublincore.org/documents/2008/01/14/dcmi-type-vocabulary/")
	soup = BS(req.content, "html.parser")
	table = (soup.find_all("table"))[4]
	trs = table.find_all("tr")

	labels = []
	definitions = []

	#loop through every table row (td)
	for tr in trs[1:]:
		col = tr.find_all("td")
		if len(col) > 0:
			if "Label:" in col[0].text:
				labels.append(col[1].text)
			elif "Definition:" in col[0].text:
				definitions.append(col[1].text)
	
	#write to file
	with open("type_content_controlled_list-data.txt", "w") as file:
		#prep file
		file.write("[type_content_id TINYINT(255), type_content VARCHAR(31), "+
			"description TINYTEXT]\n[\n")
		#print labels and definitions
		for i, _label in enumerate(labels):
		 	file.write(BASE % (i, _label, definitions[i]))
		file.write("]")


if __name__ == '__main__':
	scrape_dcmi_content()