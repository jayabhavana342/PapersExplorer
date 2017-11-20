import os
import sys
from settings import *

operation = sys.argv[1]
coreName = sys.argv[2]

# operation = 'create'
# operation = 'delete'
# coreName = 'testt'

def coreOperations(operation, coreName):
    if operation == 'create':
        # create command
        command = fullPathToSolrBinFolder + " " + operation + " -c " + coreName
        result = os.system(command)

        # timestamp,abstract,author,annotation,summary,references,title
        run_curl("curl -X POST -H 'Content-type:application/json' --data-binary \"{"
                 "\"add-field\":["
                 "{\"name\":\"timestamp\",\"type\":\"date\",\"indexed\":true,\"stored\":true,\"default\":\"NOW\",\"multiValued\":false},"
                 "{\"name\":\"abstract\",\"type\":\"string\",\"indexed\":true,\"stored\":true,\"docValues\":true},"
                 "{\"name\":\"author\",\"type\":\"string\",\"indexed\":true,\"stored\":true,\"docValues\":true},"
                 "{\"name\":\"annotation\",\"type\":\"string\",\"indexed\":true,\"stored\":true,\"docValues\":true},"
                 "{\"name\":\"summary\",\"type\":\"string\",\"indexed\":true,\"stored\":true,\"docValues\":true},"
                 "{\"name\":\"references\",\"type\":\"string\",\"indexed\":true,\"stored\":true,\"docValues\":true},"
                 "{\"name\":\"title\",\"type\":\"string\",\"indexed\":true,\"stored\":true,\"docValues\":true}"
                 "]}\" " + importQuery + coreName + "/schema  ")

        return result

    elif operation == 'delete':
        #delete command
        command = fullPathToSolrBinFolder + " " + operation + " -c " + coreName
        return os.system(command)

print coreOperations(operation, coreName)
