import urllib2
import sys
import json

from settings import *

query = sys.argv[1]
department = sys.argv[2]


def addDocument(directory, filename, core):
    # filepath = directory + filename
    filename = filename.replace(" ", "%20")
    filename = filename.replace(",", "%2c")
    filename = filename.replace("(", "%28")
    filename = filename.replace(")", "%29")

    url = importQuery + core + '/select?q=_id:' + filename + '&wt=python&rows=2147483647'
    req = urllib2.Request(url)

    try:
        connection = urllib2.urlopen(req)
        response = eval(connection.read())
        if json.dumps(response['response']['numFound'] > 0):
            for i in range(0, response['response']['numFound']):
                # print response['response']["docs"]
                url2 = 'http://localhost:8983/solr/Biology/update?commit=true&stream.body=<delete><id>' + \
                       response['response']["docs"][i]["id"] + '</id></delete>'
                request = urllib2.Request(url2)
                try:
                    conn = urllib2.urlopen(request)
                    res = eval(conn.read())
                    print json.dumps(res)
                except urllib2.HTTPError as e:
                    print(str(e))
    except urllib2.HTTPError as e:
        print(str(e))


addDocument('ResearchPapers/' + department + '/', query, department)
