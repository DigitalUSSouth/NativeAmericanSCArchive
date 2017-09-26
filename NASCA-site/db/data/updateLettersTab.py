import json
import re
from pprint import pprint
import os.path
import urllib.parse

site_root = "http://192.168.132.136"

def main():
  with open("letters/data.json") as dataFile:
        data = json.load(dataFile)
        dataFile.close
  print ("****:")
  #pprint (data)
  years = {}
  #letterId = 1
  for items in data['data']:
    letter = {
      'thumb': getImgUrl(items[0]['pointer'],'thumbnail'),
      'description': items[0]['descri'],
      'pages': []
    }

    letterTribe = items[0]['tribe'].strip()
    letterYear = ''
    match = re.search('[0-9]{4}',items[0]['date']) #match 4 digit year
    if match:
      letterYear = match.group(0)
    else:
      pprint(items[0]['date'])
    for item in items:
      page = {
        'title': item['title'],
        'transcript': item['transc'],
        'image': getImgUrl(item['pointer'],'large')
      }
      letter['pages'].append(page)
    letter['tribe'] =  letterTribe,
    if letterYear in years:
      letterId = len(years[letterYear]['letters'])+1
      letter['id'] = letterId
      years[letterYear]['letters'][letterId] = letter
    else:
      pprint (letterYear)
      pprint(items[0]['date'])

      letter['id'] = 1
      years[letterYear] = {
        'letters': {
          1: letter
        },
        'logo':'',
        'href': urllib.parse.quote_plus(letterYear)
      }
  
  with open("letters/tabs.json","w") as outfile:
        docFile = json.dumps(years,outfile,ensure_ascii=False,indent=4, sort_keys=False)
        outfile.write(docFile)
        outfile.close
      #pprint(page)

def getImgUrl(pointer,size):
  return site_root+'/db/data/letters/'+str(pointer)+'_'+size+'.jpg'


main()