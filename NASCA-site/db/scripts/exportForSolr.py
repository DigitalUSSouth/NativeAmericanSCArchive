# scrip to create one big json doc containing
# objects to be used by our Solr backend

import json
import re
from pprint import pprint
import os.path

archive = "Native American South Carolina Archive"
contributing_institution = "University of South Carolina"
site_root = "https://www.nativesouthcarolina.org"

def main():
    print("***Starting Solr export process")
    docs = []
    docs.extend(interviews())
    docs.extend(timelines())
    docs.extend(tribes())
    docs.extend(letters())
    docs.extend(images())
    videos()
    with open("../data/solrDocs.json","w") as outfile:
        docFile = json.dumps(docs,outfile,ensure_ascii=False,indent=4, sort_keys=True)
        outfile.write(docFile)
        outfile.close
    

def interviews():
    with open("../data/interviews/tabs.json") as dataFile:
        interviews = json.load(dataFile)
        dataFile.close
    print ("****Exporting interviews:")
    docs = []
    for interview in interviews:
        """doc = {
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
        docs.append(doc)"""
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
    print ("****Exporting timelines:")
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
            'url': site_root+'/timeline/'+str(fileCounter),
            'id': site_root+'/timeline/'+str(fileCounter),
            'description': '',#description,
            #'thumbnail_url': site_root+interview['logo'],
            'geolocation_human': "South Carolina",
            'file_format': 'text/html',
            'full_text': description
        }
        print(doc['title']+'-'+doc['url'])
        docs.append(doc)
        fileCounter = fileCounter+1
        currentFile = "../../html/ht/data/data"+str(fileCounter)+".json"
    return docs

def tribes():
    print ("****Exporting tribes:")
    docs = []
    with open("../data/tribes/data.json") as dataFile:
        data = json.load(dataFile)
        dataFile.close
    imgDir = data['directories']['image_directory']
    descDir = data['directories']['description_directory']
    tribes = data['data']
    counter = 1
    for tribe in tribes:
        #pprint (tribe)
        description = ""
        path = "../.."+descDir+'/'+tribe['description']
        if (os.path.isfile(path)):
            with open(path,'r') as descFile:
                description = descFile.read()
        #print(description)
        doc = {
            'archive': archive,
            'contributing_institution': contributing_institution,
            'title': tribe['title'],
            'type_content': "Text",
            'type_digital': "Text",
            'url': site_root+'/tribes/#Tribes-'+str(counter),
            'id': site_root+'/tribes/#Tribes'+str(counter),
            'description': '',
            'thumbnail_url': site_root+imgDir+'/'+tribe['logo'],
            'geolocation_human': "South Carolina",
            'file_format': 'text/html',
            'full_text': description
        }
        pprint(doc['title'])
        docs.append(doc)
        counter = counter + 1
    return docs

def images():
    print ("****Exporting Images:")
    docs = []
    with open("../data/images/data.json") as dataFile:
        data = json.load(dataFile)
        dataFile.close
    for image in data['data']:
        path = "../data/images/"+str(image['pointer'])+"_thumbnail.jpg"
        if (os.path.isfile(path)):
            thumbnail = site_root+"/db/data/images/"+str(image['pointer'])+"_thumbnail.jpg"
        else:
            thumbnail = ""
        #print(description)
        loc = "South Carolina" if image['geogra']=="" else image['geogra']
        doc = {
            'archive': archive,
            'contributing_institution': image['publis'],
            'title': image['title'],
            'type_content': "Image",
            'type_digital': "Image",
            'url': site_root+'/images/'+str(image['pointer']),
            'id': site_root+'/images/'+str(image['pointer']),
            'description': '',
            'thumbnail_url': thumbnail,
            'geolocation_human': loc,
            'file_format': 'image/jpeg',
            'full_text': image['descri']
        }
        pprint(doc['title'])
        docs.append(doc)
    return docs

def videos():
    print ("****Exporting Images:")
    docs = []
    with open("../data/video/data.json") as dataFile:
        data = json.load(dataFile)
        dataFile.close
    urls = data['urls']
    count = data['count']
    counter = 1
    for video in data['data']:
        if counter>count:
            break
        thumbnail = urls['video_prefix']+video['key']+urls['thumbnail_suffix']
        #print(description)
        doc = {
            'archive': archive,
            'contributing_institution': contributing_institution,
            'title': video['title'],
            'type_content': "Image",
            'type_digital': "Image",
            'url': site_root+'/video/#Videos-'+str(counter),
            'id': site_root+'/video/#Videos-'+str(counter),
            'description': video['description'],
            'thumbnail_url': thumbnail,
            'geolocation_human': "South Carolina",
            'file_format': 'video/x-youtube'
        }
        pprint(doc)
        docs.append(doc)
        counter = counter + 1
    return docs

def letters():
    print ("****Exporting letters:")
    docs = []
    with open("../data/letters/data.json") as dataFile:
        data = json.load(dataFile)
        dataFile.close
    data = data['data']
    counter = 1
    years = {}
    for letter in data:
        pageCounter = 1
        letterInit = True
        for page in letter:
            letterYear = 0
            match = re.search('[0-9]{4}',page['date']) #match 4 digit year
            if match:
                letterYear = match.group(0)
            if letterYear==0:
                continue
            if letterInit:
                if letterYear in years:
                    years[letterYear] = years[letterYear] + 1
                else:
                    years[letterYear] = 1
                letterInit = False
            if page['title'] == "":
                continue
            imgPath1 = "../data/letters/"+str(page['pointer'])+"_large.jpg"
            imgPath2 = "../data/letters/"+str(page['pointer'])+"_thumbnail.jpg"
            if not(os.path.isfile(imgPath1)) or not(os.path.isfile(imgPath2)):
                print (imgPath1)
                continue
            doc = {
                'archive': archive,
                'contributing_institution': contributing_institution,
                'title': page['title'],
                'type_content': "Text",
                'type_digital': "Text",
                'url': site_root+'/letters/'+str(letterYear)+'/'+str(years[letterYear])+'#page'+str(pageCounter),
                'id': site_root+'/letters/'+str(letterYear)+'/'+str(years[letterYear])+'#page'+str(pageCounter),
                'thumbnail_url': site_root+"/db/data/letters/"+str(page['pointer'])+"_thumbnail.jpg",
                'description': page['descri'],
                'geolocation_human': "South Carolina",
                'file_format': 'text/html',
                'full_text': page['transc']
            }
            pprint(doc['url'])
            docs.append(doc)
            pageCounter = pageCounter + 1
        counter = counter + 1
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