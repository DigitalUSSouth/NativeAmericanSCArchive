#type_genre_scraper.py
#Authors: Matthew Jendrasiak (jendrasi@email.sc.edu)
#7/29/2016
#Simple web scraper to pull genre standards from collex wiki. Formats output as a list of lists.

import requests
from bs4 import BeautifulSoup as BS

#format output of each individual language list
BASE = "[(%s),(%s),(%s)],\n"
def scrape_genre_content():

	req = requests.get("http://wiki.collex.org/index.php/Submitting_RDF#.3Ccollex:genre.3E")
	soup = BS(req.content, "html.parser")
	table = (soup.find_all("table"))
	tds = (table[4].find_all("td"))[1:]

	#write to file
	with open("type_genre_controlled_list-data.txt", "w") as file:
		#prep file
		file.write("[genre_id TINYINT(255), genre VARCHAR(31), "+
			"description TINYTEXT]\n[\n")
		#print labels and definitions
		for i, td in enumerate(tds):
		 	file.write(BASE % (i, td.text.strip(), "Description for " + td.text.strip()))
		file.write("]")


if __name__ == '__main__':

	scrape_genre_content()