#!/usr/bin/env python3

import difflib
import json
import sys
import urllib.request

def getUsers():
    with urllib.request.urlopen('https://foreningenbs.no/users-api/users?grouplevel=1') as response:
        data = response.read().decode('utf-8')
    data = json.loads(data)

    result = []
    for user in data['result']:
        result.append({
            'username': user['username'],
            'realname': user['realname'],
            'groups': list(user['groups_relation'].keys()) if len(user['groups_relation']) > 0 else []
        })

    return result

def findMatch(lookupValue, listOfNames):
    closeMatches = difflib.get_close_matches(lookupValue, listOfNames, n=4, cutoff=0.4)
    if len(closeMatches) == 0:
        return False

    # We need a stricter match, but we might miss e.g. a middle name
    # We do the strict match by looking at each possible match, picking
    # the shortest lengthed value of either the possible match or original
    # lookup value and looking up to check if every word is represented
    # in the longer variant, with some tollerance to misspelling
    for item in closeMatches:
        if len(item) > len(lookupValue):
            shortname = lookupValue.replace('.', '')
            longname = item.replace('.', '')
        else:
            shortname = item.replace('.', '')
            longname = lookupValue.replace('.', '')

        found = True
        for word in shortname.split():
            if difflib.get_close_matches(word, longname.split(), n=1, cutoff=0.8):
                continue

            # take into account that the name may be abbreviated
            foundlw = False
            for lw in longname.split():
                if word in lw:
                    foundlw = True
                    break

            if not foundlw:
                found = False
                break

        if found:
            return item

    return False

if __name__ == '__main__':
    if len(sys.argv) <= 1 or (sys.argv[1] == '-v' and len(sys.argv) <= 2):
        print("Syntaks: %s [-v] <beboerliste.json>" % sys.argv[0])
        sys.exit(1)

    verbose = False
    fname = sys.argv[1]
    if fname == '-v':
        verbose = True
        fname = sys.argv[2]

    with open(fname, 'r') as f:
        people = sorted(json.load(f), key=lambda person: '%s %s' % (person['firstname'], person['lastname']))

    users = getUsers()
    users_names = [user['realname'] for user in users]

    people_found = []
    users_found = []

    count_not_found = 0
    count_moved_out = 0

    for person in people:
        name = '%s %s' % (person['firstname'], person['lastname'])

        match = findMatch(name, users_names)
        if match == False:
            continue
        if name != match:
            if verbose:
                print("Fant tilsvarende navn %-30s registrert som %s" % (name, match))

        user = None
        for elm in users:
            # there might be multiple entries.. doh
            if elm['realname'] == match:
                users_found.append(elm)
                user = elm

        if 'beboer' not in user['groups']:
            print("Flyttet inn igjen? %s registrert som %s (%s)" % (name, user['realname'], user['username']))
        else:
            people_found.append(person)

    for person in people:
        if person not in people_found:
            print("Ikke-registrert person: %s %s" % (person['firstname'], person['lastname']))
            count_not_found += 1

    for user in users:
        if 'beboer' in user['groups'] and user not in users_found:
            print("./utflyttet.sh %-20s  # %s" % (user['username'], user['realname']))
            count_moved_out += 1

    print("%d personer i beboerliste. %d allerede registrert, %d mangler. %d flyttet ut" %
            (len(people), len(people_found), count_not_found, count_moved_out))
