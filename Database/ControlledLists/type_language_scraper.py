import requests
from bs4 import BeautifulSoup as BS

BASE = "(%s, %s),\n"
def scrape_iso_language():

	r = requests.get("http://www.loc.gov/standards/iso639-2/php/code_list.php")
	soup = BS(r.content, "html.parser")
	table = soup.find("table").table
	trs = table.find_all("tr")
	file = open("type_language_controlled_list-data.txt", "w")
	file.write("(language VARCHAR(3), language_full VARCHAR(63)\n")
	for tr in trs[1:]:
		tds = tr.find_all('td')
		_6392, language = tds[0].text.strip(), tds[2].text.strip()
		_6392 = _6392[:3]
		file.write(BASE % (_6392, language))
	file.close()


if __name__ == '__main__':
	
	scrape_iso_language()