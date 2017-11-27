import re

from settings import *
import re

# newAnnotation = 'hello'
# id = 'ee2a25c7-5e9e-44de-8537-3c6b48215149'
# core = 'IOT'

newAnnotation = sys.argv[1]
id = sys.argv[2]
core = sys.argv[3]

print re.escape(newAnnotation)

def addAnnotation(annotation, id, core):
    print annotation, id, core
    pp = subprocess.Popen(
        "curl localhost:8983/solr/" + core + "/update?commit=true -H 'Content-type:application/json' --data-binary " + "\"[{'id':'" + id
        + "','annotation':{'set':'" + annotation + "'}}]\"",
        shell=True, stdout=subprocess.PIPE)
    text, err = pp.communicate()

    if text is not None:
        print text
    if err is not None:
        print err

    pp.kill()


addAnnotation(newAnnotation, id, core)
