import re
import shutil
import xml.etree.ElementTree as ET

from settings import *

query = sys.argv[1]
department = sys.argv[2]


# for testing purpose
# query = 'Band.pdf'
# department = 'test'


# Convert ASCII type data to string type data
def convertASCIItoStr(stri):
    try:
        stri = str(stri).decode('utf8')
    except UnicodeEncodeError:
        # already unicode
        pass
    return stri


# Adding document to solr core and updating the title, abstract and author names.
def addDocument(directory, filename, core):
    filepath = directory + filename

    p = subprocess.Popen(
        'curl -v --form input=@' + pathToResearchPapersFolder + core + '/' + filename + ' http://cloud.science-miner.com/grobid/processFulltextDocument',
        shell=True, stdout=subprocess.PIPE)
    text, err = p.communicate()

    p.kill()

    # if text is not None:
    #     print text
    if err is not None:
        print err

    tree = ET.ElementTree(ET.fromstring(text))

    child = title = authorNames = abstract = ''

    # title
    for elem in tree.iter():
        if 'titleStmt' in elem.tag:
            child = elem
            break

    for elem in child.iter():
        if 'title' in elem.tag:
            if elem.get('type') == 'main':
                print 'Title: ', elem.text
                title = elem.text

    # abstract
    for elem in tree.iter():
        if 'profileDesc' in elem.tag:
            child = elem
            # print [el.tag for el in child.iter()]
            break

    for elem in child.iter():
        if 'abstract' in elem.tag:
            child = elem
            for elem1 in child.iter():
                if 'p' in elem1.tag:
                    print 'Abstract: ', elem1.text
                    abstract = elem1.text
            break

    # Authors
    for elem in tree.iter():
        if 'fileDesc' in elem.tag:
            child = elem
            break

    for elem1 in child.iter():
        if 'author' in elem1.tag:
            for elem in elem1.iter():
                if 'forename' in elem.tag:
                    if elem.get('type') == 'first':
                        print 'firstName: ', elem.text
                        authorNames = authorNames + elem.text + ' '
                    if elem.get('type') == 'middle':
                        print 'middleName: ', elem.text
                        authorNames = authorNames + elem.text + ' '
                if 'surname' in elem.tag:
                    print 'lastName: ', elem.text
                    authorNames = authorNames + elem.text + '; '
                    print "-----"

    # Converting ASCII to Str and escaping all special charecters
    title = re.escape(convertASCIItoStr(title))
    authorNames = re.escape(convertASCIItoStr(authorNames))
    abstract = re.escape(convertASCIItoStr(abstract))

    run_curl(
        "curl localhost:8983/solr/" + core + "/update?commit=true -H 'Content-type:application/json' --data-binary " + "\"[{'_id':'" + filename + "','title':{'set':'" + title + "'},'author':{'set':'" + authorNames + "'},'abstract':{'set':'" + abstract + "'},'annotation':{'set':'Null'}}]\"")


addDocument(pathToResearchPapersFolder + department + '/', query, department)
