import urllib2
import subprocess
import collections
import xmltodict
import json

from settings import *

query = sys.argv[1]
# query = 'Band.pdf'
department = sys.argv[2]


# department = 'test'


def findall(data, match):
    found = []

    for key, value in data.iteritems():
        if key == match:
            found.append(value)

        elif isinstance(value, dict):
            results = findall(value, match)
            for result in results:
                found.append(result)
        elif isinstance(value, list):
            for item in value:
                if isinstance(item, dict):
                    moreResults = findall(item, match)
                    for res in moreResults:
                        found.append(res)
    return found


def convert(data):
    if isinstance(data, basestring):
        return str(data)
    elif isinstance(data, collections.Mapping):
        return dict(map(convert, data.iteritems()))
    elif isinstance(data, collections.Iterable):
        return type(data)(map(convert, data))
    else:
        return data


def getData(data):
    if isinstance(data, dict):
        if '@type' in data.keys() and 'first' in data.values():
            if '#text' in data.keys():
                return data['#text']
        if '@type' in data.keys() and 'middle' in data.values():
            if '#text' in data.keys():
                return data['#text']


def getTitleData(data):
    if isinstance(data, dict):
        if '#text' in data.keys():
            return data['#text']


def getLastAddedDocumentID(id):
    url1 = importQuery + department + '/select?q=*:*&start=0&rows=1&sort=timestamp+desc&wt=python&indent=On'
    print url1
    request = urllib2.Request(url1)
    try:
        connection = urllib2.urlopen(request)
        response = eval(connection.read())
        # print type(response['response']['docs'])
        res = response['response']['docs'][0]
        # print type(res)
        for key, value in res.iteritems():
            if key == id:
                print "id:"
                print type(value)
                return value
    except urllib2.HTTPError as e:
        print(str(e))


def getAbstractData(data):
    if isinstance(data, dict):
        if 'p' in data.keys():
            return data['p']


def escapeSolrValue(str):
    match = ['\\', '+', '-', '&', '|', '!', '(', ')', '{', '}', '[', ']', '^', '~', '*', '?', ':', '"', ';', ' ']
    rep = ['\\\\', '\\+', '\\-', '\\&', '\\|', '\\!', '\\(', '\\)', '\\{', '\\}', '\\[', '\\]', '\\^', '\\~', '\\*',
           '\\?', '\\:', '\\"', '\\;', '\\ ']

    for x in range(0, len(match)):
        str = str.replace(match[x], rep[x])
    return str

def convertASCIItoStr(stri):
    try:
        stri = str(stri).decode('utf8')
    except UnicodeEncodeError:
        # already unicode
        pass

    return stri


def addDocument(directory, filename, core):
    filepath = directory + filename
    # filename = filename.replace(",", "%2c")
    # filename = filename.replace("(", "%28")
    # filename = filename.replace(")", "%29")
    # print filename
    with open(filepath, 'rb') as data_file:
        my_data = data_file.read()
    url = importQuery + core + '/update/extract?commit=true&literal._id=' + filename
    req = urllib2.Request(url, data=my_data)
    req.add_header('content-type', 'application/pdf')
    try:
        print 'started'
        f = urllib2.urlopen(req)
        # xml_command = 'curl -v --form input=@../ResearchPapers/Band.pdf http://cloud.science-miner.com/grobid/processFulltextDocument'
        xml_command = 'curl -v --form input=@' + pathToResearchPapersFolder + core + '/' + filename + ' http://cloud.science-miner.com/grobid/processFulltextDocument'
        # xml_command = 'curl -v --form input=@' + pathToResearchPapersFolder  + '/' + filename + ' http://cloud.science-miner.com/grobid/processFulltextDocument'
        # print xml_command
        p = subprocess.Popen(xml_command, shell=True, stdout=subprocess.PIPE)
        text, err = p.communicate()
        # print text
        root = xmltodict.parse(text)
        jsonData = json.dumps(root, indent=2)
        # print jsonData
        jsonData = json.loads(jsonData)
        print 'Title:'
        print '----------'
        title = findall(jsonData['TEI']['teiHeader']['fileDesc']['sourceDesc'], 'title')
        print title
        print type(title)
        # title = convert(title)
        # if isinstance(title, dict):
        #     title = getTitleData(title)
        # elif isinstance(title, list):
        #     title = getTitleData(title[0])
        # print title
        print "title:"

        title = convertASCIItoStr(title)
        print title
        print type(title)
        # title = title.replace(" ", "%20")

        # print getLastAddedDocumentID('id')

        print "Author Names:"
        authors = findall(jsonData['TEI']['teiHeader']['fileDesc']['sourceDesc'], 'author')
        authorNames = '';
        for y in authors:
            for x in y:
                if 'persName' in x:
                    foreNames = convert(x['persName']['forename'])
                    individualName = ''
                    if isinstance(foreNames, dict):
                        individualName = getData(foreNames) + ' '
                    elif isinstance(foreNames, list):
                        for i in range(0, len(foreNames)):
                            individualName += getData(foreNames[i]) + ' '
                    individualName += x['persName']['surname']
                    authorNames += individualName + ', '
        authorNames = authorNames[:-2]
        print authorNames
        authorNames = convertASCIItoStr(authorNames)
        authorNames = str(authorNames)
        print "authorname:"
        print type(authorNames)

        abstract = findall(jsonData['TEI']['teiHeader']['profileDesc'], 'abstract')
        if isinstance(abstract, dict):
            abstract = getAbstractData(abstract)
        elif isinstance(abstract, list):
            abstract = getAbstractData(abstract[0])
        # print abstract
        # abstract = abstract.replace('"', '\\"')
        # abstract = abstract.replace('-', '')
        # abstract = abstract.replace('+', '\\+')
        # abstract = abstract.replace('&', '\\&')
        # abstract = abstract.replace('|', '\\|')
        # abstract = abstract.replace('!', '\\!')
        # abstract = abstract.replace('(', '\\(')
        # abstract = abstract.replace('{', '\\{')
        # abstract = abstract.replace('}', '\\}')
        # abstract = abstract.replace('[', '\\[')
        # abstract = abstract.replace(']', '\\]')
        # abstract = abstract.replace('^', '\\^')
        # abstract = abstract.replace('~', '\\~')
        # abstract = abstract.replace('*', '\\*')
        # abstract = abstract.replace('?', '\\?')
        # abstract = abstract.replace(':', '\\:')
        # abstract = abstract.replace('\\', "\\")
        # abstract = abstract.replace(',', "")


        print 'abstract:'
        print abstract
        abstract = convertASCIItoStr(abstract)
        abstract = str(abstract)


        # abstract = escapeSolrValue(abstract)
        print type(abstract)

        print getLastAddedDocumentID('id')
        # abstract = "^"

        cmd = "curl localhost:8983/solr/" + core + "/update?commit=true -H 'Content-type:application/json' --data-binary " + "\"[{'id':'" + getLastAddedDocumentID(
            'id') + "','title':{'set':'" + title + "'},'author':{'set':'" + authorNames + "'},'abstract':{'set':'" + abstract + "'}}]\""
        print cmd
        pp = subprocess.Popen(cmd, shell=True, stdout=subprocess.PIPE)
        text, err = pp.communicate()
        print text

    except urllib2.HTTPError as e:
        print(str(e))


addDocument(pathToResearchPapersFolder + department + '/', query, department)
# addDocument('../ResearchPapers/', query, department)
