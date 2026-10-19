import requests

routes = [
    '/',
    '/about/',
    '/programs/',
    '/guides/',
    '/services/',
    '/corporate/',
    '/coaching/',
    '/group/',
    '/individual/'
]

base_url = 'http://localhost:8081'

for route in routes:
    try:
        response = requests.get(base_url + route, allow_redirects=True)
        print(f"{route}: {response.status_code}")
    except Exception as e:
        print(f"{route}: ERROR {e}")
