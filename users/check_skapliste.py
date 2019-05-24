#!/usr/bin/env python3

import json
import re
import requests
import sys
import check_users
from bs4 import BeautifulSoup

ignorelist = [
    'Arkivaren',
    'Brun Sprit',
    'Festforeningen',
    'Hjæmmerbryggerlaug',
    'Hyttestyret',
    'IFBS',
    'Katiba',
    'Kollegiet',
    'LEDIG',
    'Studentkjøkkenet',
    'UKA',
]

def getLockerList(confluence_user, confluence_pass):
    result = []
    uri = 'https://foreningenbs.no/confluence/rest/api/content/2034011?expand=body.export_view'
    response = requests.get(uri, auth=requests.auth.HTTPBasicAuth(confluence_user, confluence_pass))

    data = response.json()['body']['export_view']['value']
    soup = BeautifulSoup(data, 'html.parser')

    location = "Unknown"
    for tag in soup.find_all(re.compile(r'(tr|h[1-6])')):
        if tag.name[0:1] == "h":
            location = tag.string.strip()
        else:
            cols = tag.find_all('td')
            if len(cols) < 2:
                continue

            for br in cols[1].find_all('br'):
                br.replace_with('\n')

            locker = cols[0].get_text()
            names = cols[1].get_text()

            for name in names.split('\n'):
                result.append({
                    'location': location,
                    'locker': locker.strip(),
                    'name': name.strip()
                })

    return result

if __name__ == '__main__':
    try:
        with open('confluence-api-user.txt', 'r') as f:
            data = f.read().split(':')
            if len(data) != 2:
                print("Store username:password in confluence-api-user.txt!")
                sys.exit(1)
            confluence_user = data[0].strip()
            confluence_pass = data[1].strip()
    except:
        print("Store username:password in confluence-api-user.txt!")
        sys.exit(1)

    if len(sys.argv) <= 1:
        print("Syntax: %s <beboerliste.json>" % sys.argv[0])
        sys.exit(1)

    with open(sys.argv[1], 'r') as f:
        people = sorted(json.load(f), key=lambda person: '%s %s' % (person['firstname'], person['lastname']))

    lockers = getLockerList(confluence_user, confluence_pass)

    users = check_users.getUsers()
    users_names = [user['realname'] for user in users]

    people_names = ['%s %s' % (person['firstname'], person['lastname']) for person in people]

    for locker in lockers:
        locker_name = locker['name']
        if locker_name.startswith('a)') or locker_name.startswith('b)'):
            locker_name = locker_name[2:].strip()
        if locker_name == '':
            continue

        foundex = False
        for ex in ignorelist:
            if ex in locker_name:
                foundex = True
        if foundex:
            continue

        match = check_users.findMatch(locker_name, people_names)
        if match == False:
            match = check_users.findMatch(locker_name, users_names)
            if match != False:
                for elm in users:
                    if elm['realname'] == match:
                        user = elm
                if 'utflyttet' in user['groups']:
                    print('Flyttet ut?    %-30s skap %s (%s)' % (locker_name, locker['locker'], locker['location']))
                    continue

            print('Ukjent:        %-30s skap %s (%s)' % (locker_name, locker['locker'], locker['location']))
