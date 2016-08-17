#!/usr/bin/env python3

import collections
import csv
import json
import os
import re
import subprocess
import sys
import tempfile

class PostlisteParser():
    @staticmethod
    def parseName(name):
        # the list often misses a space after the comma
        name = re.sub(',([^ ])', ', \\1', name)
        name = re.sub('  +', ' ', name).strip()

        # warn if missing comma
        if ',' not in name:
            print('Malformed name: %s' % name, file=sys.stderr)

        name = name.split(', ', 1)
        lastname = name[0]
        firstname = name[1] if len(name) > 0 else ''

        return (lastname, firstname)

    @staticmethod
    def parseRoom(room):
        room = room.replace(' ', '')
        return room

    def __init__(self):
        self.people = []

    def parseExcelFile(self, excelFile):
        tmp = tempfile._get_default_tempdir() + os.path.sep + 'beboere.csv'
        self.convertToCsv(excelFile, tmp)
        self.people = self.loadFromCsv(tmp)
        os.remove(tmp)

    @staticmethod
    def convertToCsv(excelfile, outfile):
        ret = subprocess.call(["ssconvert", excelfile, outfile],
            stdout=subprocess.DEVNULL,
            stderr=subprocess.DEVNULL)

        if ret != 0:
            print("ssconvert failed, do you have it?", file=sys.stderr)
            sys.exit(1)

    def loadFromCsv(self, csvFile):
        people = []
        others_col = None

        with open(csvFile, 'r') as f:
            reader = csv.reader(f)
            next(reader)  # skip header
            for row in reader:
                for coli in range(0, len(row)):
                    if coli % 2 == 0:
                        continue
                    name = row[coli-1]
                    room = PostlisteParser.parseRoom(row[coli])

                    # if we hit "PORTEN" we skip later rows in that col
                    if others_col != None and coli == others_col:
                        continue
                    elif others_col == None and 'PORTEN' in name:
                        others_col = coli
                        continue
                    elif 'ADMINIS' in name or name == '':
                        continue

                    name = PostlisteParser.parseName(name)

                    for person in people:
                        if name[0] == person['lastname'] and name[1] == person['firstname']:
                            print("Found duplicate entry of %s %s - skipping" % (name[1], name[0]), file=sys.stderr)
                            break

                    people.append({
                        'firstname': name[1],
                        'lastname': name[0],
                        'room': room
                    })

        people = sorted(people, key=lambda person: person['lastname'] + person['firstname'])
        return people

    def storeAsJson(self, fileName):
        with open(fileName, 'w') as f:
            json.dump(self.people, f)

if __name__ == '__main__':
    if len(sys.argv) <= 1:
        print("Missing input file (Excel file to parse)")
        print("Syntax: %s <inputfile.xls> [<outputfile.json>]" % sys.argv[0])
        sys.exit(1)

    parser = PostlisteParser()
    parser.parseExcelFile(sys.argv[1])

    if len(sys.argv) > 2:
        with open(sys.argv[2], 'w') as f:
            json.dump(parser.people, f, indent=2)
            print("Found %d people. Save to %s" % (len(parser.people), sys.argv[2]))
    else:
        print(json.dumps(parser.people, indent=2))
