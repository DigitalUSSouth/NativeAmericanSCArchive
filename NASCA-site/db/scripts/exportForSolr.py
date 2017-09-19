# scrip to create one big json doc containing
# objects to be used by our Solr backend

import json
import re
from pprint import pprint
import os.path

archive = "Native American South Carolina Archive"
contributing_institution = "University of South Carolina"
site_root = "https://test.nativesouthcarolina.org"

def main():
    print("***Starting Solr export process")
    docs = []
    docs.extend(interviews())
    with open("../data/solrDocs.json","w") as outfile:
        docFile = json.dumps(docs,outfile,ensure_ascii=False,indent=4, sort_keys=True)
        outfile.write(docFile)
        outfile.close
    timelines()


def interviews():
    with open("../data/interviews/tabs.json") as dataFile:
        interviews = json.load(dataFile)
        dataFile.close
    print ("****Exporting interviews:")
    docs = []
    for interview in interviews:
        doc = {
            'archive': archive,
            'contributing_institution': contributing_institution,
            'title': interview['tribe'],
            'type_content': "Text",
            'type_digital': "Text",
            'url': site_root+'/interviews/'+interview['href'],
            'id': site_root+'/interviews/'+interview['href'],
            'description': interview['description'],
            'thumbnail_url': site_root+interview['logo'],
            'geolocation_human': "South Carolina",
            'file_format': 'text/html'
        }
        pprint(doc['title'])
        docs.append(doc)
        print ("*****Interview transcripts for: "+interview['tribe'])
        docs.extend(interviewTranscripts(interview))
    return docs

def interviewTranscripts(interview):
    transcripts = interview['interviews']
    docs = []
    for datafile,shortTitle in transcripts.items():
        print (datafile+'---'+shortTitle)
        with open ("../data/interviews/transcripts/json/minified/"+datafile) as dataFile:
            transcript = json.load(dataFile)
            dataFile.close()
        match = re.match('.+?(?=-minified\.json)',datafile)
        if match:
            href = (match.group(0)) #get href value from filename
        
        #generate the full text from text bits in the interview transcript
        fulltext = ""
        for bit in transcript['text']:
            textbit = bit['text_bit']
            fulltext = fulltext+' '+ textbit
        doc = {
            'archive': archive,
            'contributing_institution': contributing_institution,
            'title': transcript['title'],
            'type_content': "Sound",
            'type_digital': "Sound",
            'url': site_root+'/interviews/'+interview['href']+'/'+href,
            'id': site_root+'/interviews/'+interview['href']+'/'+href,
            'description': transcript['description'],
            'thumbnail_url': site_root+interview['logo'],
            'geolocation_human': "South Carolina",
            'file_format': 'audio/mpeg',
            'is_part_of': site_root+'/interviews/'+interview['href'],
            'full_text': fulltext
        }
        docs.append(doc)
    return docs

def timelines():
    print ("****Exporting interviews:")
    docs = []
    fileCounter = 1
    currentFile = "../../html/ht/data/data"+str(fileCounter)+".json"
    while (os.path.isfile(currentFile)):
        #print (currentFile)
        with open(currentFile) as dataFile:
            timeline = json.load(dataFile)
            dataFile.close
        description = ""
        for event in timeline['events']:
            description += ' '+event['media']['caption']
            description += ' '+event['start_date']['year']
            description += ' '+event['text']['headline']
            description += ' '+event['text']['text']
        doc = {
            'archive': archive,
            'contributing_institution': contributing_institution,
            'title': timeline['title']['text']['headline']+' '+timeline['title']['text']['text'],
            'type_content': "Text",
            'type_digital': "Text",
            'url': site_root+'/timeline/',
            'id': site_root+'/timeline/',
            'description': description,
            #'thumbnail_url': site_root+interview['logo'],
            'geolocation_human': "South Carolina",
            'file_format': 'text/html'
        }
        print(doc['title'])
        docs.append(doc)
        fileCounter = fileCounter+1
        currentFile = "../../html/ht/data/data"+str(fileCounter)+".json"
    return docs
"""
req:
    archive
    contributing_institution
    url
    title
    type_content
    type_digital
    roles
    year_begin
    month_begin
    day_begin
    year_end
    month_end
    day_end
    years[]
    date_digital (string)
    geolocation_human
    file_format
opt:
    alternative_title
    is_part_of
    thumbnail_url
    description
    genre
    full_text
    type_physical
    date_original_human
    date_digital_human
    geolocation_machine
    shelfmark
    subject_heading[]
    extent
    copyright_holder
    use_permissions
    language
    notes
"""

main()