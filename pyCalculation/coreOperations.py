import shutil, errno
from settings import *

operation = sys.argv[1]
coreName = sys.argv[2]


# operation = 'create'
# operation = 'delete'
# coreName = 'testt'

def coreOperations(operation, coreName):
    if operation == 'create':

        try:
            shutil.copytree(fullPathToSolrServerFolder + "configsets/_default/conf",
                            fullPathToSolrServerFolder + coreName + "/conf")
        except OSError as exc:  # python >2.5
            if exc.errno == errno.ENOTDIR:
                shutil.copy(src, dst)
            else:
                raise

        run_curl("curl \"" + importQuery + "admin/cores?action=CREATE&name=" + coreName + "\"")

        # timestamp,abstract,author,annotation,summary,references,title
        run_curl("curl -X POST -H 'Content-type:application/json' --data-binary \"{"
                 "\"add-field\":["
                 "{\"name\":\"timestamp\",\"type\":\"pdate\",\"indexed\":true,\"stored\":true,\"default\":\"NOW\",\"multiValued\":false},"
                 "{\"name\":\"abstract\",\"type\":\"string\",\"indexed\":true,\"stored\":true,\"docValues\":true},"
                 "{\"name\":\"author\",\"type\":\"string\",\"indexed\":true,\"stored\":true,\"docValues\":true},"
                 "{\"name\":\"annotation\",\"type\":\"string\",\"indexed\":true,\"stored\":true,\"docValues\":true},"
                 "{\"name\":\"summary\",\"type\":\"string\",\"indexed\":true,\"stored\":true,\"docValues\":true},"
                 "{\"name\":\"references\",\"type\":\"string\",\"indexed\":true,\"stored\":true,\"docValues\":true},"
                 "{\"name\":\"title\",\"type\":\"string\",\"indexed\":true,\"stored\":true,\"docValues\":true}"
                 "]}\" " + importQuery + coreName + "/schema  ")

        run_curl('curl "' + importQuery + 'admin/cores?action=RELOAD&core=' + coreName + '"')

    elif operation == 'delete':
        run_curl("curl \"" +
                 importQuery + "admin/cores?action=UNLOAD&core=" + coreName + "&deleteIndex=true&deleteDataDir=true&deleteInstanceDir=true\"")


coreOperations(operation, coreName)
