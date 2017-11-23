from settings import *

query = sys.argv[1]
department = sys.argv[2]


# query = '4496b4f7-42ad-4dee-92ad-daa78b5c32b9'
# department = 'test'


def addDocument(directory, id, core):
    url = importQuery + core + '/select?q=*:*&fq=id:' + id + '&wt=python&rows=2147483647'
    req = urllib2.Request(url)
    try:
        connection = urllib2.urlopen(req)
        response = eval(connection.read())
        file = directory + response['response']['docs'][0]['_id'][0]
        if os.path.isfile(file):
            os.remove(file)
        else:
            print("Error: %s file not found" % file)

    except urllib2.HTTPError as e:
        print(str(e))

    run_curl(
        'curl ' + importQuery + core + '/update?commit=true -H \"Content-Type: text/xml\" --data-binary "<delete><id>' + id + '</id></delete>"')


addDocument(pathToResearchPapersFolder + department + '/', query, department)
