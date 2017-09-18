# scrip to create one big json doc containing
# objects to be used by our Solr backend

import json

from pprint import pprint

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

def interviews():
    with open("../data/interviews/tabs.json") as dataFile:
        interviews = json.load(dataFile)
        dataFile.close
    print ("**Exporting interviews:")
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