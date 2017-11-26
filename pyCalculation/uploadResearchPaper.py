import re
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


# Get the unique ID of recently added document
def getLastAddedDocumentID(file):
    try:
        r = urllib2.urlopen(urllib2.Request(
            importQuery + department + '/select?q=*:*&start=0&rows=1&sort=timestamp+desc&wt=python&indent=On'))
        # run_curl('curl '+importQuery + department + '/select?q=*:*&start=0&rows=1&sort=timestamp+desc&wt=python&indent=On')
        response = eval(r.read())
        res = response

        r.close()
        for key, value in response['response']['docs'][0].iteritems():
            if key == '_id_str':
                if value[0] == file:
                    print key, ":", value
                    print res['response']['docs'][0]['_id_str'][0]
                    # print ':' + value
                    # return value
                    return res['response']['docs'][0]['id']
    except urllib2.HTTPError as e:
        print(str(e))


# Adding document to solr core and updating the title, abstract and author names.
def addDocument(directory, filename, core):
    filepath = directory + filename

    with open(filepath, 'rb') as data_file:
        my_data = data_file.read()
    url = importQuery + core + '/update/extract?commit=true&literal._id=' + filename
    req = urllib2.Request(url, data=my_data)
    req.add_header('content-type', 'application/pdf')
    try:
        f = urllib2.urlopen(req)
        print f
    except urllib2.HTTPError as e:
        print(str(e))

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

    print tree

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

    run_curl(
        "curl localhost:8983/solr/" + core + "/update?commit=true -H 'Content-type:application/json' --data-binary " + "\"[{'id':'" + getLastAddedDocumentID(
            filename) + "','text':{'add':'Title => " + re.escape(convertASCIItoStr(title)) + "'}}]\"")

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

    run_curl(
        "curl localhost:8983/solr/" + core + "/update?commit=true -H 'Content-type:application/json' --data-binary " + "\"[{'id':'" + getLastAddedDocumentID(
            filename) + "','text':{'add':'Authors => " + re.escape(convertASCIItoStr(authorNames)) + "'}}]\"")

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

    run_curl(
        "curl localhost:8983/solr/" + core + "/update?commit=true -H 'Content-type:application/json' --data-binary " + "\"[{'id':'" + getLastAddedDocumentID(
            filename) + "','text':{'add':'Abstract => " + re.escape(convertASCIItoStr(abstract)) + "'}}]\"")

    # PDF text
    for elem in tree.iter():
        if 'body' in elem.tag:
            child = elem
            break

    heading = ''
    key = value = ''
    i = 0

    for elem in child.iter():
        if 'div' in elem.tag:
            value = ''
            for el in elem.iter():
                if 'head' in el.tag:
                    value = convertASCIItoStr(el.text) + ' => '
                if 'p' in el.tag:
                    if convertASCIItoStr(el.text) != 'None':
                        value += convertASCIItoStr(el.text) + ' '
                if 'ref' in el.tag:
                    value += convertASCIItoStr(el.tail) + ' '
            key = key.encode('utf-8')
            value = value.encode('utf-8')
            value = re.escape(convertASCIItoStr(value))
            run_curl(
                "curl localhost:8983/solr/" + core + "/update?commit=true -H 'Content-type:application/json' --data-binary " + "\"[{'id':'" + getLastAddedDocumentID(
                    filename) + "','text':{'add':'" + value + "'}}]\"")

            heading += value + ' '
            i = i + 1

    # print heading

    run_curl(
        "curl localhost:8983/solr/" + core + "/update?commit=true -H 'Content-type:application/json' --data-binary " + "\"[{'id':'" + getLastAddedDocumentID(
            filename) + "','title':{'set':'" + re.escape(convertASCIItoStr(title)) + "'},'author':{'set':'" + re.escape(
            convertASCIItoStr(authorNames)) + "'},'abstract':{'set':'" + re.escape(
            convertASCIItoStr(abstract)) + "'},'annotation':{'set':'Null'}}]\"")


addDocument(pathToResearchPapersFolder + department + '/', query, department)
