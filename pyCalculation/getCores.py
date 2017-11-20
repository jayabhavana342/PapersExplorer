import urllib2
import json

from settings import *


def getCores():
    url = importQuery + 'admin/cores?action=STATUS&wt=python'
    req = urllib2.Request(url)
    try:
        connection = urllib2.urlopen(req)
        response = eval(connection.read())

        return json.dumps(response["status"])
    except urllib2.HTTPError as e:
        print(str(e))


print getCores()
