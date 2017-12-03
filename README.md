# Personal Papers-Explorer

Personal Papers-Explorer is analogous to a private pdf papers search engine for a Phd researcher student at Texas State University.

## Project Description, Scope
This project is aimed at developing an easy to use web application for the Personal Papers-Explorer engine. This engine is built using Solr which is a standalone enterprise text search server with a REST-like API. The web application facilitates a user i.e. a researcher to upload research papers of pdf format into various cores or projects and search through them. A machine learning library called GROBID (GeneRatio Of Bibliographic Data) API is used for extracting and parsing the title, authors, abstract and body text of pdf papers. These extracted data are stored in Solr via JSON over HTTP. PhP and Python scripts are developed to invoke Personal PaperExplorer APIs for accessing stored papers. The web interface also provides a text area for the researcher to annotate each research paper and this annotation can be exported in multiple formats such as JSON, XML for integration with other systems.

![image](https://user-images.githubusercontent.com/26471348/33521305-fbe81f02-d793-11e7-9db1-99b7b608321f.png)

### Prerequisites

Below softwares should be installed to run the Project:
* [Apache Solr 7.1.0](http://www.apache.org/dyn/closer.lua/lucene/solr/7.1.0)
* [Python 2.7](https://www.python.org/downloads/)
* [PHP 7.0](http://php.net/downloads.php)
* [GROBID](http://cloud.science-miner.com/grobid/)
* [Curl](https://curl.haxx.se/download.html)
* [XAMPP](https://www.apachefriends.org/index.html)

### Project Installation

Setup the project by changing:
In settings.php under includes folder:
![image](https://user-images.githubusercontent.com/26471348/33521270-2a608758-d793-11e7-8dd3-abb9c4c2d317.png)

$target_dir - Enter the full path to the folder where you want to store the research papers.

In settings.py under pyCalculation folder:
![image](https://user-images.githubusercontent.com/26471348/33521239-37167d00-d792-11e7-8227-261c3b2a899b.png)

* importQuery - Solr UI url.
* fullPathToSolrBinFolder - C:\solr-7.1.0\bin
* fullPathToSolrServerFolder - C:\solr-7.1.0\server\solr
* pathToResearchPapersFolder - Enter the full path to the folder where you want to store the research papers.

### Features
* **Automatic metadata extraction** - Personal Papers-Explorer automatically extracts author, title and other related metadata for analysis and document search.
* **Full-text indexing** - Personal Papers-Explorer indexes the full-text of the entire pdf papers. Full boolean, phrase search is supported.
* **Individual core capability search** - Personal Papers-Explorer is flexible with multiple core search where cores can be created by user according to the categories of the papers. 
* **Annotating the research papers** - The web interface also provides a text area for the researcher to annotate each research paper.
* **Exporting data to JSON format** - The extracted File name, Title, Authors, Abstract and Annotation can be exported into a downloadable JSON format file.

#### Creating a Solr Core
Core creation can be done by Solr API using CREATE:
```
curl "http://localhost:8983/solr/admin/cores?action=CREATE&name=ComputerScience"
```
Once the core is created, new fields such as timestamp, title, author, abstract, annotation, summary, references and text are added to the core schema:
```
curl -X POST -H 'Content-type:application/json' --data-binary 
"{"add-field":[
    {"name":"timestamp","type":"pdate","indexed":true,"stored":true,"default":"NOW","multiValued":false},
    {"name":"abstract","type":"string","indexed":true,"stored":true,"docValues":true},
    {"name":"author","type":"string","indexed":true,"stored":true,"docValues":true},
    {"name":"annotation","type":"string","indexed":true,"stored":true,"docValues":true},
    {"name":"summary","type":"string","indexed":true,"stored":true,"docValues":true},
    {"name":"references","type":"string","indexed":true,"stored":true,"docValues":true},
    {"name":"title","type":"string","indexed":true,"stored":true,"docValues":true}
    {"name":"text","type":"string","indexed":true,"stored":true,"docValues":true,"multiValued":true}
]}" http://localhost:8983/solr/ComputerScience/schema
```
To reflect these new changes in solr core, the core should be reloaded using RELOAD option:
```
curl "http://localhost:8983/solr/admin/cores?action=RELOAD&core=ComputerScience"
```
Screenshot for core creation:

![image](https://user-images.githubusercontent.com/26471348/33521385-427b7584-d796-11e7-9837-384cef685551.png)

#### Deleting a Solr Core
Solr core can be deleted using UNLOAD operation:
```
curl "http://localhost:8983/solr/admin/cores?action=UNLOAD&core=ComputerScience&deleteIndex=true&deleteDataDir=true&deleteInstanceDir=true"
```
Screenshot for core deletion:

![image](https://user-images.githubusercontent.com/26471348/33521390-69eeb31a-d796-11e7-964e-1f0156c19c02.png)

#### Upload a PDF Document to Core
A pdf document can be uploaded to the Solr core by API call:
```
http://localhost:8983/solr/ComputerScience/update/extract?commit=true&literal._id=Band.pdf
```
The Research Papers PDF documents can be extracted by GROBID API call:
```
curl -v --form input=@./ResearchPapers/ComputerScience/Band.pdf http://cloud.science-miner.com/grobid/processFulltextDocument
```

#### Deleting a PDF Document from Core
A uploaded document can be deleted using the uniqie index of the document:
```
curl http://localhost:8983/solr/ComputerScience/update?commit=true -H "Content-Type: text/xml" --data-binary "<delete><id>4496b4f7-42ad-4dee-92ad-daa78b5c32b9</id></delete>"
```
#### Search for a PDF Document using Solr Boolean Queries:
The three different Boolean query parsers used are:

![image](https://user-images.githubusercontent.com/26471348/33521436-988d7c6e-d797-11e7-8edc-56734af5fef0.png)

To retieve all the documents:
```
http://localhost:8983/solr/ComputerScience/select?q=*:*&fq=*:*&wt=python&rows=2147483647&sort=timestamp%20desc&df=text
```
The "fq" field is changed base on the queries such as:
* Query using AND:

    	title:*Detection* AND author:*Tech*
        This will retrieve the documents whose title contains “Detection” in title and “Tech” in author.
* Query using OR:

    	title:*Detection* OR author:*Tech*
        This will retrieve the documents whose title contains “Detection” or the author name contains “Tech”.
* Query using NOT (-):

    	title:*Detection* AND -author:*Tech*
        This will retrieve the documents whose title contains “Detection” and documents whose author doesn’t have “Tech”.

#### Annotating the Documents
The annotation field for each pdf paper can be updated using the Solr API call:
```
curl localhost:8983/solr/ComputerScience/update?commit=true -H 'Content-type:application/json' --data-binary "[{'id':'148796b5-89c3-4e5b-bad9-ee8b5b2d449b','annotation':{'set':'This is test annotation'}}]"
```

![image](https://user-images.githubusercontent.com/26471348/33521501-dd25035a-d798-11e7-8b9d-a6cd421a9f93.png)

Once the documents are annotate, the data can be exported to JSON file:

![image](https://user-images.githubusercontent.com/26471348/33521514-190ac01c-d799-11e7-838d-0c243e0d0020.png)

### Link to Project Video
[![image](https://user-images.githubusercontent.com/26471348/33521964-71d4f2b4-d7a6-11e7-9c92-8a6133e4a196.png)](https://www.youtube.com/watch?v=9h6BXYqBe-c&feature=youtu.be)

### Course

**CS5395-004** Independent study with [Dr.Anne Hee Hiong Ngu](http://cs.txstate.edu/~hn12/) at Texas State University.
