import urllib2, json, sys

from settings import *

query = sys.argv[1]
# query = 'and'
department = sys.argv[2]
# department = 'PapersExplorer'
query = query.replace("+", " ")


def queryByField(searchSting, core):
    searchSting = searchSting.replace(" ", "%20")
    searchSting = searchSting.replace("(", "%28")
    searchSting = searchSting.replace(")", "%29")
    url = importQuery + core + '/select?q=*:*&fq=' + searchSting + '&wt=python&rows=2147483647&sort=timestamp%20desc&df=text'
    print url
    req = urllib2.Request(url)

    try:
        connection = urllib2.urlopen(req)
        response = eval(connection.read())
        return json.dumps(response['response']);

    except urllib2.HTTPError as e:
        print(str(e))


print queryByField(query, department)
