import glob
import os
import sys
import nltk
import logging
logging.basicConfig(format='%(asctime)s : %(levelname)s : %(message)s', level=logging.INFO)


from tika import parser
from nltk import word_tokenize
from gensim.summarization import summarize

input_path = sys.argv[1]

for input_file in glob.glob(os.path.join(input_path, '*.pdf')):
    # Grab the PDF's file name
    filename = os.path.basename(input_file)
    print filename

    # Use Tika to parse the PDF
    parsedPDF = parser.from_file(input_file)
    # Extract the text content from the parsed PDF
    pdf = parsedPDF["content"]
    # Convert double newlines into single newlines

    pdf = pdf.replace('\n\n', '\n')
    print type(pdf)
    print "length: " , len(pdf)
    print pdf

    print pdf[:100]

    text = nltk.Text(word_tokenize(pdf))
    print type(text)
    # print text[1024:1063]
    # print text.collocations()

    print summarize(text)

    # text.concordance("mitochondrial")
