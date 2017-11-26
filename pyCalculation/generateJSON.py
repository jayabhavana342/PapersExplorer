import re

from settings import *

id = sys.argv[1]
core = sys.argv[2]


def searchForAnnotation(id, core):
    fl = "_id_str+title+author+abstract+annotation"
    url = importQuery + core + '/select?q=id:' + id + '&fl=' + fl + '&wt=json'
    req = urllib2.Request(url)

    try:
        connection = urllib2.urlopen(req)
        response = eval(connection.read())
        return json.dumps(response['response']['docs'])

    except urllib2.HTTPError as e:
        print(str(e))


print searchForAnnotation(id, core)
