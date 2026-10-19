import requests
from bs4 import BeautifulSoup

routes = [
    '/',
    '/about/',
    '/contact/',
    '/programs/',
    '/guides/',
    '/services/',
    '/corporate/',
    '/coaching/',
    '/group/',
    '/individual/',
    '/services/corporate/',
    '/coaching/individual/',
    '/coaching/group/'
]

base_url = 'http://localhost:8081'

for route in routes:
    try:
        response = requests.get(base_url + route, allow_redirects=True)
        print(f"{route}: {response.status_code}")
        
        # Check if template rendered by looking for common theme classes
        soup = BeautifulSoup(response.text, 'html.parser')
        main_el = soup.find('main', id='primary')
        if main_el:
            print(f"  -> Template rendered successfully (main#primary found).")
            # If it's one of the new pages, check for specific hero content
            if route == '/services/':
                if 'Services Hub' in response.text or 'Strategic AI capability' in response.text:
                    print("  -> Confirmed page-services.php content.")
            elif route == '/corporate/':
                if 'Corporate Training' in response.text or 'AI capability building designed for organizations' in response.text:
                    print("  -> Confirmed page-corporate.php content.")
            elif route == '/coaching/':
                if 'Coaching Hub' in response.text or 'Personalized guidance' in response.text:
                    print("  -> Confirmed page-coaching.php content.")
            elif route == '/group/':
                if 'Group Coaching' in response.text or 'Learn, discuss, and grow together' in response.text:
                    print("  -> Confirmed page-group.php content.")
            elif route == '/individual/':
                if 'Individual Coaching' in response.text or 'Focused guidance' in response.text:
                    print("  -> Confirmed page-individual.php content.")
        else:
            print(f"  -> Warning: Template may not have rendered properly.")
            
    except Exception as e:
        print(f"{route}: ERROR {e}")
