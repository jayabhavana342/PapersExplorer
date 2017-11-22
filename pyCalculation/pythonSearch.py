import urllib2
from os import walk

def addDocument(directory,filename):
    filepath=directory+filename
    filename=filename.replace(" ","%20")
    filename=filename.replace(",","%2c")
    filename=filename.replace("(","%28")
    filename=filename.replace(")","%29")
    with open(filepath,'rb') as data_file:
        my_data=data_file.read()
    url = 'http://localhost:8983/solr/Chemistry/update/extract?commit=true&literal._id='+filename
    req=urllib2.Request(url,data=my_data)
    req.add_header('content-type','application/pdf')
    try:
        f = urllib2.urlopen(req)
    except urllib2.HTTPError as e:
        print(str(e))

# def deleteDocuments():
#     filename = "48144546-0f73-4b2c-a1ca-676d23ede7f9"
#     # filename = filename.replace(" ", "%20")
#     # filename = filename.replace(",", "%2c")
#     # filename = filename.replace("(", "%28")
#     # filename = filename.replace(")", "%29")
#     print filename
#     # url='http://localhost:8983/solr/PapersExplorer/update?commit=true&stream.body=<delete><query>*:*</query></delete>'
#     url='http://localhost:8983/solr/PapersExplorer/update?commit=true&stream.body=<delete><id>'+filename+'</id></delete>'
#
#     # http://localhost:8983/solr/TABLE_NAME/update?stream.body=%3Cdelete%3E%3Cquery%3Eid:12%3C/query%3E%3C/delete%3E&commit=true
#     req=urllib2.Request(url)
#     try:
#         f = urllib2.urlopen(req)
#     except urllib2.HTTPError as e:
#         print(str(e))

def deleteDocuments():
    url='http://localhost:8983/solr/IOT/update?commit=true&stream.body=<delete><query>*:*</query></delete>'
    # url='http://localhost:8983/solr/test/update?commit=true&stream.body=<delete><query>id:lecture-04.pdf</query></delete>'

    # http://localhost:8983/solr/TABLE_NAME/update?stream.body=%3Cdelete%3E%3Cquery%3Eid:12%3C/query%3E%3C/delete%3E&commit=true
    req=urllib2.Request(url)
    try:
        f = urllib2.urlopen(req)
    except urllib2.HTTPError as e:
        print(str(e))

def addMultipleDocuments(directory):
    filenum=0
    f=[]
    for(dirpath,dirnames,filenames) in walk(directory):
        f.extend(filenames)
        break
    for filename in f:
        if filename.endswith(".pdf"):
            filenum += 1
            addDocument(directory,filename)
            print (filename +" added" )
    print (str(filenum)+" files added")

def queryByField(searchSting):
    searchSting = searchSting.replace(" ","%20")
    searchSting = searchSting.replace("(", "%28")
    searchSting = searchSting.replace(")", "%29")
    url = 'http://localhost:8983/solr/PapersExplorer/select?q=*:*&fq='+searchSting+'&wt=python'
    req = urllib2.Request(url)

    try:
        connection = urllib2.urlopen(req)
        response=eval(connection.read())
        print response['response']['numFound'], " documents found."
        for document in response['response']['docs']:
            print " id=",document['_id']
            print " Title=",document['last_modified']
    except urllib2.HTTPError as e:
        print(str(e))


#addDocument('/home/bhavanaa/PhpstormProjects/PapersExplorer/pythonSearch/papers/','pdf06-weirdchars.pdf')
deleteDocuments()
# addMultipleDocuments('../Research Papers/Chemistry/')
# queryByField("*:*")