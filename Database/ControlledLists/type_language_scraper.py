#type_language_scraper.py
#Authors: Nick Brown (ntbrown@email.sc.edu), Matthew Jendrasiak (jendrasi@email.sc.edu)
#7/28/2016
#Simple web scraper to pull ISO 639-2 language standards from loc.gov. Formats output as a list of lists.

import requests
from bs4 import BeautifulSoup as BS

#format output of each individual language list
BASE = "[(%s),(%s)],\n"
def scrape_iso_language():

	r = requests.get("http://www.loc.gov/standards/iso639-2/php/code_list.php")
	soup = BS(r.content, "html.parser")
	table = soup.find("table").table
	trs = table.find_all("tr")
	file = open("type_language_controlled_list-data.txt", "w")
	file.write("[language VARCHAR(3), language_full VARCHAR(63)]\n[\n")

	for tr in trs[1:]: #cycle through table and pull 1st and 3rd column from each row
		tds = tr.find_all('td')
		_6392, language = tds[0].text.strip(), tds[2].text.strip()
		_6392 = _6392[:3] #strip unnecessary stuff from 639-2 code
		file.write(BASE % (_6392, language))
	file.write("]")
	file.close()


if __name__ == '__main__':
	
	scrape_iso_language()