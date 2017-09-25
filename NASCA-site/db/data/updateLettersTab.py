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
  tribes = {}
  #letterId = 1
  for items in data['data']:
    letter = {
      'thumb': getImgUrl(items[0]['pointer'],'thumbnail'),
      'description': items[0]['descri'],
      'pages': []
    }
    letterTribe = items[0]['tribe'].strip()
    for item in items:
      page = {
        'title': item['title'],
        'transcript': item['transc'],
        'image': getImgUrl(item['pointer'],'large')
      }
      letter['pages'].append(page)
    if letterTribe in tribes:
      letterId = len(tribes[letterTribe]['letters'])+1
      letter['id'] = letterId
      tribes[letterTribe]['letters'][letterId] = letter
    else:
      tribes[letterTribe] = {
        'tribe': letterTribe,
        'letters': {},
        'logo':'',
        'href': urllib.parse.quote_plus(letterTribe)
      }
  
  with open("letters/tabs.json","w") as outfile:
        docFile = json.dumps(tribes,outfile,ensure_ascii=False,indent=4, sort_keys=False)
        outfile.write(docFile)
        outfile.close
      #pprint(page)

def getImgUrl(pointer,size):
  return site_root+'/db/data/letters/'+str(pointer)+'_'+size+'.jpg'


main()